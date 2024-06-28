<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CentrePointController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Pengecualian halaman tidak perlu login Map route 
Route::get('/', [MapController::class, 'index'])->name('map');
Route::get('/lokasi/{id}/detail', [LokasiController::class, 'showDetail'])->name('lokasi.detail');

// Lokasi routes with middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::resource('lokasi', LokasiController::class);
    Route::get('/lokasis', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::get('/lokasis/data', [DataController::class, 'lokasi'])->name('data-lokasi');
    Route::post('/lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
    Route::get('/lokasi/{id}/pesanan', [LokasiController::class, 'pesananLokasi'])->name('lokasi.pesananlokasi');
});

// CentrePoint routes with middleware 'auth'
Route::resource('centre-point', CentrePointController::class)->middleware('auth');

// Rute untuk peran 'komentar' dengan pembatasan akses
Route::middleware('auth')->group(function () {
    // Akses terbatas untuk create dan store komentar bagi semua pengguna
    Route::get('/komentars/create', [KomentarController::class, 'create'])->name('komentars.create');
    Route::post('/komentars', [KomentarController::class, 'store'])->name('komentars.store');
});

Route::middleware(['auth', 'checkrole:adminpusat,mitra'])->group(function () {
    // Akses penuh untuk adminpusat dan mitra
    Route::get('/komentars', [KomentarController::class, 'index'])->name('komentars.index');
    Route::get('/komentars/{id}/edit', [KomentarController::class, 'edit'])->name('komentars.edit');
    Route::put('/komentars/{id}', [KomentarController::class, 'update'])->name('komentars.update');
    Route::delete('/komentars/{id}', [KomentarController::class, 'destroy'])->name('komentars.destroy');
});

// Pesanan routes with middleware 'auth'
Route::resource('pesanans', PesananController::class)->middleware('auth');

// Route for updating pesanan status
Route::put('/pesanans/{id}/updateStatus', [PesananController::class, 'updateStatus'])->name('pesanans.updateStatus')->middleware('auth');

// Route for showing pesanan detail
Route::get('/pesanans/{id}/detail', [PesananController::class, 'showDetail'])->name('pesanans.detail')->middleware('auth');

// Routes untuk role berbeda
Route::get('admin', function () {
    return view('admin');
})->middleware('checkRole:admin');

Route::get('mitra', function () {
    return view('mitra');
})->middleware(['checkRole:mitra,admin']);

Route::get('pengunjung', function () {
    return view('pengunjung');
})->middleware(['checkRole:pengunjung,admin']);

// Akses untuk hanya admin dan mitra
Route::group(['middleware' => ['auth', 'checkrole:adminpusat,mitra']], function () {
    Route::resource('lokasi', LokasiController::class);
});

// Akses untuk hanya admin
Route::group(['middleware' => ['auth', 'checkrole:adminpusat']], function () {
    Route::resource('users', UserController::class);
});
