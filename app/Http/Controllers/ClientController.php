<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        try {
            $insert =  Client::create($request->all());
            return response()->json(['data' => $insert, 'message' => 'Dados inseridos com sucesso.'], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Erro ao inserir dados'], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data =  Client::find($id);
            return response()->json(['data' => $data], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Erro ao localizar dados'], 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = Client::find($id)->update($request->all());
            return response()->json(['data' => $data, 'message' => 'Dados atualizados com sucesso'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Erro ao atualizar dados'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Client::find($id)->delete();
            return response()->json(['message' => 'Dados removidos com sucesso.'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Falha ao deletar registro.'], 422);
        }
    }


    public function getPlate($number)
    {
        try {
            $data = Client::where(DB::raw("substr(plate, -1)"), $number)->get();
            return response()->json(['message' => 'Clientes localizados com sucesso.', 'data' => $data], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Falha ao localizar registro.'], 422);
        }
    }
}
