<?php

namespace App\Http\Controllers;

use App\Services\ChecklistItemService;
use App\Services\GuiaService;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    protected $guiaService;

    public function __construct(GuiaService $guiaService)
    {
        $this->guiaService = $guiaService;
    }

    public function index()
    {
        $guias = $this->guiaService->todosAtivos();

        return view('pages.guia', compact('guias'));
    }

    public function indexadm()
    {
        $checklistItens = ChecklistItemService::todos();
        $guias = $this->guiaService->todos();

        return view('pages.administracao.guiaadm', compact('guias','checklistItens'));
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
