<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DemandaResource;
use App\Services\DemandaService;

class DemandaAPIController extends Controller
{
    protected $demandaService;

    public function __construct(DemandaService $demandaService)
    {
        $this->demandaService = $demandaService;
    }

    public function index($finalizados = false)
    {
        return DemandaResource::collection($this->demandaService->todosDatatables($finalizados));
    }
}
