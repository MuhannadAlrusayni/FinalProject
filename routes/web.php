<?php

use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

//ImportExcelController
Route::post('/excel/import',[ImportExcelController::class,'import'])->name('importExcel');
Route::get('/excel/add',[ImportExcelController::class,'add'])->name('AddExcelForm');

//UserController
// Route::get('/users',[UserController::class,'index'])->name('ShowAllUsers');
// Route::get('/user/{user}',[UserController::class,'show'])->name('ShowOneUser');

//Auth::routes();
Auth::routes(['register'=> false]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
