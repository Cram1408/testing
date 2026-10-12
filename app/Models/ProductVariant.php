<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'weight_value',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_default' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute()
    {
        return ($this->sale_price && $this->sale_price < $this->price)
            ? $this->sale_price
            : $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
