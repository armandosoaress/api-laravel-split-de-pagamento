<?php

namespace App\Http\Controllers\api\Motoboy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\motoboys;

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

    public function indexdateChecked(Request $request)
    {
        try {
            $motoboy = DB::table('motoboys')
                ->where('dados_user_Id', $request->id)
                ->get();
            return response()->json(
                [
                    'message' => 'sucess', 'data' => $motoboy
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro'], 500);
        }
    }

    public function dateChecked(Request $request)
    {
        try {

            $idmotoboy = DB::table('motoboys')
                ->select('id')
                ->where('dados_user_Id', $request->id)
                ->get();


            $motoboys =  motoboys::find($idmotoboy[0]->id);
            $motoboys->modelo_da_moto = $request->modelo_da_moto;
            $motoboys->placa_da_moto = $request->placa_da_moto;
            $motoboys->ano_da_moto = $request->ano_da_moto;
            $motoboys->cnh = $request->cnh;
            $motoboys->foto_cnh = $request->foto_cnh;
            $motoboys->foto_placa_moto = $request->foto_placa_moto;
            $motoboys ->foto_frente_da_moto = $request->foto_frente_da_moto;
            $motoboys->foto_trazeira_da_moto = $request->foto_trazeira_da_moto;
            $motoboys->foto_esquerda_da_moto = $request->foto_esquerda_da_moto;
            $motoboys->foto_direita_da_moto = $request->foto_direita_da_moto;

    

            $motoboys->save();
            return response()->json(['message' => 'sucess'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }
}
