<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Repositories\StatusRepositories;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    private $status;

    public function __construct(status $status) {
        $this->status = $status;
    }

    public function index(Request $request)
    {
        $status = $this->status->where('st_ativo', '=', "S");
        $StatusRepositories = new StatusRepositories($status);
        $StatusRepositories->setModelWithWhere($request);
        $task = $StatusRepositories->getModel();
        return response()->json($task, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $StatusRepositories = new StatusRepositories($this->status);
        $idUser = $StatusRepositories->getIDUser();
        $request->validate($this->status->rules(), $this->status->feedback());
        $status = $this->status->create([
            'st_nome' => $request->nome,
            'user_id' => $idUser
        ]);  
        return response()->json($status, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $codigoStatus)
    {
        $idUser = StatusRepositories::getIDUser();
        $status = $this->status->where('user_id', '=', $idUser)
                                ->where('st_ativo', '=', "S")
                                ->find($codigoStatus);
        return response()->json($status, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $codigoStatus)
    {
        $idUser = StatusRepositories::getIDUser();
        $status = $this->status->where('user_id', '=', $idUser)
                            ->where('st_ativo', '=', "S")
                            ->find($codigoStatus);
        if(empty($status))
        {
            return response()->json(['msg'=>'Impossivel realizar a requisição de update'],404);
        }  

        $request->validate($status->rules(), $this->status->feedback());
        $status->update([
            'st_nome' => $request->nome
        ]);
        $status->save();
        return response()->json($status,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $codigoStatus)
    {
        $status = $this->status->find($codigoStatus);
        if (!$status) {
            return response()->json(['error' => 'Status não encontrada'], 404);
        }
        $status = $status->update([
            'st_ativo' => 'N'
        ]);  
        return response()->json(['message' => 'Status excluida com sucesso'], 200);
    }
}
