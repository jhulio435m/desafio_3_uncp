<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_bulk_save_contact_schedules(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $contact = Contact::create([
            'name' => 'Dr. Test Responsable',
            'office' => 'Oficina de Prueba',
            'phone' => '987654321',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('bot.contacts.schedules.store', $contact), [
                'schedules' => [
                    [
                        'day_of_week' => 1,
                        'start_time' => '08:00',
                        'end_time' => '13:00',
                    ],
                    [
                        'day_of_week' => 1,
                        'start_time' => '14:00',
                        'end_time' => '17:00',
                    ],
                    [
                        'day_of_week' => 3,
                        'start_time' => '09:00',
                        'end_time' => '12:00',
                    ],
                ]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('contact_schedules', 3);

        $this->assertDatabaseHas('contact_schedules', [
            'contact_id' => $contact->id,
            'day_of_week' => 1,
            'start_time' => '08:00:00',
            'end_time' => '13:00:00',
        ]);

        $this->assertDatabaseHas('contact_schedules', [
            'contact_id' => $contact->id,
            'day_of_week' => 1,
            'start_time' => '14:00:00',
            'end_time' => '17:00:00',
        ]);
    }

    public function test_schedules_fails_if_end_time_is_before_or_equal_to_start_time(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $contact = Contact::create([
            'name' => 'Dr. Test Responsable',
            'office' => 'Oficina de Prueba',
            'phone' => '987654321',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->from(route('bot.contacts'))
            ->post(route('bot.contacts.schedules.store', $contact), [
                'schedules' => [
                    [
                        'day_of_week' => 2,
                        'start_time' => '15:00',
                        'end_time' => '13:00', // Invalid: end_time before start_time
                    ]
                ]
            ]);

        $response->assertRedirect(route('bot.contacts') . '#contact-' . $contact->id);
        $this->assertDatabaseCount('contact_schedules', 0);
    }

    public function test_updating_contact_without_schedules_clears_existing_schedules(): void
    {
        $user = User::factory()->create();
        $contact = Contact::create([
            'name' => 'Dr. Test Responsable',
            'office' => 'Oficina de Prueba',
            'phone' => '987654321',
            'email' => 'responsable@uncp.edu.pe',
            'attention_hours' => 'Lunes 8 a 13',
            'topics' => 'Proyección social',
            'is_active' => true,
        ]);

        $contact->schedules()->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '13:00',
        ]);

        $response = $this->actingAs($user)
            ->patch(route('bot.contacts.update', $contact), [
                'name' => 'Dr. Test Responsable',
                'office' => 'Oficina de Prueba',
                'phone' => '987654321',
                'email' => 'responsable@uncp.edu.pe',
                'attention_hours' => 'Sin horario estructurado',
                'topics' => 'Proyección social',
                'is_active' => '1',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('contact_schedules', 0);
    }
}
