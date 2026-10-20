<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge',
        'image',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($banner) {
            if (!empty($banner->image) && !Str::startsWith($banner->image, ['http://', 'https://', 'data:image'])) {
                if (Storage::disk('public')->exists($banner->image)) {
                    $path = Storage::disk('public')->path($banner->image);
                    $mime = mime_content_type($path) ?: 'image/jpeg';
                    $data = @file_get_contents($path);
                    if ($data && strlen($data) < 4 * 1024 * 1024) {
                        $banner->image = 'data:' . $mime . ';base64,' . base64_encode($data);
                    }
                }
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return '';
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:image'])) {
            return $this->image;
        }

        return Storage::url($this->image);
    }
}
