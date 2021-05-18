<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgendamentoTipoService;

class AgendaController extends Controller
{
    protected $agendamentoTipoService;
    protected $agendamentooService;

    public function __construct(AgendamentoTipoService $agendamentoTipoService)
    {
        $this->agendamentoTipoService = $agendamentoTipoService;
    }

    public function index()
    {
        $lista_tipos_de_agendamento = $this->agendamentoTipoService->todosAtivos();
        return view('pages.agenda', compact('lista_tipos_de_agendamento'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
