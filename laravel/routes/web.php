<?php

use App\Http\Controllers\AgendamentoTipoController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\IntegracaoController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ImagemController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\GuiaController as AdmGuiaController;

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

Route::middleware(['web', 'auth.caixa'])->group(function () {

    Route::get('/', function () {
        return view('index');
    })->name('index');

    Route::resource('/agenda', AgendamentoController::class)->names(['index' => 'agenda']);
    Route::resource('/guias', GuiaController::class);
    Route::resource('/checklist', ChecklistController::class);

    Route::prefix('administracao')->name('adm.')->middleware(['web', 'auth.caixa','admin'])->group(function () {

        Route::resource('/tipodeagendamento', AgendamentoTipoController::class)->names(['index' => 'tipodeagendamento']);
        Route::resource('/checklist', ChecklistItemController::class);
        Route::get('/guia', [GuiaController::class, 'indexadm'])->name('guia.indexadm');

        Route::resource('/integracao', IntegracaoController::class);
    });
});



