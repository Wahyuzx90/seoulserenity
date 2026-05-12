<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $staff = User::where('role', 'staff')->get();
        return view('owner.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->role = 'staff';
        $user->save();
        
        return redirect()->route('owner.staff')->with('success', 'Staff berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $user = User::findOrFail($id);
        if ($user->role === 'staff') {
            $user->delete();
        }
        
        return redirect()->route('owner.staff')->with('success', 'Staff berhasil dihapus!');
    }
}