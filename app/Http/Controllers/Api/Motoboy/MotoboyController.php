<?php

namespace App\Http\Controllers\api\Motoboy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MotoboyController extends Controller
{
    public function index(Request $request)
    {
        $user =  DB::table('users')
            ->select(
                'users.terms_and_services'
            )
            ->where('users.id', '=', $request->id)
            ->get();

        return $user;
    }

    public function altetermsandservices(Request $request)
    {
        $user = User::find($request->id);
        $user->terms_and_services = 1;
        try {
            $user->save();
            return response()->json(['message' => 'sucess'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao aceitar termos e serviços!'], 500);
        }
    }
}
