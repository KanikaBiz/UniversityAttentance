<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'unit_price',
        'sale_price',
        'image',
        'status',
        'category_id',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $appends = [
        'image_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        // return $this->image ? asset($this->image) : null;
        if (!$this->image) {
            return asset('images/default.png');
        }
        // Check if the image is stored in the local disk
        if (Storage::disk('local')->exists($this->image)) {
            return Storage::url($this->image);
        }
        // If the image is not found in the local disk, return null
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        // If the image is not found in either disk, return null
        return $this->image ? Storage::url($this->image) : null;
    }
}
