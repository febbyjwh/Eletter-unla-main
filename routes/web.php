<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\LoginUnit;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\RegisterUnit;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UnitGoogleController;

use App\Livewire\Profile\Profile;
use App\Livewire\UserManagement\UserManagement;
use App\Livewire\RoleManagement\RoleManagement;
use App\Livewire\UnitManagement\UnitManagement;
use App\Livewire\SuratMasuk\SuratMasukManagement;
use App\Livewire\SuratKeluar\SuratKeluarManagement;
use App\Livewire\Disposisi\DisposisiManagement;
use App\Livewire\TamplateSurat\TamplateSuratManagement;

use App\Livewire\Arsip\ArsipAdmin;
use App\Livewire\Arsip\ArsipUser;
use App\Livewire\Laporan\LaporanManagement;

Route::get('/', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/login-unit', LoginUnit::class)->name('login-unit');
Route::get('/register-unit', RegisterUnit::class)->name('register-unit');
// Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
// Route::get('/auth/google/{role}',[GoogleController::class, 'redirect'])->name('google.redirect');

Route::get('/auth/google/login', [GoogleController::class, 'redirectLogin'])->name('google.login');
Route::get('/auth/google/register', [GoogleController::class, 'redirectRegister'])->name('google.register');
Route::get('/auth/google/unit', [GoogleController::class, 'redirectUnit'])->name('google.unit');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{email}', ResetPassword::class)->name('password.reset');

Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})
    ->name('logout');

// waiting approval page
Route::middleware('auth')->group(function () {
    Route::view('/waiting-approval', 'auth.waiting-approval')
        ->name('waiting.approval');
});

// user eb
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile.show');

    Route::get('/arsip/admin', ArsipAdmin::class)->name('arsip.admin');
    Route::get('/arsip/user', ArsipUser::class)->name('arsip.user');

    Route::get('/manajemen-user', UserManagement::class)->name('manajemen-user');
    Route::get('/manajemen-role', RoleManagement::class)->name('manajemen-role');
    Route::get('/manajemen-unit', UnitManagement::class)->name('manajemen-unit');
    Route::get('/manajemen-suratmasuk', SuratMasukManagement::class)->name('manajemen-suratmasuk');
    Route::get('/manajemen-suratkeluar', SuratKeluarManagement::class)->name('manajemen-suratkeluar');
    Route::get('/surat-masuk/{id}/disposisi', DisposisiManagement::class)->name('disposisi.management');
    Route::get('/disposisi/{suratMasukId}', DisposisiManagement::class)
        ->name('disposisi.management');
    Route::get('/laporan', LaporanManagement::class)->name('laporan.index');

    Route::get('/template-surat', TamplateSuratManagement::class)->name('template-surat.index');

    Route::post('/upload-drive', [GoogleController::class, 'uploadToDrive']);
});
