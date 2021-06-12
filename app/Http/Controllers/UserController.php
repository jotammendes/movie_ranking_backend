<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function auth(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'E-mail é obrigatório.',
            'password.required' => 'Senha é obrigatória',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Usuário ou senha inválidos.'], 401);
            }

            $token = $user->createToken('auth')->plainTextToken;
            $user->token = $token;

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Ops, ocorreu um erro.'], 400);
        }
    }

    public function index() {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'E-mail é obrigatório.',
            'name.required' => 'Nome é obrigatório',
            'password.required' => 'Senha é obrigatória',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = User::create($request->all());

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
        ], [
            'email.required' => 'E-mail é obrigatório.',
            'name.required' => 'Nome é obrigatório',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = $request->user();

            $user->email = $request->email;
            $user->name = $request->name;
            $user->update();

            return response()->json($user, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
