<?php

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== ROOT REDIRECT ====================
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'owner' || $user->role === 'staff') {
            return redirect('/staff/orders');
        }
        return redirect('/customer/home');
    }
    return redirect('/login');
});

// ==================== AUTHENTICATION ROUTES ====================

// Customer Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Staff Auth
Route::middleware('guest')->group(function () {
    Route::get('/staff/login', [AuthController::class, 'showStaffLogin'])->name('staff.login');
    Route::post('/staff/login', [AuthController::class, 'staffLogin'])->name('staff.login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== CUSTOMER ROUTES ====================
Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {
    
    Route::get('/home', function () {
        return view('customer.home');
    })->name('home');
    
    Route::get('/menu', function () {
        $query = Menu::where('is_available', true);
        
        if (request('kategori')) {
            $query->where('category', request('kategori'));
        }
        
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ko', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $menus = $query->orderBy('name')->get();
        
        return view('customer.menu', compact('menus'));
    })->name('menu');
    
    Route::get('/orders', function () {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('customer.orders', compact('orders'));
    })->name('orders');
    
    Route::get('/about', function () {
        return view('customer.about');
    })->name('about');
    
    Route::get('/checkout', function () {
        return view('customer.checkout');
    })->name('checkout');
    
    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('profile');
    
    Route::post('/profile/update', function () {
        $user = Auth::user();
        if ($user) {
            $field = request('field');
            $value = request('value');
            if ($field && $value && in_array($field, ['name', 'email', 'phone'])) {
                $user->$field = $value;
                $user->save();
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false]);
    })->name('profile.update');
    
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
});

// ==================== STAFF ROUTES ====================
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', fn() => redirect()->route('staff.orders'))->name('home');
    Route::get('/dashboard', [StaffOrderController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [StaffOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [StaffOrderController::class, 'update'])->name('orders.update');
    Route::get('/history', [StaffOrderController::class, 'history'])->name('history');
});

// ==================== OWNER ROUTES ====================
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', fn() => redirect()->route('owner.reports'))->name('home');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
    Route::get('/export', [ReportController::class, 'export'])->name('export');
    Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    Route::resource('/menus', OwnerMenuController::class)->names([
        'index' => 'menus',
        'create' => 'menus.create',
        'store' => 'menus.store',
        'edit' => 'menus.edit',
        'update' => 'menus.update',
        'destroy' => 'menus.destroy',
    ]);
    Route::get('/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

// ==================== ORDER API ROUTES ====================

// Konfirmasi pembayaran (QRIS/COD)
Route::post('/order/confirm/{orderNumber}', function ($orderNumber) {
    \Log::info('Confirm payment: ' . $orderNumber);
    
    try {
        $order = Order::where('order_number', $orderNumber)->first();
        if ($order) {
            $order->status = 'process';
            $order->save();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi']);
        }
        return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
})->name('order.confirm');

// Upload bukti transfer
Route::post('/order/upload-proof', function (Request $request) {
    \Log::info('Upload proof for order: ' . $request->order_number);
    
    try {
        $order = Order::where('order_number', $request->order_number)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
        }
        
        if (!$request->hasFile('proof_image')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada file yang diupload'], 400);
        }
        
        $file = $request->file('proof_image');
        
        $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $validTypes)) {
            return response()->json(['success' => false, 'message' => 'Format file harus JPG atau PNG'], 400);
        }
        
        if ($file->getSize() > 5 * 1024 * 1024) {
            return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
        }
        
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('proofs', $filename, 'public');
        
        $order->proof_image = $path;
        $order->status = 'process';
        $order->save();
        
        \Log::info('Proof uploaded: ' . $path);
        
        return response()->json(['success' => true, 'message' => 'Bukti transfer berhasil diupload']);
        
    } catch (\Exception $e) {
        \Log::error('Upload proof error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
})->name('order.upload');

// Batalkan pesanan
Route::post('/order/cancel/{orderNumber}', function ($orderNumber) {
    $order = Order::where('order_number', $orderNumber)->first();
    if ($order) {
        $order->status = 'cancelled';
        $order->save();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
})->name('order.cancel');

// Hapus pesanan
Route::delete('/order/delete/{orderNumber}', function ($orderNumber) {
    $order = Order::where('order_number', $orderNumber)->first();
    if ($order) {
        $order->delete();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
})->name('order.delete');

// Pesan Lagi
Route::post('/order/again/{orderNumber}', function ($orderNumber) {
    $order = Order::where('order_number', $orderNumber)->with('items')->first();
    if ($order) {
        $cart = [];
        foreach ($order->items as $item) {
            $cart[$item->menu_id] = [
                'id' => $item->menu_id,
                'name' => $item->menu_name,
                'price' => $item->price,
                'emoji' => $item->menu_emoji,
                'image' => $item->menu_image,
                'qty' => $item->quantity
            ];
        }
        return response()->json(['success' => true, 'cart' => $cart]);
    }
    return response()->json(['success' => false]);
})->name('order.again');

// Detail pesanan
Route::get('/order/details/{orderNumber}', function ($orderNumber) {
    $order = Order::where('order_number', $orderNumber)->with('items')->first();
    if ($order) {
        $cart = [];
        foreach ($order->items as $item) {
            $cart[$item->menu_id] = [
                'id' => $item->menu_id,
                'name' => $item->menu_name,
                'price' => $item->price,
                'emoji' => $item->menu_emoji,
                'image' => $item->menu_image,
                'qty' => $item->quantity
            ];
        }
        return response()->json(['success' => true, 'cart' => $cart]);
    }
    return response()->json(['success' => false]);
})->name('order.details');