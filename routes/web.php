<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Models\Content;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Situs publik
|--------------------------------------------------------------------------
*/
Route::middleware('visit')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/berita', [ArticleController::class, 'index'])->name('berita.index');
    Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('berita.show');
    Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('halaman.show');
    Route::get('/profil', [PageController::class, 'profil'])->name('profil');
    Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
    Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
});

// Asisten virtual (AJAX dari widget)
Route::post('/chat', ChatController::class)->middleware('throttle:chat')->name('chat');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Panel admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');

    // Berita
    Route::resource('artikel', Admin\ArticleController::class)
        ->parameters(['artikel' => 'article'])->except('show');

    Route::get('/berita-utama', [Admin\FeaturedController::class, 'edit'])->name('berita-utama');
    Route::post('/berita-utama', [Admin\FeaturedController::class, 'update'])->name('berita-utama.update');

    // Referensi
    Route::get('/kategori', [Admin\CategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [Admin\CategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [Admin\CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [Admin\CategoryController::class, 'destroy'])->name('kategori.destroy');

    // Konten: dokumen, halaman, pengumuman, slider, sumber
    Route::prefix('konten/{jenis}')->whereIn('jenis', array_keys(Content::JENIS))->group(function () {
        Route::get('/', [Admin\ContentController::class, 'index'])->name('konten.index');
        Route::post('/', [Admin\ContentController::class, 'store'])->name('konten.store');
        Route::put('/{content}', [Admin\ContentController::class, 'update'])->name('konten.update');
        Route::delete('/{content}', [Admin\ContentController::class, 'destroy'])->name('konten.destroy');
    });

    // Menu Manager: utama, footer, layanan
    Route::prefix('menu/{jenis}')->whereIn('jenis', array_keys(Menu::JENIS))->group(function () {
        Route::get('/', [Admin\MenuController::class, 'index'])->name('menu.index');
        Route::post('/', [Admin\MenuController::class, 'store'])->name('menu.store');
        Route::post('/reorder', [Admin\MenuController::class, 'reorder'])->name('menu.reorder');
        Route::put('/{menu}', [Admin\MenuController::class, 'update'])->name('menu.update');
        Route::delete('/{menu}', [Admin\MenuController::class, 'destroy'])->name('menu.destroy');
    });

    // Khusus admin
    Route::middleware('admin')->group(function () {
        Route::get('/pengaturan', [Admin\SettingController::class, 'edit'])->name('pengaturan');
        Route::post('/pengaturan', [Admin\SettingController::class, 'update'])->name('pengaturan.update');

        Route::resource('pengguna', Admin\UserController::class)
            ->parameters(['pengguna' => 'user'])->except('show');
    });
});
