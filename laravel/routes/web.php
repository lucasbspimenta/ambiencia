<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgendaController;
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

    Route::post('/upload-image', [ImagemController::class, 'dropZone' ])->name('drag-drop');

    Route::resource('/guia',  GuiaController::class);

    Route::resource('/agenda', AgendaController::class)->names(['index' => 'agenda']);

    Route::get('/checklist',                                [ChecklistController::class, 'index'])->name('checklist.index');
    Route::match(['get', 'post'],'/checklist/{agenda_id}',  [ChecklistController::class, 'show'])->name('checklist.edit');

    Route::delete('/checklist',                             [ChecklistController::class, 'delete'])->name('checklist.delete');

    Route::prefix('administracao')->name('adm.')->middleware(['web', 'auth.caixa','admin'])->group(function () {

        Route::get('/tipodeagendamento', function () {
            return view('pages.administracao.tipodeagendamento');
        })->name('tipodeagendamento');

        Route::get('/checklist', function () {
            return view('pages.administracao.checklistitens');
        })->name('checklist');

        Route::resource('/guia', AdmGuiaController::class);
    });
});



