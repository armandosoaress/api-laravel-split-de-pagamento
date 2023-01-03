<?php

namespace App\Http\Controllers\Api\LoginController;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAccessTokens;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        try {
            $user->save();
            return response()->json([
                "status" => "Usuario cadastrado",
                "user" => $user
            ], 200);
        } catch (\Throwable $erro) {
            return response()->json([
                "status" => $erro
            ], 409);
        }
    }

    public function user(Request $request)
    {
        try {
            return response()->json([
                "status" => "Usuario Logado",
                "user" => Auth::user()
            ], 200);
        } catch (\Throwable $erro) {
            return response()->json([
                "status" => $erro
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $PersonalAccessTokens = new PersonalAccessTokens();
        PersonalAccessTokens::where('id',  auth()->user()->currentAccessToken()->id)->update([
            'expires_at' => Carbon::now()
                ->setTimezone('America/Sao_Paulo')
                ->format('y-m-d' . " " . 'H:i:s')
        ]);
        return response()->json(
            [
                'message' => 'Successfully logged out'
            ]
        );
    }


    public function login(Request $request)
    {
        try {
            if (Auth::attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password
                ]
            )) {
                $user = Auth::user();
                $token = $user->createToken('JWT');
                return response()->json([
                    "Status" => "Success",
                    "Token" => $token->plainTextToken,
                    "User" => $user
                ], 200);
                return response()->json($token->plainTextToken, 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "Status" => "Error",
                "erro" => $th
            ], 500);
        }
        return response()->json([
            "Status" => "Usuario ou senha incorreto"
        ], 401);
    }
}
