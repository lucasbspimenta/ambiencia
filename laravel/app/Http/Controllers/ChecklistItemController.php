<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Services\ChecklistItemService;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    protected $checklistItemService;

    public function __construct(ChecklistItemService $checklistItemService)
    {
        $this->checklistItemService = $checklistItemService;
    }

    public function index()
    {
        $itens = $this->checklistItemService->todos();
        return view('pages.administracao.checklistadm', compact('itens'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ChecklistItem $checklistItem)
    {
        //
    }

    public function edit(ChecklistItem $checklistItem)
    {
        //
    }

    public function update(Request $request, ChecklistItem $checklistItem)
    {
        //
    }

    public function destroy(ChecklistItem $checklistItem)
    {
        //
    }
}
