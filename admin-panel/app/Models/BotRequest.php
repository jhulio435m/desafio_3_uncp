<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'ticket_id',
        'representative_name',
        'representative_dni',
        'institution_name',
        'institution_type',
        'description',
        'location',
        'status',
    ];

    // Assuming Laravel will handle created_at and updated_at automatically if we keep them.
    // The init.sql uses TIMESTAMP DEFAULT CURRENT_TIMESTAMP, which matches Laravel defaults.
}
