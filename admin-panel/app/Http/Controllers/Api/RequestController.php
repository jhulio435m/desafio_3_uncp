<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BotRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'representative_name' => 'required|string|max:255',
            'representative_dni' => 'required|string|max:20',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|string|max:50',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        $ticketId = strtoupper(Str::random(6));

        $botRequest = BotRequest::create(array_merge($validated, [
            'ticket_id' => $ticketId,
            'status' => 'Recibido'
        ]));

        return response()->json([
            'success' => true,
            'ticket_id' => $ticketId,
            'message' => 'Solicitud registrada correctamente.'
        ], 201);
    }

    public function show($ticket_id)
    {
        $botRequest = BotRequest::where('ticket_id', $ticket_id)->first();

        if (!$botRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ticket_id' => $botRequest->ticket_id,
                'status' => $botRequest->status,
                'institution_name' => $botRequest->institution_name,
                'created_at' => $botRequest->created_at,
            ]
        ]);
    }
}
