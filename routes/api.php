<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return ["response json" => 'Rota inválida'];
});

Route::post('login', [AuthController::class,'login']);
Route::post('register', [UserController::class,'register']);

Route::prefix("v1")->middleware('jwt.auth')->group(function(){
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::apiResource('task', 'App\Http\Controllers\TaskController');
    Route::apiResource('status', 'App\Http\Controllers\StatusController');

});
