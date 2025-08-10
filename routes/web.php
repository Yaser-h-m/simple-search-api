<?php

use App\Models\Content;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/hello', function () {
    return Content::all();
});
