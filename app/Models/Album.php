<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'access_code',
        'access_type',
        'authorized_phones',
        'client_id',
        'user_id',
    ];

    protected $casts = [
        'authorized_phones' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function generateAccessCode()
    {
        $this->access_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        return $this->access_code;
    }

    public function canAccess($phone)
    {
        if ($this->access_type === 'general') {
            return true;
        }

        return in_array($phone, $this->authorized_phones ?? []);
    }
}