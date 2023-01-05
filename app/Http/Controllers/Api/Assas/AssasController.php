<?php

namespace App\Http\Controllers\Api\Assas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;



class AssasController extends Controller
{

    /**
     * Função para cadastrar no banco
     *
     * @param RecrutadorRequest $request
     * @return bool
     */
    public function listpay(request $request)
    {
        $response = Http::withHeaders([
            'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
        ])
            ->get('https://www.asaas.com/api/v3/payments?limit=100');

        return  $response->json();
    }

    public function listpaymotoboy(request $request)
    {
        $response = Http::withHeaders([
            'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
        ])
            ->get('https://www.asaas.com/api/v3/payments?limit=100&externalReference='.$request->externalReference);

        return  $response->json();
    }

    public function pay(request $request)
    {
        $response = Http::withBody(
            '{
         "customer": ' . $request->customer . ',
         "billingType": ' . $request->billingType . ',
         "dueDate": ' . $request->dueDate . ',
         "value": ' . $request->value . ',
         "description": ' . $request->description . ',
         "externalReference": ' . $request->externalReference . ',
            }',
            'json'
        )
            ->withHeaders([
                'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
                'Content-Type' => 'application/json',
            ])
            ->post('https://www.asaas.com/api/v3/payments');

        return $response->json();
    }

    public function listpayid(request $request)
    {
        $response = Http::withHeaders([
            'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
        ])
            ->get('https://www.asaas.com/api/v3/payments/' . $request->id);

        return  $response->json();
    }

    public function cliente(request $request)
    {
        $response = Http::withHeaders([
            'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
        ])
            ->get('https://www.asaas.com/api/v3/customers/' . $request->id);

        return  $response->json();
    }

    public function custumerporiddomeubanco(request $request)
    {

        $user = User::find($request->id);
        return response()->json([
            "customer"    => $user->customer_assas
        ], 200);
    }

    public function cadcliente(request $request)
    {
        $response = Http::withBody(
            '{
         "name": ' . $request->name . ',
         "email": ' . $request->email . ',
         "cpfCnpj": "12328011004",
            }',
            'json'
        )
            ->withHeaders([
                'access_token' => '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyNDkwNTc6OiRhYWNoX2ZkOTI1MzgxLWE1ZWYtNDhhNS1iYjhmLTAwZTEwODNlMDJmOQ==',
                'Content-Type' => 'application/json',
            ])
            ->post('https://www.asaas.com/api/v3/customers');

        return $response->json();
    }
}
