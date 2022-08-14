<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\VoucherController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
/*     return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]); */
});
Route::get('/test', function () {
    $user = Auth::user();
    $roles = Role::whereHas('role_admins',function($query)use($user){
        $query->where('user_id',$user->id);
    })->paginate(5);
   return $roles;
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('/create_voucher', [VoucherController::class,'create_voucher'])->name('create.voucher');
    Route::get('/get_voucher', [VoucherController::class,'get_voucher'])->name('get.voucher');

    Route::group(['middleware' => ['role_or_permission:super-admin|moderate_group']], function () {
        Route::get('/get_group', [GroupController::class,'get_group'])->name('get.group');
        Route::get('/get_groupuser', [GroupController::class,'get_groupuser'])->name('get.get_groupuser');
        Route::post('/remove_user_group', [GroupController::class,'remove_user_group'])->name('get.remove_user_group');
    });




});
