<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Services\AgendamentoService;
use App\Services\ChecklistItemService;
use App\Services\ChecklistService;
//use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    protected $checklistService;
    protected $agendamentoService;

    public function __construct(ChecklistService $checklistService, AgendamentoService $agendamentoService)
    {
        $this->checklistService = $checklistService;
        $this->agendamentoService = $agendamentoService;
    }

    public function index()
    {
        //$checklists = $this->checklistService->todos();
        $agendamentos_sem_checklist = $this->agendamentoService->agendamentosSemChecklist();
        return view('pages.checklist.index', compact('agendamentos_sem_checklist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist)
    {
        return view('pages.checklist.exibir', compact('checklist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
        //app('debugbar')->disable();

        if ((boolean) $checklist->concluido || $checklist->agendamento->unidade->responsavel->matricula != Auth::user()->matricula) {
            return redirect()->route('checklist.show', ['checklist' => $checklist->id]);
        }

        return view('pages.checklist.preenchimento', compact('checklist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function imprimir(Request $request)
    {
        $itens = ChecklistItemService::todosAtivos();
        $pdf = \PDF::loadView('pages.checklist.imprimir', compact('itens'))->setPaper('a4', 'portrait');
        return $pdf->download('checklist-imprimir.pdf');
        return view('pages.checklist.imprimir', compact('itens'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
