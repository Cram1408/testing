<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'tagline',
        'short_description',
        'description',
        'ingredients',
        'spice_level',
        'shelf_life',
        'storage_instructions',
        'image',
        'gallery',
        'is_featured',
        'is_bestseller',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true)->withDefault(function () {
            return $this->variants()->first();
        });
    }

    public function getMinPriceAttribute()
    {
        return $this->variants->min('price') ?? 0;
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants->max('price') ?? 0;
    }

    public function getStartingPriceAttribute()
    {
        $default = $this->variants->where('is_default', true)->first() ?: $this->variants->first();
        return $default ? ($default->sale_price ?? $default->price) : 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }
}
