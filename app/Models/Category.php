<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (!empty($category->image) && !Str::startsWith($category->image, ['http://', 'https://', 'data:image'])) {
                if (Storage::disk('public')->exists($category->image)) {
                    $path = Storage::disk('public')->path($category->image);
                    $mime = mime_content_type($path) ?: 'image/jpeg';
                    $data = @file_get_contents($path);
                    if ($data && strlen($data) < 4 * 1024 * 1024) {
                        $category->image = 'data:' . $mime . ';base64,' . base64_encode($data);
                    }
                }
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=300&q=80';
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:image'])) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
