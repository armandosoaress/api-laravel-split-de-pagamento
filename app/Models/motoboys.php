<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class motoboys extends Model
{
    use HasFactory;

    protected $table  = 'motoboys';

    protected $fillable = [
        'dados_user_Id'
    ];
}
