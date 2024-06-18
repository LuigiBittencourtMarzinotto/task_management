<?php
namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user ;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index(){
        return $this->user->get();
    }

    public function register(Request $request)
    {
        $request->validate($this->user->rules(), $this->user->feedback());

        $user = $this->user->create([
            'name' => $request->nome,
            'password' => bcrypt($request->senha),
            'email' => $request->email
        ]);

        return response()->json($user,201);
    }
    
    public static function getUserByEmailAndPassword($email, $password){
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
    public static function updateTokenUser($token, $email, $password, $expirationTime){
        $user = array();
        $user = self::getUserByEmailAndPassword($email, $password);
        if ($user) {
            $user->api_token = $token;
            $user->created_token = Carbon::now('America/Sao_Paulo');
            $user->expires_token = $expirationTime;
            $user->save();
            return $user;
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

}
