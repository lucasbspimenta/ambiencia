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
