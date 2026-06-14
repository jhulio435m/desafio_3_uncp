<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotTranslationKey extends Model
{
    protected $fillable = ['key', 'group', 'label', 'description'];

    public function translations(): HasMany
    {
        return $this->hasMany(BotTranslation::class);
    }
}
