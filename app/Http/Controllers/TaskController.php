<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskRepositories;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $task ;
    
    public function __construct(task $task) {
        $this->task = $task;
    }

    public function index(Request $request)
    {
        $task = $this->task->where('tas_ativo', '=', "S");
        $TaskRepositories = new TaskRepositories($task);
        $TaskRepositories->setModelWithWhere($request);
        $task = $TaskRepositories->getModel();
        return response()->json($task, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $TaskRepositories = new TaskRepositories($this->task);
        $idUser = $TaskRepositories->getIDUser();
        $request->validate($this->task->rules(), $this->task->feedback());
        $task = $this->task->create([
            'tas_nome' => $request->tas_nome,
            'user_id' => $idUser,
            'status_id' => $request->status_id,
            'tas_data_inicio' => $request->tas_data_inicio,
            'tas_data_final' => !empty($request->tas_data_final) ? $request->tas_data_final : '0000-00-00',
            'tas_observacao' => !empty($request->tas_observacao) ? $request->tas_observacao : '',
        ]);  
        return response()->json($task, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $taskCodigo)
    {
        $idUser = TaskRepositories::getIDUser();
        $task = $this->task->where('user_id', '=', $idUser)
                            ->where('tas_ativo', '=', "S")
                            ->find($taskCodigo);
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $taskCodigo)
    {
        $idUser = TaskRepositories::getIDUser();
        $task = $this->task->where('user_id', '=', $idUser)
                            ->where('tas_ativo', '=', "S")
                            ->find($taskCodigo);

        if(empty($task))
        {
            return response()->json(['msg'=>'Impossivel realizar a requisição de update'],404);
        }    
        if($request->method() == "PATCH")
        {
            $regrasDinamicas = array();
            foreach($task->rules() as $input => $regra)
            {
                if(array_key_exists($input, $request->all()))
                {
                   $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $this->task->feedback());
        }else
        {
            $request->validate($task->rules(), $this->task->feedback());
        }
        $arrayUpdate = array();
        
        if(!empty($request->status)){
            $arrayUpdate['status_id'] = $request->status;
        }
        if(!empty($request->nome)){
            $arrayUpdate['tas_nome'] = $request->nome;
        }
        if(!empty($request->data_inicio)){
            $arrayUpdate['tas_data_inicio'] = $request->data_inicio;
        }
        $task->update($arrayUpdate);

        $task->save();
        
        return response()->json($task,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $taskCodigo)
    {
        $task = $this->task->find($taskCodigo);
        if (!$task) {
            return response()->json(['error' => 'Tarefa não encontrada'], 404);
        }
        $task = $task->update([
            'tas_ativo' => 'N'
        ]);  
        return response()->json(['message' => 'Tarefa excluida com sucesso'], 200);
    }
}
