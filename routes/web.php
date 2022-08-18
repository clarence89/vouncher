<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\VoucherController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Inertia\Inertia;


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
    Route::post('/delete_voucher', [VoucherController::class,'delete_voucher'])->name('delete.voucher');

    Route::group(['middleware' => ['role_or_permission:super-admin|moderate_group']], function () {
        Route::get('/get_group', [GroupController::class,'get_group'])->name('get.group');
        Route::get('/get_user', [GroupController::class,'get_user'])->name('get.get_user');
        Route::get('/get_user_moderator/{group_name}', [GroupController::class,'get_user_moderator'])->name('get.get_user_moderator');
        Route::patch('/patch_users', [GroupController::class,'patch_users'])->name('patch.users');
        Route::get('/get_groupuser', [GroupController::class,'get_groupuser'])->name('get.get_groupuser');
        Route::get('/get_usercodes/{user_id}', [GroupController::class,'get_usercodes'])->name('get.usercodes');
        Route::post('/remove_user_group', [GroupController::class,'remove_user_group'])->name('get.remove_user_group');
        Route::post('/generate_group_excel', [GroupController::class,'generate_group_excel'])->name('generate.group');
    });


    Route::group(['middleware' => ['role:super-admin']], function () {
        Route::post('/create_group', [AdminController::class,'create_group'])->name('admin.create_group');
        Route::get('/get_user/{type}', [AdminController::class,'getUsers'])->name('admin.get_user');
        Route::get('/admin_usermoderated/{user_id}', [AdminController::class,'admin_usermoderated'])->name('admin.usermoderated');
        Route::post('/generate_all_excel', [AdminController::class,'generate_all_excel'])->name('admin.generate_users');
        Route::patch('/update_group', [AdminController::class,'update_group'])->name('admin.update_group');
        Route::post('/delete_group', [AdminController::class,'delete_group'])->name('admin.delete_group');
    });


});
