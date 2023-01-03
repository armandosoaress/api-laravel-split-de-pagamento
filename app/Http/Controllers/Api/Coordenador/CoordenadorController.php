<?php

namespace App\Http\Controllers\api\Coordenador;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CoordenadorController extends Controller
{

    public function store(Request $request)
    {
       return $request;
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
        ->where('users.niveis_acesso_id', '=', '4')
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
            "Recrutadores" =>  $paginator->items(),
            "proxima" => $paginator->nextPageUrl(),
            "Anterior" => $paginator->previousPageUrl()

        ];
        return $page;
    }
}
