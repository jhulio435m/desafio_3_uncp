<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\HumanContactController;

Route::post('/requests', [RequestController::class, 'store']);
Route::get('/requests/{ticket_id}', [RequestController::class, 'show']);
Route::post('/human-contacts', [HumanContactController::class, 'store']);
