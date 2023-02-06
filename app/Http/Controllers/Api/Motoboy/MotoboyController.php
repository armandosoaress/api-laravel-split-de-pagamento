<?php

namespace App\Http\Controllers\api\Motoboy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotoboyController extends Controller
{
    public function index(Request $request)
    {
        $user =  DB::table('users')
            ->select(
                'users.terms_and_services'
            )
            ->where('users.id', '=', '10')
            ->get();

        return $user;
    }
}
