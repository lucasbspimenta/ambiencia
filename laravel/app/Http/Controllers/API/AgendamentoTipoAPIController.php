<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendamentoTipoResource;
use App\Services\AgendamentoTipoService;
use Illuminate\Http\Request;

class AgendamentoTipoAPIController extends Controller
{
    protected $agendamentoTipoService;

    public function __construct(AgendamentoTipoService $agendamentoTipoService)
    {
        $this->agendamentoTipoService = $agendamentoTipoService;
    }

    public function index()
    {
        return ['data' => AgendamentoTipoResource::collection($this->agendamentoTipoService->todos())];
    }
}
