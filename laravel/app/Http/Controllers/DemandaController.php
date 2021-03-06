<?php

namespace App\Http\Controllers;

use App\Models\Demanda;
use App\Models\DemandaSistema;
use Illuminate\Http\Request;

class DemandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demandas = Demanda::all();
        $demandas_sistemas = DemandaSistema::all();
        $unidades_vinculadas = []; //Unidade::select('id', 'codigo', 'tipoPv', 'unidades.nome')->orderBy('unidades.nome', 'ASC')->get();
        return view('pages.demandas', compact('demandas', 'demandas_sistemas', 'unidades_vinculadas'));
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
     * @param  \App\Models\Demanda  $demanda
     * @return \Illuminate\Http\Response
     */
    public function show(Demanda $demanda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Demanda  $demanda
     * @return \Illuminate\Http\Response
     */
    public function edit(Demanda $demanda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Demanda  $demanda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demanda $demanda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Demanda  $demanda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Demanda $demanda)
    {
        //
    }
}
