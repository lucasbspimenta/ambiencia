<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AgendamentoTipoController;
use App\Http\Controllers\API\AgendamentoAPIController;
use App\Http\Controllers\API\AgendamentoTipoAPIController;
use App\Http\Controllers\API\ChecklistAPIController;
use App\Http\Controllers\API\DemandaAPIController;
use App\Http\Controllers\API\GuiaAPIController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\DemandaController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\ImagemController;
use App\Http\Controllers\IntegracaoController;
use App\Http\Controllers\PainelController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::middleware(['chrome','web', 'auth.caixa'])->group(function () {

    Route::get('/', [PainelController::class, 'index'])->name('index');

    Route::resource('/agenda', AgendamentoController::class)->names(['index' => 'agenda']);
    Route::resource('/guias', GuiaController::class);
    Route::resource('/checklist', ChecklistController::class);
    Route::get('/checklist-imprimir', [ChecklistController::class, 'imprimir'])->name('checklist-imprimir');
    Route::resource('/demandas', DemandaController::class);
    Route::resource('/imagem', ImagemController::class);

    Route::prefix('api')->name('api.')->middleware(['web', 'auth.caixa'])->group(function () {

        Route::get('/unidades', function () {
            return response()->json(Unidade::all()->toArray());
        });

        Route::apiResource('guias', GuiaAPIController::class);
        Route::apiResource('tiposagendamentos', AgendamentoTipoAPIController::class);
        Route::apiResource('checklists', ChecklistAPIController::class);

        Route::apiResource('agendamentos', AgendamentoAPIController::class);
        Route::get('agendamentos/tipo/{tipo}', [AgendamentoAPIController::class, 'indexPorTipo'])->name('agendamentostipo');
        Route::post('/agendamento/atualizar', [AgendamentoController::class, 'update'])->name('agendamento_update');

        Route::get('demandas/tratar', [DemandaAPIController::class, 'tratar'])->name('demandastratar');
        Route::get('demandas/{finalizados?}', [DemandaAPIController::class, 'index'])->name('demandasapi');

    });

    Route::prefix('administracao')->name('adm.')->middleware(['admin'])->group(function () {

        Route::resource('/tipodeagendamento', AgendamentoTipoController::class)->names(['index' => 'tipodeagendamento']);
        Route::resource('/checklist', ChecklistItemController::class);
        Route::get('/guia', [GuiaController::class, 'indexadm'])->name('guia.indexadm');

        Route::resource('/integracao', IntegracaoController::class);

        Route::get('/simulausuario/{matricula?}', function ($matricula = 'limpar') {

            if ($matricula && strtoupper(trim(Auth::user()->equipe->nome)) == 'SISTEMAS') {

                Auth::user()->simulando = strtoupper($matricula);
                Auth::user()->save();

                return redirect()->route('index');
            }

            return redirect()->route('index');
        })->name('simulausuario');
    });

    Route::get('/limpasimulacao', function () {

        $usuario_simulador = User::where('matricula', Auth::user()->usuario_simulador)->first();
        $usuario_simulador->simulando = null;
        $usuario_simulador->save();

        Auth::user()->simulando = null;
        Auth::user()->is_simulado = false;
        Auth::user()->usuario_simulador = null;

        return redirect()->route('index');

    })->name('limpasimulacao');

    Route::get('/otimizar', function () {
        dump(Artisan::call('optimize:clear'));
    });

});
