<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\equipeuser;
use App\Models\dadosuser;
use App\Models\pagamento;
use App\Models\motoboys;
use App\Models\hierarquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index(Request $request)
    {
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
            ->where('users.niveis_acesso_id', '=', '2')
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
            "cho" =>  $paginator->items(),
            "proxima" => $paginator->nextPageUrl(),
            "Anterior" => $paginator->previousPageUrl()

        ];
        return $page;
    }




    public function indexoptions(Request $request)
    {

        $motoboys =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '6')
            ->orderBy('created_at', 'desc')
            ->paginate(100000);


        $supervisores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '5')
            ->paginate();

        $recrutadores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '4')
            ->paginate();

        $coordenadores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '3')
            ->paginate();

        $chos =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '2')
            ->paginate();




        $page = [
            "supervisores" =>  $supervisores->items(),
            "recrutadores" =>  $recrutadores->items(),
            "coordenadores" =>  $coordenadores->items(),
            "motoboys" =>  $motoboys->items(),
            "chos" =>  $chos->items(),

        ];
        return $page;
    }


    public function storemotoboy(Request $request)
    {
        try {
            //inserindo na tabela user para motoboy

            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->customer_assas = $request->customer_assas;
            $user->password = bcrypt('password');
            $user->niveis_acesso_id = 6;
            $user->save();

            //inserindo na tabela dados user para motoboy

            $Dadosuser = new dadosuser;
            $Dadosuser->dados_user_id =  $user->id;
            $Dadosuser->save();

            //   //inserindo na tabela equipe user para motoboy

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $request->dependent_supervisor;
            $equipeuser->nivel_de_acesso_dependent = "supervisor";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $recrutador = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "Recrutador")
                ->where('equipe_user.user_id', '=', $request->dependent_supervisor)
                ->get();



            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $recrutador[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "recrutador";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $coordenador = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "coordenador")
                ->where('equipe_user.user_id', '=', $request->dependent_supervisor)
                ->get();

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $coordenador[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "coordenador";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $cho = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "cho")
                ->where('equipe_user.user_id', '=', $request->dependent_supervisor)
                ->get();

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $cho[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "cho";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = 1;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();


            //inserindo na tabela motoboy user para motoboy

            $motoboys = new motoboys;
            $motoboys->dados_user_id =  $user->id;
            $motoboys->save();

            return response()->json([
                "Status"    => "sucess",
                "user"    => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"    => $th,
            ], 200);
        }
    }

    public function storesupervisor(Request $request)
    {
        try {
            //inserindo na tabela user para motoboy
            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->customer_assas = $request->customer_assas;
            $user->password = bcrypt('password');
            $user->niveis_acesso_id = 5;
            $user->save();

            //inserindo na tabela dados user para motoboy

            $Dadosuser = new Dadosuser;
            $Dadosuser->dados_user_id =  $user->id;
            $Dadosuser->save();

            //   //inserindo na tabela equipe user para motoboy

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $request->dependent_recrutador;
            $equipeuser->nivel_de_acesso_dependent = "recrutador";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $coordenador = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "coordenador")
                ->where('equipe_user.user_id', '=', $request->dependent_recrutador)
                ->get();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $coordenador[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "coordenador";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $cho = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "cho")
                ->where('equipe_user.user_id', '=', $request->dependent_recrutador)
                ->get();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $cho[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "cho";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = 1;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            return response()->json([
                "Status"    => "sucess"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"    => $th,
            ], 200);
        }
    }

    public function storerecrutador(Request $request)
    {
        try {
            //inserindo na tabela user para motoboy
            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->customer_assas = $request->customer_assas;
            $user->password = bcrypt('password');
            $user->niveis_acesso_id = 4;
            $user->save();

            //inserindo na tabela dados user para motoboy

            $Dadosuser = new Dadosuser;
            $Dadosuser->dados_user_id =  $user->id;
            $Dadosuser->save();

            //   //inserindo na tabela equipe user para motoboy

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $request->dependent_coordenador;
            $equipeuser->nivel_de_acesso_dependent = "coordenador";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            $cho = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "cho")
                ->where('equipe_user.user_id', '=', $request->dependent_coordenador)
                ->get();

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $cho[0]->dependent_user_id;
            $equipeuser->nivel_de_acesso_dependent = "cho";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = 1;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            return response()->json([
                "Status"    => "sucess"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"    => $th,
            ], 200);
        }
    }

    public function storecoordenador(Request $request)
    {
        try {
            //inserindo na tabela user 
            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->customer_assas = $request->customer_assas;
            $user->password = bcrypt('password');
            $user->niveis_acesso_id = 3;
            $user->save();

            //inserindo na tabela dados user

            $Dadosuser = new Dadosuser;
            $Dadosuser->dados_user_id =  $user->id;
            $Dadosuser->save();

            //   //inserindo na tabela equipe user para motoboy

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $request->dependent_cho;
            $equipeuser->nivel_de_acesso_dependent = "cho";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();


            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = 1;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            return response()->json([
                "Status"    => "sucess"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"    => $th,
            ], 200);
        }
    }

    public function storecho(Request $request)
    {
        try {
            //inserindo na tabela user 
            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->customer_assas = $request->customer_assas;
            $user->password = bcrypt('password');
            $user->niveis_acesso_id = 2;
            $user->save();

            //inserindo na tabela dados user

            $Dadosuser = new Dadosuser;
            $Dadosuser->dados_user_id =  $user->id;
            $Dadosuser->save();

            //   //inserindo na tabela equipe user 

            $equipeuser = new equipeuser;
            $equipeuser->dependent_user_id = $request->dependent_admin;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->user_id =  $user->id;
            $equipeuser->save();

            return response()->json([
                "Status"    => "sucess"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "Error",
                "erro"    => $th,
            ], 200);
        }
    }

    public function show(Request $request)
    {
        $user =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                // 'users.password',
                'niveis_acessos.nome as funcao',
                'dados_users.endereco',
                'dados_users.cidade',
                'dados_users.estado',
                'dados_users.pix_chave',
                'dados_users.telefone',
                'users.email',
                'dados_users.banco',
                'dados_users.conta',
                'dados_users.agencia',
            )
            ->join('niveis_acessos', 'niveis_acessos.idAcess', '=', 'users.niveis_acesso_id')
            ->join('dados_users', 'dados_users.dados_user_id', '=', 'users.id')
            ->where('users.id', '=',  $request->id)
            ->get();


        return $user[0];
    }

    public function putuser(Request $request)
    {



        try {
            $user = User::find($request->id);
            $user->name = $request->nome;
            $user->password = bcrypt($request->senha);

            $dadosUser =  DB::table('dados_users')
                ->select(
                    'id',
                )
                ->where('dados_users.dados_user_id', '=',  $request->id)
                ->get();


            $dadosUser = Dadosuser::find($dadosUser[0]->id);
            $dadosUser->endereco = $request->endereco;
            $dadosUser->cidade = $request->cidade;
            $dadosUser->estado = $request->estado;
            $dadosUser->telefone = $request->telefone;
            $dadosUser->banco = $request->banco;
            $dadosUser->conta = $request->conta;
            $dadosUser->agencia = $request->agencia;
            $dadosUser->pix_chave = $request->pix;
            $dadosUser->pix_type = $request->tipopix;


            $user->firstaccess = 1;

            $user->save();
            $dadosUser->save();


            return response()->json([
                "Status"    => "sucess",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "erro",
            ], 200);
        }
    }

    public function confirmarpagamentomotoboy(Request $request)
    {
        try {


            $idmotoboy = $request->id_motoboy;


            $motoboynumregister = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'motoboy_id'
                )
                ->where('dados_hirarquia_pagamento.motoboy_id', '=', $idmotoboy)
                ->get();

            if ($motoboynumregister->count() >= 6) {
                return response()->json([
                    "Status"    => "motoboy ja comprou maquininha"
                ], 200);
            }


            $user = new pagamento;
            $user->id_motoboy = $request->id_motoboy;
            try {
                $user->save();
            } catch (\Throwable $th) {
                return response()->json([
                    "Status"    => "motoboy ja comprou maquininhaaa"
                ], 200);
            }



            //pegando id do supervisor do motoboy

            $supervisor = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "Supervisor")
                ->where('equipe_user.user_id', '=', $idmotoboy)
                ->get();


            $idsupervisor = $supervisor[0]->dependent_user_id;

            //atualizar saldo
            $saldosupervisor = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'valor_a_pagar'
                )
                ->where('dados_hirarquia_pagamento.dependent_user_id', '=', $idsupervisor)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();

            try {
                $saldo = $saldosupervisor[0]->valor_a_pagar + 10;
            } catch (\Throwable $th) {
                $saldo = 10;
            }
            //fim

            $equipeuser = new hierarquia;
            $equipeuser->dependent_user_id = $idsupervisor;
            $equipeuser->nivel_de_acesso_dependent = "supervisor";
            $equipeuser->motoboy_id =  $idmotoboy;
            $equipeuser->valor_a_pagar =  $saldo;
            $equipeuser->save();


            //pegando id do recrutador do motoboy

            $recrutador = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "Recrutador")
                ->where('equipe_user.user_id', '=',  $idmotoboy)
                ->get();

            $idrecrutador = $recrutador[0]->dependent_user_id;


             //atualizar saldo
             $saldosupervisor = DB::table('dados_hirarquia_pagamento')
             ->select(
                 'valor_a_pagar'
             )
             ->where('dados_hirarquia_pagamento.dependent_user_id', '=', $idrecrutador)
             ->limit(1)
             ->orderBy('id', 'desc')
             ->get();


         try {
             $saldo = $saldosupervisor[0]->valor_a_pagar + 10;
         } catch (\Throwable $th) {
             $saldo = 10;
         }
         //fim


            $equipeuser = new hierarquia;
            $equipeuser->dependent_user_id = $idrecrutador;
            $equipeuser->nivel_de_acesso_dependent = "recrutador";
            $equipeuser->motoboy_id =   $idmotoboy;
            $equipeuser->valor_a_pagar =  $saldo;
            $equipeuser->save();



            //pegando id do coordenador do motoboy

            $coordenador = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "coordenador")
                ->where('equipe_user.user_id', '=', $idmotoboy)
                ->get();

            $idcoordenador = $coordenador[0]->dependent_user_id;

                //atualizar saldo
                $saldosupervisor = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'valor_a_pagar'
                )
                ->where('dados_hirarquia_pagamento.dependent_user_id', '=', $idcoordenador)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();
   
   
            try {
                $saldo = $saldosupervisor[0]->valor_a_pagar + 15;
            } catch (\Throwable $th) {
                $saldo = 15;
            }
            //fim

            $equipeuser = new hierarquia;
            $equipeuser->dependent_user_id = $idcoordenador;
            $equipeuser->nivel_de_acesso_dependent = "coordenador";
            $equipeuser->motoboy_id =  $idmotoboy;
            $equipeuser->valor_a_pagar =  $saldo;
            $equipeuser->save();


            //pegando id do cho do motoboy

            $cho = DB::table('equipe_user')
                ->select(
                    'equipe_user.id',
                    'equipe_user.nivel_de_acesso_dependent',
                    'equipe_user.dependent_user_id',
                )
                ->where('equipe_user.nivel_de_acesso_dependent', '=', "cho")
                ->where('equipe_user.user_id', '=', $idmotoboy)
                ->get();

            $idcho = $cho[0]->dependent_user_id;

                //atualizar saldo
                $saldosupervisor = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'valor_a_pagar'
                )
                ->where('dados_hirarquia_pagamento.dependent_user_id', '=', $idcho)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();
   
   
            try {
                $saldo = $saldosupervisor[0]->valor_a_pagar + 10;
            } catch (\Throwable $th) {
                $saldo = 10;
            }
            //fim

            $equipeuser = new hierarquia;
            $equipeuser->dependent_user_id = $idcho;
            $equipeuser->nivel_de_acesso_dependent = "cho";
            $equipeuser->motoboy_id =  $idmotoboy;
            $equipeuser->valor_a_pagar =  $saldo;
            $equipeuser->save();


            //colocar o id do admin que so tem um que no cadso se o admin com id igual a 1


                //atualizar saldo
                $saldosupervisor = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'valor_a_pagar'
                )
                ->where('dados_hirarquia_pagamento.dependent_user_id', '=',1 )
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();
   
   
            try {
                $saldo = $saldosupervisor[0]->valor_a_pagar + 10;
            } catch (\Throwable $th) {
                $saldo = 10;
            }
            //fim

            $equipeuser = new hierarquia;
            $equipeuser->dependent_user_id = 1;
            $equipeuser->nivel_de_acesso_dependent = "admin";
            $equipeuser->motoboy_id =  $idmotoboy;
            $equipeuser->valor_a_pagar =  $saldo;
            $equipeuser->save();



            return response()->json([
                "status"    =>  "sucess",
                "idsupervisor"    =>  $idsupervisor,
                "idrecrutador"    =>  $idrecrutador,
                "idcoordenador"    =>  $idcoordenador,
                "idcho"    =>  $idcho,
                "idadmin"    =>  1,
            ], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                "Status"    => "erro",
            ], 200);
        }
    }

    public function listartodososusers(Request $request)
    {

        $motoboys =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '6')
            ->orderBy('created_at', 'desc')
            ->paginate(100000);


        $supervisores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '5')
            ->paginate();



        $recrutadores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '4')
            ->paginate();

        $coordenadores =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '3')
            ->paginate();

        $chos =  DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
            )
            ->where('users.niveis_acesso_id', '=', '2')
            ->paginate();




        $page = [
            "supervisores" =>  $supervisores->items(),
            "recrutadores" =>  $recrutadores->items(),
            "coordenadores" =>  $coordenadores->items(),
            "motoboys" =>  $motoboys->items(),
            "chos" =>  $chos->items(),

        ];
        return $page;
    }

    public function retornarquantosregistrotemempagamento(Request $request)
    {
        $user =  DB::table('dados_hirarquia_pagamento')
            ->select(
                'dependent_user_id',
            )
            ->where('dependent_user_id', '=',  $request->id)
            ->get();

        return response()->json([
            "Status"    => "sucess",
            "num"    => $user->count()
        ], 200);
    }


    public function saldo(request $request)
    {

        try {
            $saldosupervisor = DB::table('dados_hirarquia_pagamento')
                ->select(
                    'valor_a_pagar'
                )
                ->where('dados_hirarquia_pagamento.dependent_user_id', '=', $request->id)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                "Status"    => "sucess",
                "saldo"    =>  $saldosupervisor[0]->valor_a_pagar
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Status"    => "sucess",
                "saldo"    =>  0
            ], 200);
        }
    }
}
