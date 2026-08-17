<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnEvidence extends Model
{
    protected $fillable = [
        'return_item_id',
        'image_path',
    ];

    public function returnItem(): BelongsTo
    {
        return $this->belongsTo(ReturnItem::class, 'return_item_id');
    }

    public function imageUrl(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
