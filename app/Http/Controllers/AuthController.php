<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = 
        [
            'email' => $request->email,
            'password' => $request->senha 
        ];

        $token = auth("api")->attempt($credenciais);

        if($token){
            $payload = JWTAuth::setToken($token)->getPayload();

            // Tempo de expiração do token
            $exp = $payload->get('exp');
            $expirationTime = Carbon::createFromTimestamp($exp, 'America/Sao_Paulo');
            $expirationTime = $expirationTime->toDateTimeString();
            UserController::updateTokenUser($token, $request->email, $request->senha, $expirationTime);
            return response()->json(['token' => $token]);
        }else{
            return response()->json(['erro' => "Usuário ou senha inválido"], 403);
        }
        return 'Login';
    }

    public function logout()
    {
        auth("api")->logout();
        return response()->json(["msg"=>'Logout foi realizado com sucesso!']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token'=>$token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function cadastrar(Request $request)
    {
        
    }
}
