<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendamentoResource;
use App\Services\AgendamentoService;
use Illuminate\Support\Facades\Session;

class AgendamentoAPIController extends Controller
{
    protected $agendamentoService;

    public function __construct(AgendamentoService $agendamentoService)
    {
        $this->agendamentoService = $agendamentoService;
    }

    public function index()
    {
        return AgendamentoResource::collection($this->agendamentoService->todosCalendario()->keyBy->id);
    }

    public function indexPorTipo($tipo)
    {
        return AgendamentoResource::collection($this->agendamentoService->todosCalendario($tipo)->keyBy->id);
    }
}
