<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    
    protected $fillable = [
        'name', 'name_ko', 'description', 'category', 
        'price', 'emoji', 'image', 'bg_class', 'is_available', 'is_featured'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'integer'
    ];

    /**
     * Get image URL from storage
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // Cek di storage
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }
        
        // Fallback ke public/images
        $publicPath = public_path('images/' . $this->image);
        if (file_exists($publicPath)) {
            return asset('images/' . $this->image);
        }
        
        // Fallback ke storage/menus dengan nama file saja
        $storagePath = 'menus/' . basename($this->image);
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->url($storagePath);
        }
        
        return null;
    }
    
    /**
     * Check if image exists
     */
    public function hasImage()
    {
        if (!$this->image) {
            return false;
        }
        
        if (Storage::disk('public')->exists($this->image)) {
            return true;
        }
        
        return file_exists(public_path('images/' . $this->image));
    }
    
    /**
     * Delete image file
     */
    public function deleteImage()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
    }
    
    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    
    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('name_ko', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
    }
}