<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendamentoResource;
use App\Services\AgendamentoService;
use Illuminate\Http\Request;

class AgendamentoAPIController extends Controller
{
    protected $agendamentoService;

    public function __construct(AgendamentoService $agendamentoService)
    {
        $this->agendamentoService = $agendamentoService;
    }

    public function index()
    {
        return AgendamentoResource::collection($this->agendamentoService->todos()->keyBy->id);
    }

    public function indexPorTipo($tipo)
    {
        return AgendamentoResource::collection($this->agendamentoService->todos()->where('tipo.id', $tipo)->keyBy->id);
    }
}
