<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:agent'])->group(function(){
    Route::get('/agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');
});
Route::get('/dashboard', function () {
        $url = "dashboard";

        if (request()->user()->role == "admin") {
            $url = "admin/dashboard";
        } else if(request()->user()->role == "agent"){
            $url = "agent/dashboard";
        }else{
            return view('dashboard');
        }
        return redirect()->intended($url);
   
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkRole:admin,agent'])->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->only(['index', 'create', 'store']);
});


Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'create', 'store']);
});


require __DIR__.'/auth.php';
