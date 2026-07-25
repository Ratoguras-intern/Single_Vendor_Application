<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'source_hash',
        'source_text',
        'source_locale',
        'target_locale',
        'translated_text',
    ];

    protected $hidden = [
        'source_text',
        'translated_text',
    ];

    public function getSourceHashAttribute(): string
    {
        return md5($this->source_text . $this->source_locale . $this->target_locale);
    }
}
