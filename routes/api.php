<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController\LoginController;

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Recrutador\RecrutadorController;
use App\Http\Controllers\Api\Coordenador\CoordenadorController;
use App\Http\Controllers\Api\Supervisor\SupervisorController;
use App\Http\Controllers\Api\Cho\ChoController;
use App\Http\Controllers\Api\Assas\AssasController;
use App\Http\Controllers\Api\Motoboy\MotoboyController;
/*
|--------------------------------------------------------------------------
| API Routes

*/

//Altenticação
Route::post('/login',  [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/register',  [LoginController::class, 'store']);
Route::middleware('auth:sanctum')->get('/userlogado',  [LoginController::class, 'user']);
Route::middleware('auth:sanctum')->post('/logout',  [LoginController::class, 'logout']);
//fim

//rotas de admin
Route::middleware('auth:sanctum')->get('/adminusers',  [AdminController::class, 'index']);
Route::middleware('auth:sanctum')->get('/user',  [AdminController::class, 'show']);

Route::middleware('auth:sanctum')->post('/storemotoboy',  [AdminController::class, 'storemotoboy']);
Route::middleware('auth:sanctum')->post('/storesupervisor',  [AdminController::class, 'storesupervisor']);
Route::middleware('auth:sanctum')->post('/storerecrutador',  [AdminController::class, 'storerecrutador']);
Route::middleware('auth:sanctum')->post('/storecoordenador',  [AdminController::class, 'storecoordenador']);
Route::middleware('auth:sanctum')->post('/storecho',  [AdminController::class, 'storecho']);
Route::middleware('auth:sanctum')->get('/listartodososusers',  [AdminController::class, 'listartodososusers']);
Route::middleware('auth:sanctum')->get('/retornarnumregistrosparapagamento',  [AdminController::class, 'retornarquantosregistrotemempagamento']);

Route::middleware('auth:sanctum')->put('/edtiuser',  [AdminController::class, 'putuser']);

Route::middleware('auth:sanctum')->get('/listoptions',  [AdminController::class, 'indexoptions']);
Route::middleware('auth:sanctum')->post('/recrutadoruser',  [RecrutadorController::class, 'store']);
Route::middleware('auth:sanctum')->post('/coordenadoruser',  [CoordenadorController::class, 'store']);
Route::middleware('auth:sanctum')->get('/saldoruser',  [AdminController::class, 'saldo']);
//

//rotas de coordenador
Route::middleware('auth:sanctum')->get('/coordenadorusers',  [CoordenadorController::class, 'index']);
//

//rotas de recrutador
Route::middleware('auth:sanctum')->get('/recrutadorusers',  [RecrutadorController::class, 'index']);

//

//rotas de supervisor
Route::middleware('auth:sanctum')->get('/supervisorusers',  [SupervisorController::class, 'index']);
//

//rotas de CHO
Route::middleware('auth:sanctum')->get('/chousers',  [ChoController::class, 'index']);
//

//rotas de motoboy
Route::middleware('auth:sanctum')->get('/termsandservices',  [MotoboyController::class, 'index']);
Route::middleware('auth:sanctum')->put('/termsandservices',  [MotoboyController::class, 'altetermsandservices']);

Route::middleware('auth:sanctum')->get('/dateChecked',  [MotoboyController::class, 'indexdateChecked']);
Route::post('/dateChecked',  [MotoboyController::class, 'dateChecked']);
Route::post('/photodateChecked',  [MotoboyController::class, 'photodateChecked']);


//

//rotas de asssas
Route::middleware('auth:sanctum')->get('/pagamento',  [AssasController::class, 'listpay']);
Route::middleware('auth:sanctum')->post('/pagamento',  [AssasController::class, 'pay']);
Route::middleware('auth:sanctum')->post('/cadcliente',  [AssasController::class, 'cadcliente']);
Route::middleware('auth:sanctum')->get('/pagamentoid',  [AssasController::class, 'listpayid']);
Route::middleware('auth:sanctum')->get('/cliente',  [AssasController::class, 'cliente']);
Route::middleware('auth:sanctum')->get('/custumermy',  [AssasController::class, 'custumerporiddomeubanco']);
Route::middleware('auth:sanctum')->get('/pagamentomotoboy',  [AssasController::class, 'listpaymotoboy']);
//

//rotas de pagamento
Route::middleware('auth:sanctum')->post('/confirmpagamentomotoboy',  [AdminController::class, 'confirmarpagamentomotoboy']);
Route::middleware('auth:sanctum')->get('/hierarquiamotoboy',  [AdminController::class, 'pegarhierarquiadomotoboy']);
//


