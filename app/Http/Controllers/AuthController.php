<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    private $users;

    /**
     * Função Construtora para iniciar variável como model.
     */
    public function __construct(User $users)
    {
        $this->users = $users;
    }

    /**
     * Função responsável por avisar que um usuário não está autenticado.
     *
     * @return json(array, code)
     */
    public function unauthenticatedUser() {
        return response()->json(['error' => 'Token inválido'], 401);
    }

    /**
     * Função responsável por autenticar um usuário e gerar o token.
     *
     * @param \Illuminate\Http\Request  $request
     * @return json(array, code)
     */
    public function authenticateUser(Request $request) {
        // validação dos campos
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'E-mail é obrigatório.',
            'email.email' => 'E-mail é obrigatório.',
            'password.required' => 'Senha é obrigatória',
            'password.string' => 'Senha é obrigatória.',
        ]);

        // retorna com erros de validação caso exista
        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = $this->users->where('email', $request->email)->first(); // procura usuário com email passado na requisição

            if (! $user || ! Hash::check($request->password, $user->password)) { // compara senha de usuário com senha passada na requisição
                return response()->json(['error' => 'Usuário ou senha inválidos.'], 401);
            }

            $token = $user->createToken('auth')->plainTextToken; // gera token de autenticação
            $user->token = $token;

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Ops, ocorreu um erro.'], 400);
        }
    }
}
