<?php

use App\Http\Resources\AgendamentoResource;
use App\Models\Agendamento;
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

    Route::get('/agendamentos/{tipo}', function ($tipo) {
        return AgendamentoResource::collection(Agendamento::with(['unidade', 'tipo'])->get()->where('tipo.id', $tipo)->keyBy->id);
    })->name('agendamentostipo');
});
