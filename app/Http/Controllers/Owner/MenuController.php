<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $query = Menu::query();
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('name_ko', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->orderBy('name')->get();
        
        return view('owner.menus', compact('menus'));
    }
    
    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        return view('owner.menu-form');
    }
    
    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ko' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|integer|min:0',
            'emoji' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bg_class' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);
        
        $menu = new Menu();
        $menu->name = $request->name;
        $menu->name_ko = $request->name_ko;
        $menu->description = $request->description;
        $menu->category = $request->category;
        $menu->price = $request->price;
        $menu->emoji = $request->emoji ?? '🍽️';
        $menu->bg_class = $request->bg_class ?? 'bg-k1';
        $menu->is_available = $request->has('is_available');
        $menu->is_featured = $request->has('is_featured');
        
        // Upload gambar ke storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $originalName) . '.' . $extension;
            $path = $image->storeAs('menus', $filename, 'public');
            $menu->image = $path;
        }
        
        $menu->save();
        
        return redirect()
            ->route('owner.menus')
            ->with('success', 'Menu "' . $menu->name . '" berhasil ditambahkan!');
    }
    
    /**
     * Show the form for editing the specified menu.
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $menu = Menu::findOrFail($id);
        return view('owner.menu-form', compact('menu'));
    }
    
    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $menu = Menu::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ko' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|integer|min:0',
            'emoji' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bg_class' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);
        
        $menu->name = $request->name;
        $menu->name_ko = $request->name_ko;
        $menu->description = $request->description;
        $menu->category = $request->category;
        $menu->price = $request->price;
        $menu->emoji = $request->emoji ?? '🍽️';
        $menu->bg_class = $request->bg_class ?? 'bg-k1';
        $menu->is_available = $request->has('is_available');
        $menu->is_featured = $request->has('is_featured');
        
        // Hapus gambar jika diminta
        if ($request->remove_image == '1' && $menu->image) {
            if (Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->image = null;
        }
        
        // Upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $originalName) . '.' . $extension;
            $path = $image->storeAs('menus', $filename, 'public');
            $menu->image = $path;
        }
        
        $menu->save();
        
        return redirect()
            ->route('owner.menus')
            ->with('success', 'Menu "' . $menu->name . '" berhasil diperbarui!');
    }
    
    /**
     * Remove the specified menu from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $menu = Menu::findOrFail($id);
        $menuName = $menu->name;
        
        // Hapus file gambar
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        
        return redirect()
            ->route('owner.menus')
            ->with('success', 'Menu "' . $menuName . '" berhasil dihapus!');
    }
    
    /**
     * Sync images from public/images to storage
     */
    public function syncImages()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        // Get all images from public/images
        $publicImages = glob(public_path('images/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG}'), GLOB_BRACE);
        $synced = 0;
        
        foreach ($publicImages as $imagePath) {
            $filename = basename($imagePath);
            $storagePath = 'menus/' . $filename;
            
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->put($storagePath, file_get_contents($imagePath));
                $synced++;
            }
        }
        
        // Update database
        $menus = Menu::whereNotNull('image')->get();
        foreach ($menus as $menu) {
            $oldImage = $menu->image;
            $newImage = 'menus/' . basename($oldImage);
            
            if (Storage::disk('public')->exists($newImage) && $oldImage !== $newImage) {
                $menu->image = $newImage;
                $menu->save();
            }
        }
        
        return redirect()
            ->route('owner.menus')
            ->with('success', "Berhasil sync {$synced} gambar ke storage!");
    }
}