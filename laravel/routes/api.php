<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\API\AgendamentoAPIController;
use App\Http\Controllers\API\ChecklistAPIController;
use App\Http\Controllers\API\GuiaAPIController;
use App\Http\Resources\AgendamentoResource;
use App\Http\Controllers\API\AgendamentoTipoAPIController;
use App\Models\Agendamento;
use App\Models\Unidade;
use App\Services\AgendamentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::name('api.')->middleware('auth.caixa')->group(function () {

    Route::get('/unidades', function () {
        return response()->json(Unidade::all()->toArray());
    });

    Route::apiResource('guias', GuiaAPIController::class);
    Route::apiResource('tiposagendamentos', AgendamentoTipoAPIController::class);
    Route::apiResource('checklists', ChecklistAPIController::class);

    Route::apiResource('agendamentos', AgendamentoAPIController::class);
    Route::get('agendamentos/tipo/{tipo}', [AgendamentoAPIController::class, 'indexPorTipo'])->name('agendamentostipo');

    Route::post('/agendamento/atualizar', [AgendamentoController::class, 'update'])->name('agendamento_update');
});
