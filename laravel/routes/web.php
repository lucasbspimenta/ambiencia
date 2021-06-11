<?php

use App\Http\Controllers\AgendamentoTipoController;
use App\Http\Controllers\API\AgendamentoAPIController;
use App\Http\Controllers\API\AgendamentoTipoAPIController;
use App\Http\Controllers\API\ChecklistAPIController;
use App\Http\Controllers\API\GuiaAPIController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\IntegracaoController;
use App\Models\User;
use App\Services\RelatoriosService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ImagemController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\GuiaController as AdmGuiaController;
use Illuminate\Support\Facades\Session;

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
    Route::resource('/imagem', ImagemController::class);

    Route::prefix('api')->name('api.')->middleware(['web','auth.caixa'])->group(function () {

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


    Route::prefix('administracao')->name('adm.')->middleware(['admin'])->group(function () {

        Route::resource('/tipodeagendamento', AgendamentoTipoController::class)->names(['index' => 'tipodeagendamento']);
        Route::resource('/checklist', ChecklistItemController::class);
        Route::get('/guia', [GuiaController::class, 'indexadm'])->name('guia.indexadm');

        Route::resource('/integracao', IntegracaoController::class);

        Route::get('/simulausuario/{matricula?}', function ($matricula='limpar') {

            if($matricula && strtoupper(trim(Auth::user()->equipe->nome)) == 'SISTEMAS') {

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

    Route::get('/test', function () {
        dump(RelatoriosService::VisitaPorPeriodoCoordenador('C132747'));
    });


});



