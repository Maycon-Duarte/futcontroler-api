<?php

use App\Http\Controllers\ApiControllerCampeonatos;
use App\Http\Controllers\ApiControllerEscudos;
use App\Http\Controllers\ApiControllerPartidas;
use App\Http\Controllers\ApiControllerytbpartida;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('campeonatos', [ApiControllerCampeonatos::class, 'getAll'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('campeonatos/{id}', [ApiControllerCampeonatos::class, 'get'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('campeonatos/atu', [ApiControllerCampeonatos::class, 'getAllAtu'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::post('campeonatos/reset/{id}', [ApiControllerCampeonatos::class, 'reset'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::post('campeonatos', [ApiControllerCampeonatos::class, 'create'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::put('campeonatos/{id}', [ApiControllerCampeonatos::class, 'update'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::delete('campeonatos/{id}',[ApiControllerCampeonatos::class, 'delete'])->middleware(['auth:sanctum', 'ability:api:write']);

Route::get('partidas', [ApiControllerPartidas::class, 'getAll'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('partidas/{token}', [ApiControllerPartidas::class, 'get'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('partidas/campeonato/{id}', [ApiControllerPartidas::class, 'getByCampeonato'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::post('partidas', [ApiControllerPartidas::class, 'create'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::put('partidas/{token}', [ApiControllerPartidas::class, 'update'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::delete('partidas/{token}', [ApiControllerPartidas::class, 'delete'])->middleware(['auth:sanctum', 'ability:api:write']);
Route::get('partidas', [ApiControllerPartidas::class, 'getAll'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('partidas/{token}', [ApiControllerPartidas::class, 'get'])->middleware(['auth:sanctum', 'ability:api:read']);

Route::post('ytbpartidas', [ApiControllerytbpartida::class, 'create'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::put('ytbpartidas/{ytb_id}', [ApiControllerytbpartida::class, 'update'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('ytbpartidas', [ApiControllerytbpartida::class, 'getAll'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('ytbpartidas/{ytb_id}', [ApiControllerytbpartida::class, 'get'])->middleware(['auth:sanctum', 'ability:api:read']);

Route::post('escudos/{id}', [ApiControllerEscudos::class, 'update'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('escudos/', [ApiControllerEscudos::class, 'getAll'])->middleware(['auth:sanctum', 'ability:api:read']);
Route::get('escudos/{id}', [ApiControllerEscudos::class, 'get'])->middleware(['auth:sanctum', 'ability:api:read']);