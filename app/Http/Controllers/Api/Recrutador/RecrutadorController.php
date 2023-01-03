<?php

namespace App\Http\Controllers\Api\Recrutador;

use App\Http\Requests\RecrutadorRequest;
use App\Models\Recrutador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecrutadorController extends Controller
{

    /**
     * Função para cadastrar no banco
     *
     * @param RecrutadorRequest $request
     * @return bool
     */
    public function store(RecrutadorRequest $request)
    {
        try {
            if (Recrutador::create($request->all()))
                return response()->json([
                    "Status"    => "Success",
                    "Menssage"  => "Recrutador cadastrado com sucesso!"
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"      => $th
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $paginator =  DB::table('users')
        ->select(
            'users.id',
            'users.name',
            'niveis_acessos.nome as funcao',
            'dados_users.endereco',
            'dados_users.cidade',
            'dados_users.estado',
            'dados_users.telefone',
            'users.email',
            'dados_users.banco',
            'dados_users.conta',
            'dados_users.agencia',
        )
        ->join('niveis_acessos', 'niveis_acessos.idAcess', '=', 'users.niveis_acesso_id')
        ->join('dados_users', 'dados_users.dados_user_id', '=', 'users.id')
        ->join('equipe_user', 'equipe_user.user_id', '=', 'users.id')
        ->where('users.niveis_acesso_id', '=', '5')
        ->where('equipe_user.dependent_user_id', '=', $request->id)
            ->paginate();
        if ($paginator->items() == []) {
            $page = [
                "message" => 'página não disponível',
                "page_1" =>  $paginator->url(1)
            ];
            return $page;
        }
        $page = [
            "npagina" => $paginator->currentPage(),
            "nTotpagina" => $paginator->lastPage(),
            "nTottregistros" =>  $paginator->total(),
            "Supervisores" =>  $paginator->items(),
            "proxima" => $paginator->nextPageUrl(),
            "Anterior" => $paginator->previousPageUrl()

        ];
        return $page;
    }
}
