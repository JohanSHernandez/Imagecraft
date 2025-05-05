<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function getTotalSalesAttribute()
    {
        return $this->sales->sum('total_amount');
    }

    public function getPendingBalanceAttribute()
    {
        return $this->sales->sum('total_amount') - $this->sales->sum('paid_amount');
    }
}