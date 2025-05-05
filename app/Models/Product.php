<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'base_cost',
        'sale_price',
        'description',
        'image',
        'discount_amount',
        'is_percentage',
        'discount_start',
        'discount_end',
        'user_id',
    ];

    protected $casts = [
        'discount_start' => 'date',
        'discount_end' => 'date',
        'is_percentage' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function getCurrentPrice()
    {
        if ($this->hasActiveDiscount()) {
            if ($this->is_percentage) {
                return $this->sale_price - ($this->sale_price * $this->discount_amount / 100);
            } else {
                return $this->sale_price - $this->discount_amount;
            }
        }
        return $this->sale_price;
    }

    public function hasActiveDiscount()
    {
        if (!$this->discount_amount) {
            return false;
        }

        $today = now()->startOfDay();
        
        if ($this->discount_start && $this->discount_end) {
            return $today->between($this->discount_start, $this->discount_end);
        }
        
        return false;
    }
}