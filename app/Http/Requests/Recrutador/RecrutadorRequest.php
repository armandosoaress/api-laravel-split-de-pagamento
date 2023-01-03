<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecrutadorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nome"          => "required|min:3|max:160",
            "endereco"      => "required|min:3|max:160",
            "cidade"        => "required|min:3|max:160",
            "estado"        => "required|min:2|max:4",
            "telefone"      => "required|min:11|max:15",
            "email"         => "required|min:3|max:160|email",
            "banco"         => "required|min:3|max:160",
            "conta"         => "required|min:3|max:40",
            "agencia"       => "required|min:3|max:40",
            "id_regional"   => "required|integer"
        ];
    }
}
