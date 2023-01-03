<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recrutador extends Model
{
    use HasFactory;

    protected $table    = 'recrutador';

    protected $fillable = [
        'nome',  'endereco', 'cidade', 'estado', 'telefone',
        'email', 'banco', 'conta', 'agencia', 'id_regional',
    ];
}
