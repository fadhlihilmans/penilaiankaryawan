<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    if (Auth::check()) {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('penilai')){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.user.profile');
        }
    }
    return redirect()->route('login');
});

Route::get('/admin/login', \App\Livewire\Admin\Auth\Login::class)
    ->name('login')->middleware('guest');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', \App\Livewire\Admin\Dashboard\Dashboard::class)
        ->name('dashboard')
        ->middleware(RoleMiddleware::class . ':admin,penilai');

    Route::get('/evaluasi-kriteria/nilai', \App\Livewire\Admin\Evaluation\ScoreList::class)
        ->name('evaluasi-kriteria.nilai')
        ->middleware(RoleMiddleware::class . ':admin,penilai');

    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('/user/list', \App\Livewire\Admin\User\UserList::class)->name('user.list');
        Route::get('/jabatan/list', \App\Livewire\Admin\Employee\PositionList::class)->name('jabatan.list');
        Route::get('/karyawan/list', \App\Livewire\Admin\Employee\EmployeeList::class)->name('karyawan.list');
        Route::get('/pendidikan/list', \App\Livewire\Admin\Employee\EducationList::class)->name('pendidikan.list');
        Route::get('/evaluasi-kriteria/list', \App\Livewire\Admin\Evaluation\CriteriaList::class)->name('evaluasi-kriteria.list');
        Route::get('/evaluasi-kriteria/detail/{id}', \App\Livewire\Admin\Evaluation\CriteriaDetail::class)->name('evaluasi-kriteria.detail');
    });

    Route::get('/evaluasi-kriteria/nilai-karyawan', \App\Livewire\Admin\Evaluation\EmployeeScoreList::class)->name('evaluasi-kriteria.nilai-karyawan');
    Route::get('/user/profile', \App\Livewire\Admin\User\Profile::class)->name('user.profile');
    
});
