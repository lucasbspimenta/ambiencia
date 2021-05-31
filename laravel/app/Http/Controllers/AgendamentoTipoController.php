<?php

namespace App\Http\Controllers;

use App\Services\AgendamentoTipoService;
use Illuminate\Http\Request;

class AgendamentoTipoController extends Controller
{
    protected $agendamentoTipoService;

    public function __construct(AgendamentoTipoService $agendamentoTipoService)
    {
        $this->agendamentoTipoService = $agendamentoTipoService;
    }

    public function index()
    {
        $tipos = $this->agendamentoTipoService->todos();
        return view('pages.administracao.agendamentotipo', compact('tipos'));
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
