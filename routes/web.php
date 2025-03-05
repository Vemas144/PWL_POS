<?php

use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;

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

Route::get('/level', [LevelController::class, 'index']);
route::get('/kategori', [KategoriController::class, "index"]);
route::get('/user', [UserController::class, "index"]);
route::get('/user/tambah', [UserController::class, "tambah"]);
route::post('/user/tambah_simpan', [UserController::class, "tambah_simpan"]);
route::get('/user/ubah/{id}', [UserController::class, "ubah"]);
route::put('/user/ubah_simpan/{id}', [UserController::class, "ubah_simpan"]);
route::get('/user/hapus/{id}', [UserController::class, "hapus"]);