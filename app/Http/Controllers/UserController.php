<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
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
     * Função responsável por listar os usuários cadastrados.
     *
     * @return json(array, code)
     */
    public function getAllUsers() {
        return response()->json(User::all(), 200);
    }

    /**
     * Função responsável por cadastrar um novo usuário.
     *
     * @param \Illuminate\Http\Request  $request
     * @return json(array, code)
     */
    public function storeNewUser(Request $request) {
        // validação dos campos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:users,email', 'email', 'max:255'],
            'password' => ['required', 'string', 'between:6, 255'],
        ], [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome é obrigatório',
            'name.max' => 'Nome deve ter no máximo 255 caracteres.',
            'email.required' => 'E-mail é obrigatório.',
            'email.email' => 'E-mail é obrigatório.',
            'email.max' => 'E-mail deve ter no máximo 255 caracteres.',
            'email.unique' => 'E-mail já registrado.',
            'password.required' => 'Senha é obrigatória',
            'password.string' => 'Senha é obrigatória.',
            'password.between' => 'Senha deve ter entre 6 e 255 caracteres.',
        ]);

        // retorna com erros de validação, caso exista
        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = $this->users->create($request->all()); // cadastra novo usuário informações passadas na requisição

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Função responsável por editar o usuário autenticado.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return json(array, code)
     */
    public function updateAuthenticatedUser(Request $request) {
        // validação dos campos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', Rule::unique('users')->ignore($request->user()->id), 'email', 'max:255'],
        ], [
            'name.required' => 'Nome é obrigatório',
            'name.string' => 'Nome é obrigatório',
            'name.max' => 'Nome deve ter no máximo 255 caracteres.',
            'email.required' => 'E-mail é obrigatório.',
            'email.email' => 'E-mail é obrigatório.',
            'email.max' => 'E-mail deve ter no máximo 255 caracteres.',
            'email.unique' => 'E-mail já registrado.',
        ]);

        // retorna com erros de validação, caso exista
        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = $request->user(); // salva em variável as informações de usuário autenticado

            // altera as informações na variável de acordo com o que foi passado na requisição
            $user->email = $request->email;
            $user->name = $request->name;
            $user->update(); // atualiza as informações do usuário no banco

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
