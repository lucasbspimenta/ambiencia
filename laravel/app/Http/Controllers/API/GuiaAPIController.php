<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuiaResource;
use App\Services\GuiaService;

class GuiaAPIController extends Controller
{
    protected $guiaService;

    public function __construct(GuiaService $guiaService)
    {
        $this->guiaService = $guiaService;
    }

    public function index()
    {
        return ['data' => GuiaResource::collection($this->guiaService->todos()->keyBy->id)];
    }
}
