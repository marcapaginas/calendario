<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalenderController;

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

Route::get('/', [FullCalenderController::class, 'index'])->name('index');
Route::get('/eventos', [FullCalenderController::class, 'listarEventos']);
Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);
Route::get('/calcular', [FullCalenderController::class, 'calcular'])->name('calcular');

Route::get('/pruebamodal', function () {
    return "hola pruebamodal";
});

Route::get('/crearEvento/{comienzo?}/{fin?}/{todoElDia?}', [FullCalenderController::class, 'crearEvento'])->name('crearEvento');
Route::get('/actualizarEvento/{comienzo?}/{fin?}/{todoElDia?}', [FullCalenderController::class, 'actualizarEvento'])->name('actualizarEvento');

Route::resource('evento', App\Http\Controllers\EventoController::class)->only('index', 'update', 'store');

Route::resource('persona', App\Http\Controllers\PersonaController::class)->only('index', 'update', 'store');
