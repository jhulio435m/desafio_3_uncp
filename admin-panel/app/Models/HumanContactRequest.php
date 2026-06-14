<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumanContactRequest extends Model
{
    protected $fillable = [
        'citizen_name',
        'phone',
        'topic',
        'message',
        'preferred_channel',
        'status',
        'internal_notes',
        'contacted_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRelatedRequest()
    {
        if (!$this->citizen_name) return null;
        return \App\Models\BotRequest::where('representative_name', 'like', '%' . $this->citizen_name . '%')->first();
    }

    public function getRelatedRequestAttribute()
    {
        return $this->getRelatedRequest();
    }
}
