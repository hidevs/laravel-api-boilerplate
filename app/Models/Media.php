<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasFactory, SoftDeletes;
    const TYPES = [
        User::class
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function (Media $media) {
            $media->attributes['user_id'] = auth()->check() ? auth()->user()->id : null;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getConversionsUrls(array $conversionsName = [], bool $only = true): array
    {
        $result = [];
        foreach ($this->getMediaConversionNames() as $conversion)
            $result[$conversion] = $this->getUrl($conversion);
        if (!!count($conversionsName))
            return $only
                ? Arr::only($result, $conversionsName)
                : Arr::except($result, $conversionsName);
        $result['_original'] = $this->getUrl();
        return $result;
    }
}
