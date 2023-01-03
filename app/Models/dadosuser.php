<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dadosuser extends Model
{
    use HasFactory;

    protected $table    = 'dados_users';

    protected $fillable = [
        'nome', 'email','dados_user_id'
    ];
}
