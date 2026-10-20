<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'image',
        'stock',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if (!empty($product->image) && !Str::startsWith($product->image, ['http://', 'https://', 'data:image'])) {
                if (Storage::disk('public')->exists($product->image)) {
                    $path = Storage::disk('public')->path($product->image);
                    $mime = mime_content_type($path) ?: 'image/jpeg';
                    $data = @file_get_contents($path);
                    if ($data && strlen($data) < 4 * 1024 * 1024) {
                        $product->image = 'data:' . $mime . ';base64,' . base64_encode($data);
                    }
                }
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80';
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:image'])) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
