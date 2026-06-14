<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotTranslation extends Model
{
    protected $fillable = ['bot_translation_key_id', 'lang', 'value'];

    public function translationKey(): BelongsTo
    {
        return $this->belongsTo(BotTranslationKey::class, 'bot_translation_key_id');
    }
}
