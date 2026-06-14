<?php

namespace App\Http\Controllers;

use App\Models\BotSetting;
use App\Models\BotRequest;
use App\Models\Contact;
use App\Models\ContactSchedule;
use App\Models\Faq;
use App\Models\HumanContactRequest;
use App\Models\KnowledgeCategory;
use App\Models\OfficialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BotManagementController extends Controller
{
    public function dashboard(): View
    {
        $requestStatuses = ['Recibido', 'Evaluando', 'Asignado', 'En Ejecución', 'Finalizado'];
        $requestStatusCounts = BotRequest::query()
            ->selectRaw('status, count(*) as total')
            ->whereIn('status', $requestStatuses)
            ->groupBy('status')
            ->pluck('total', 'status');

        $humanStatusCounts = HumanContactRequest::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard', [
            'activeFaqs' => Faq::where('is_active', true)->count(),
            'activeLinks' => OfficialLink::where('is_active', true)->count(),
            'activeContacts' => Contact::where('is_active', true)->count(),
            'pendingHumanContacts' => HumanContactRequest::where('status', 'Pendiente')->count(),
            'activeRequests' => BotRequest::count(),
            'recentContacts' => HumanContactRequest::latest()->take(4)->get(),
            'recentRequests' => BotRequest::latest()->take(5)->get(),
            'requestStatuses' => $requestStatuses,
            'requestStatusCounts' => $requestStatusCounts,
            'humanStatusCounts' => $humanStatusCounts,
            'settings' => BotSetting::orderBy('label')->get(),
        ]);
    }

    public function faqs(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $active = $request->input('active');

        $faqs = Faq::with('category')
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('question', 'ilike', "%{$search}%")
                  ->orWhere('keywords', 'ilike', "%{$search}%")
                  ->orWhere('answer', 'ilike', "%{$search}%")
            ))
            ->when($category, fn($q) => $q->where('knowledge_category_id', $category))
            ->when($active !== null && $active !== '', fn($q) => $q->where('is_active', (bool)$active))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('bot.faqs', [
            'categories' => KnowledgeCategory::orderBy('name')->get(),
            'faqs' => $faqs,
            'search' => $search,
            'selectedCategory' => $category,
            'selectedActive' => $active,
        ]);
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        Faq::create($this->validateFaq($request));

        return back()->with('status', 'Pregunta frecuente creada.');
    }

    public function updateFaq(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validateFaq($request));

        return back()->with('status', 'Pregunta frecuente actualizada.');
    }

    public function destroyFaq(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('status', 'Pregunta frecuente eliminada.');
    }

    public function links(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $links = OfficialLink::with('category')
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('url', 'ilike', "%{$search}%")
                  ->orWhere('keywords', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
            ))
            ->when($category, fn($q) => $q->where('knowledge_category_id', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('bot.links', [
            'categories' => KnowledgeCategory::orderBy('name')->get(),
            'links' => $links,
            'search' => $search,
            'selectedCategory' => $category,
        ]);
    }

    public function storeLink(Request $request): RedirectResponse
    {
        OfficialLink::create($this->validateLink($request));

        return back()->with('status', 'Enlace oficial creado.');
    }

    public function updateLink(Request $request, OfficialLink $officialLink): RedirectResponse
    {
        $officialLink->update($this->validateLink($request));

        return back()->with('status', 'Enlace oficial actualizado.');
    }

    public function destroyLink(OfficialLink $officialLink): RedirectResponse
    {
        $officialLink->delete();

        return back()->with('status', 'Enlace oficial eliminado.');
    }

    public function contacts(Request $request): View
    {
        $search = $request->input('search');
        $active = $request->input('active');

        $contacts = Contact::with('schedules')
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('office', 'ilike', "%{$search}%")
                  ->orWhere('topics', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%")
            ))
            ->when($active !== null && $active !== '', fn($q) => $q->where('is_active', (bool)$active))
            ->orderBy('office')
            ->paginate(12)
            ->withQueryString();

        return view('bot.contacts', [
            'contacts' => $contacts,
            'search' => $search,
            'selectedActive' => $active,
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $contact = Contact::create($this->validateContact($request));

        if ($request->has('schedules')) {
            $this->saveSchedules($contact, $request->input('schedules'));
        }

        return back()->with('status', 'Contacto creado.');
    }

    public function updateContact(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update($this->validateContact($request));

        $this->saveSchedules($contact, $request->input('schedules', []));

        return back()->with('status', 'Contacto actualizado.');
    }

    public function destroyContact(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return back()->with('status', 'Contacto eliminado.');
    }

    public function humanContacts(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $requests = HumanContactRequest::query()
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('citizen_name', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%")
                  ->orWhere('topic', 'ilike', "%{$search}%")
                  ->orWhere('message', 'ilike', "%{$search}%")
            ))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('bot.human-contacts', [
            'requests' => $requests,
            'statuses' => $this->humanContactStatuses(),
            'search' => $search,
            'selectedStatus' => $status,
        ]);
    }

    public function destroyHumanContact(HumanContactRequest $humanContactRequest): RedirectResponse
    {
        $humanContactRequest->delete();

        return back()->with('status', 'Pedido de contacto eliminado.');
    }

    public function updateHumanContact(Request $request, HumanContactRequest $humanContactRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in($this->humanContactStatuses())],
            'internal_notes' => ['nullable', 'string'],
        ]);

        $data['contacted_at'] = ($data['status'] !== 'Pendiente') ? ($humanContactRequest->contacted_at ?? now()) : null;
        $data['user_id'] = auth()->id();
        $humanContactRequest->update($data);

        return back()->with('status', 'Pedido de contacto actualizado.');
    }

    public function settings(): View
    {
        return view('bot.settings', [
            'settings' => BotSetting::orderBy('label')->get(),
            'categories' => KnowledgeCategory::orderBy('name')->get(),
        ]);
    }

    public function updateSetting(Request $request, BotSetting $botSetting): RedirectResponse
    {
        $botSetting->update($request->validate([
            'value' => ['required', 'string'],
        ]));

        return back()->with('status', 'Configuración actualizada.');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        KnowledgeCategory::updateOrCreate(['slug' => $data['slug']], $data);

        return back()->with('status', 'Categoría guardada.');
    }

    private function validateFaq(Request $request): array
    {
        $data = $request->validate([
            'knowledge_category_id' => ['required', 'exists:knowledge_categories,id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function validateLink(Request $request): array
    {
        $data = $request->validate([
            'knowledge_category_id' => ['nullable', 'exists:knowledge_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['required', 'url', 'max:600'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function validateContact(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'office' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'attention_hours' => ['nullable', 'string', 'max:255'],
            'topics' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function humanContactStatuses(): array
    {
        return ['Pendiente', 'En Contacto', 'Derivado a Trámite', 'Resuelto', 'Inubicable'];
    }

    public function getBotStatus(): \Illuminate\Http\JsonResponse
    {
        try {
            $response = Http::timeout(3)->get('http://app:3000/status');
            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            // Log or handle
        }

        return response()->json([
            'connected' => false,
            'state' => 'DESCONECTADO',
            'qrCode' => null,
            'number' => null,
            'error' => 'No se pudo conectar con el servicio del Bot.'
        ]);
    }

    public function logoutBot(): RedirectResponse
    {
        try {
            $response = Http::timeout(5)->post('http://app:3000/logout');
            if ($response->successful() && $response->json('success')) {
                return back()->with('status', 'Sesión de WhatsApp cerrada exitosamente.');
            }
            $error = $response->json('error') ?? 'Error desconocido';
            return back()->withErrors(['bot' => 'Error al cerrar sesión del bot: ' . $error]);
        } catch (\Exception $e) {
            return back()->withErrors(['bot' => 'No se pudo comunicar con el servicio del bot para cerrar sesión.']);
        }
    }

    public function restartBot(): RedirectResponse
    {
        try {
            $response = Http::timeout(5)->post('http://app:3000/restart');
            if ($response->successful() && $response->json('success')) {
                return back()->with('status', 'Se solicitó el reinicio del Bot de WhatsApp. Tardará unos segundos.');
            }
            return back()->withErrors(['bot' => 'Error al reiniciar el bot.']);
        } catch (\Exception $e) {
            return back()->withErrors(['bot' => 'No se pudo comunicar con el servicio del bot para reiniciar.']);
        }
    }

    public function storeContactSchedule(Request $request, $contact): RedirectResponse
    {
        if (!$contact instanceof Contact) {
            $contact = Contact::findOrFail($contact);
        }

        $request->validate([
            'schedules' => ['nullable', 'array'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:1,7'],
            'schedules.*.start_time' => ['required', 'string'],
            'schedules.*.end_time' => ['required', 'string'],
        ]);

        try {
            $this->saveSchedules($contact, $request->input('schedules') ?? []);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->to(url()->previous() . '#contact-' . $contact->id)
                ->withErrors($e->errors());
        }

        return redirect()->to(url()->previous() . '#contact-' . $contact->id)
            ->with('status', 'Horarios de atención actualizados con éxito.');
    }

    public function destroyContactSchedule(ContactSchedule $contactSchedule): RedirectResponse
    {
        $contactId = $contactSchedule->contact_id;
        $contactSchedule->delete();

        return redirect()->to(url()->previous() . '#contact-' . $contactId)
            ->with('status', 'Horario de atención eliminado.');
    }

    private function saveSchedules(Contact $contact, array $schedules): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($contact, $schedules) {
            $contact->schedules()->delete();

            foreach ($schedules as $schData) {
                if (empty($schData['start_time']) || empty($schData['end_time'])) {
                    continue;
                }

                $startTime = date('H:i', strtotime($schData['start_time']));
                $endTime = date('H:i', strtotime($schData['end_time']));

                if ($endTime <= $startTime) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'schedules' => "La hora de fin ($endTime) debe ser posterior a la hora de inicio ($startTime).",
                    ]);
                }

                $contact->schedules()->create([
                    'day_of_week' => $schData['day_of_week'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
            }
        });
    }
}
