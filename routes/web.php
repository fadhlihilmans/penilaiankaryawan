<?php

use Illuminate\Support\Facades\Route;


Route::get('/users/list', '\App\Livewire\Admin\User\UserList')->name('users.list');

Route::get('/jabatan/list', '\App\Livewire\Admin\Employee\PositionList')->name('jabatan.list');
Route::get('/karyawan/list', '\App\Livewire\Admin\Employee\EmployeeList')->name('karyawan.list');
Route::get('/pendidikan/list', '\App\Livewire\Admin\Employee\EducationList')->name('pendidikan.list');

Route::get('/evaluasi-kriteria/list', '\App\Livewire\Admin\Evaluation\CriteriaList')->name('evaluasi-kriteria.list');
Route::get('/evaluasi-kriteria/detail/{id}', '\App\Livewire\Admin\Evaluation\CriteriaDetail')->name('evaluasi-kriteria.detail');