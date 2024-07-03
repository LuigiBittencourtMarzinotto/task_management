<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class AbstractRepository{
    private $model ;

    public function __construct($model){
        $this->model = $model;
    }

    public function setModelWithWhere($request){
        if($request->has('filters')){
            foreach($request->filters as $filter){
                $column = $filter["column"];
                $operator = $filter["operator"];
                $value = $filter["value"];
                $this->model =  $this->model->where($column, $operator, $value);
            }
        }
        $idUser = $this->getIDUser();
        $this->model =  $this->model->where('user_id', '=', $idUser);
    }

    public function getModel(){
        $this->model = $this->model->get();
        return $this->model;
    }

    public  static function getIDUser(){
        $request = request();
        $token = $request->bearerToken();
        if ($token) {
            $payload = JWTAuth::setToken($token)->getPayload();
            $sub = $payload->get('sub');
            return $sub;
        } else {
            return response()->json(['error' => 'Token não encontrado'], 401);
        }
    }

}
