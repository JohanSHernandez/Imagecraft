<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'thumbnail_path',
        'album_id',
        'is_selected',
        'product_id',
        'edit_type',
        'comments',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}