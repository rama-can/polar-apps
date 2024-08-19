<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\UsageLogbookController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\WorkInstructionController;
use App\Http\Controllers\Admin\CalibrationLogbookController;

Route::get('/', function () {
    return redirect()->route('product-category.index');
    // return view('frontend.welcome');
});
Route::get('qrcode/scanner/{product}', [QrCodeController::class, 'scan'])->name('qrcode.scan');

// Frontend
Route::namespace('App\Http\Controllers\Frontend')->group(function () {
    Route::get('/peralatan-laboratorium/lists', 'ProductController@index')->name('products.index');
    Route::get('/peralatan-laboratorium/{category}/{product}', 'ProductController@detail')->name('products.detail');
    Route::get('/peralatan-laboratorium/categories', 'ProductCategoryController@index')->name('product-category.index');
    Route::get('/peralatan-laboratorium/{category}', 'ProductCategoryController@detail')->name('product-category.detail');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile', 'ProfileController@update')->name('profile.update');

    Route::middleware(['auth', 'isActive'])->group(function() {
        Route::prefix('{product}')->group(function() {

            Route::resource('work-instructions', 'WorkInstructionController')->names('work-instructions');
            Route::resource('usage-logbooks', 'UsageLogbookController')->names('usage-logbooks');
            Route::resource('calibration-logbooks', 'CalibrationLogbookController')->names('calibration-logbooks');
        });
    });
});


Auth::routes();
// Admin
Route::middleware('auth', 'isActive')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/exports', [ExportController::class, 'index'])->name('export.index');
        Route::post('exports/usage-logbook', [ExportController::class, 'usageLogbook'])->name('export.usage-logbook');
        Route::post('exports/calibration-logbook', [ExportController::class, 'calibrationLogbook'])->name('export.calibration-logbook');
        Route::get('/application-settings', [SettingController::class, 'index'])->name('setting');
        Route::post('/application-settings', [SettingController::class, 'update'])->name('setting.update');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('roles', RoleController::class);
        Route::resource('navigations', NavigationController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);

        Route::resource('products', ProductController::class)->except('show');
        Route::get('/qrcode/generate/{product}', [QrCodeController::class, 'generate'])->name('qrcode.generate');
        Route::get('/qrcode/download/{product}', [QrCodeController::class, 'download'])->name('qrcode.download');

        Route::resource('product-images', ProductImageController::class)->except('show');
        Route::resource('product-categories', ProductCategoryController::class)->except('show');
        Route::get('/import', [ImportController::class, '__invoke'])->name('import');

        Route::resource('{product}/usage-logbooks', UsageLogbookController::class)->except('show');
        Route::resource('{product}/calibration-logbooks', CalibrationLogbookController::class)->except('show');
        Route::resource('{product}/work-instructions', WorkInstructionController::class)->except('show');
    });

Route::middleware('guest')
    ->group(function () {
        Route::get('admin/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
        Route::post('admin/login', [AdminLoginController::class, 'adminLogin']);
    });
