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

            $motoboys->save();
            return response()->json(['message' => 'sucess'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }


    public function photodateChecked(Request $request)
    {
        try {

            $idmotoboy = DB::table('motoboys')
                ->select('id')
                ->where('dados_user_Id', $request->id)
                ->get();

            if ($request->foto_cnh) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_cnh = $request->foto_cnh;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            } else if ($request->foto_placa_moto) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_placa_moto = $request->foto_placa_moto;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            } else if ($request->foto_frente_da_moto) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_frente_da_moto = $request->foto_frente_da_moto;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            } else if ($request->foto_trazeira_da_moto) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_trazeira_da_moto = $request->foto_trazeira_da_moto;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            } else if ($request->foto_esquerda_da_moto) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_esquerda_da_moto = $request->foto_esquerda_da_moto;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            } else if ($request->foto_direita_da_moto) {
                $motoboys =  motoboys::find($idmotoboy[0]->id);
                $motoboys->foto_direita_da_moto = $request->foto_direita_da_moto;
                $motoboys->save();
                return response()->json(['message' => 'sucess'], 200);
            }

            return response()->json(['message' => 'sucess'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }


    public function listmotoboys(Request $request)
    {

        $motoboys =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                 'niveis_acessos.nome as funcao',
            )
            ->join('niveis_acessos', 'niveis_acessos.idAcess', '=', 'users.niveis_acesso_id')
            ->join('equipe_user', 'equipe_user.user_id', '=', 'users.id')
            ->where('users.niveis_acesso_id', '=', '6')
            ->where('equipe_user.dependent_user_id', '=', $request->id)
            ->get();

        return response()->json(
            [
                'message' => 'sucess',
                'data' => $motoboys
            ],
            200
        );
    }
}
