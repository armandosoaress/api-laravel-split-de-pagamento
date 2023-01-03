<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hierarquia extends Model
{
    use HasFactory;

    protected $table  = 'dados_hirarquia_pagamento';

    protected $fillable = [
        'motoboy_id', 'dependent_user_id','nivel_de_acesso_dependent'
    ];
}
