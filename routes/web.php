<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/admin/login', \App\Livewire\Admin\Auth\Login::class)->name('login')->middleware('guest');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/users/list', \App\Livewire\Admin\User\UserList::class)->name('users.list');

    Route::get('/jabatan/list', \App\Livewire\Admin\Employee\PositionList::class)->name('jabatan.list');
    Route::get('/karyawan/list', \App\Livewire\Admin\Employee\EmployeeList::class)->name('karyawan.list');
    Route::get('/pendidikan/list', \App\Livewire\Admin\Employee\EducationList::class)->name('pendidikan.list');

    Route::get('/evaluasi-kriteria/list', \App\Livewire\Admin\Evaluation\CriteriaList::class)->name('evaluasi-kriteria.list');
    Route::get('/evaluasi-kriteria/detail/{id}', \App\Livewire\Admin\Evaluation\CriteriaDetail::class)->name('evaluasi-kriteria.detail');
    Route::get('/evaluasi-kriteria/nilai', \App\Livewire\Admin\Evaluation\ScoreList::class)->name('evaluasi-kriteria.nilai');
});