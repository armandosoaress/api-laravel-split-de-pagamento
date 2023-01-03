<?php

use App\Http\Controllers\{
    RecrutadorController,
    ValidacaoController
};
use Illuminate\Support\Facades\Route;

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

Route::get('/qq', function () {
    return "111";
});

Route::get('/bb', function () {
    return view('welcome');
});


Route::get('/jj', function () {
    return "1111";
    });



Route::get("/obj-youpop", [ValidacaoController::class, 'obj'])->name("youpop.obj");

Route::post("/recrutador/cadastro", [RecrutadorController::class, 'cadastro'])->name("recrutador.cadastro");
