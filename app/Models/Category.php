<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];
    /**
     * Get the products associated with the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected $casts=[
        'status' => 'boolean',
    ];

    // boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

     protected $appends = [
        'image_url',
    ];

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
