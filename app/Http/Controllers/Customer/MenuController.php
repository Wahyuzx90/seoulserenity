<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function home()
    {
        return redirect()->route('customer.menu');
    }

    public function index(Request $request)
    {
        $query = Menu::where('is_available', true);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $categoryMap = [
                'sup'     => 'Sup & Jjigae',
                'bbq'     => 'BBQ & Grill',
                'nasi'    => 'Nasi & Bento',
                'mie'     => 'Mie',
                'minuman' => 'Minuman',
            ];
            $cat = $categoryMap[$request->kategori] ?? $request->kategori;
            $query->where('category', $cat);
        }

        $menus      = $query->orderBy('name')->get();
        $featured   = Menu::where('is_available', true)->where('is_featured', true)->get();
        $totalMenus = Menu::where('is_available', true)->count();

        return view('customer.menu', compact('menus','featured','totalMenus'));
    }
}
