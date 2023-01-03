<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipeuser extends Model
{
    use HasFactory;

    protected $table  = 'equipe_user';

    protected $fillable = [
        'user_id', 'dependent_user_id','nivel_de_acesso_dependent'
    ];
}
