<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HumanContactRequest;
use Illuminate\Http\Request;

class HumanContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'citizen_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'topic' => 'nullable|string|max:255',
            'message' => 'required|string',
            'preferred_channel' => 'nullable|string|max:50',
        ]);

        $contactRequest = HumanContactRequest::create(array_merge($validated, [
            'status' => 'Pendiente',
            'preferred_channel' => $validated['preferred_channel'] ?? 'WhatsApp',
        ]));

        return response()->json([
            'success' => true,
            'data' => $contactRequest,
            'message' => 'Pedido de contacto registrado correctamente.'
        ], 201);
    }
}
