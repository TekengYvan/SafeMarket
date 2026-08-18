<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['vendor_id', 'category_id', 'title', 'description', 'price', 'discount_price', 'is_on_sale', 'condition', 'status', 'location', 'is_in_stock', 'has_invoice'])]
class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products');
        $this->addMediaCollection('invoices')->singleFile();
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->reviews()->avg('product_rating') ?? 5.0), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getEffectivePriceAttribute()
    {
        if ($this->is_on_sale && $this->discount_price > 0) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function getImageUrl(): string
    {
        if ($this->hasMedia('products')) {
            $url = $this->getFirstMediaUrl('products');
            if ($url) {
                $parsed = parse_url($url);
                if (isset($parsed['path']) && str_contains($parsed['path'], 'storage/')) {
                    return $parsed['path'];
                }
                return $url;
            }
        }

        if ($this->images()->exists() && $this->images->first()) {
            $path = $this->images->first()->image_path;
            if ($path) {
                return \Illuminate\Support\Facades\Storage::url($path);
            }
        }

        $slug = \Illuminate\Support\Str::slug($this->title);
        if (str_contains($slug, 'iphone')) {
            return asset('images/products/iphone.jpg');
        }
        if (str_contains($slug, 'macbook')) {
            return asset('images/products/macbook.jpg');
        }
        if (str_contains($slug, 'nike')) {
            return asset('images/products/nike.jpg');
        }
        if (str_contains($slug, 'sac')) {
            return asset('images/products/backpack.jpg');
        }

        return 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80';
    }
}
