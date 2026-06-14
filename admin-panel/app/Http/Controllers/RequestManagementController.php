<?php

namespace App\Http\Controllers;

use App\Models\BotRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $requests = BotRequest::query()
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('ticket_id', 'ilike', "%{$search}%")
                  ->orWhere('institution_name', 'ilike', "%{$search}%")
                  ->orWhere('representative_name', 'ilike', "%{$search}%")
                  ->orWhere('location', 'ilike', "%{$search}%")
            ))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($from,   fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to,     fn($q) => $q->whereDate('created_at', '<=', $to))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('requests.index', compact('requests', 'search', 'status', 'from', 'to'));
    }

    public function create(Request $request)
    {
        $representative_name = $request->query('representative_name');
        $description = $request->query('description');

        return view('requests.create', compact('representative_name', 'description'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'representative_name' => 'required|string|max:255',
            'representative_dni' => 'required|string|max:20',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:Recibido,Evaluando,Asignado,En Ejecución,Finalizado',
        ]);

        $ticketId = strtoupper(Str::random(6));

        $botRequest = BotRequest::create(array_merge($validated, [
            'ticket_id' => $ticketId,
        ]));

        return redirect()->route('requests.index')
            ->with('status', "Solicitud formal creada con el Ticket ID: {$ticketId}");
    }

    public function show(BotRequest $botRequest)
    {
        return view('requests.show', ['request' => $botRequest]);
    }

    public function update(Request $request, BotRequest $botRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Recibido,Evaluando,Asignado,En Ejecución,Finalizado',
        ]);

        $botRequest->update($validated);

        return back()
            ->with('status', 'Estado de la solicitud actualizado correctamente.');
    }
}
