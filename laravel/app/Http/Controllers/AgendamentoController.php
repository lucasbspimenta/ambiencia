<?php

namespace App\Http\Controllers;

use App\Services\AgendamentoService;
use App\Services\AgendamentoTipoService;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    protected $agendamentoTipoService;
    protected $agendamentoService;

    public function __construct(AgendamentoTipoService $agendamentoTipoService, AgendamentoService $agendamentoService)
    {
        $this->agendamentoTipoService = $agendamentoTipoService;
        $this->agendamentoService = $agendamentoService;
    }

    public function index()
    {
        $unidades_vinculadas = []; // Unidade::select('id', 'codigo', 'tipoPv', 'unidades.nome')->orderBy('unidades.nome', 'ASC')->get();
        $lista_tipos_de_agendamento = $this->agendamentoTipoService->todosAtivos();
        return view('pages.agenda', compact('lista_tipos_de_agendamento', 'unidades_vinculadas'));
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

    public function update(Request $request, $id = null)
    {
        if (is_null($id)) {
            $id = $request->id;
        }

        if (isset($request->id)) {
            unset($request->id);
        }

        $agendamento = $this->agendamentoService->findById($id);
        $agendamento->fill($request->toArray());

        $this->agendamentoService->atualizar(
            $agendamento->toArray(),
            $id
        );
    }

    public function destroy($id)
    {
        //
    }
}
