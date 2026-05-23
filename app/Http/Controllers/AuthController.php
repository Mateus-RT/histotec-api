<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class AuthController extends Controller
{
    // Método para autenticar e gerar o Bearer Token
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string', // Valida apenas se é string, não mais email
            'password' => 'required|string',
        ]);

        // Busca o usuário pelo username informado
        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    }

// Método de Atualização de Perfil incluindo validação de Username
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id, // Ignora o ID atual na validação de unicidade
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    }

    // 1. LISTAR USUÁRIOS (Apenas para Admins)
    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        // Retorna todos os usuários cadastrados ordenados pelo nome
        $users = User::orderBy('name', 'asc')->get(['id', 'name', 'username', 'email', 'is_admin']);
        return response()->json($users);
    }

// 2. CADASTRAR NOVO USUÁRIO (Simplificado sem o campo removido)
    public function registerNewUser(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'is_admin' => 'required|boolean',
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(['message' => 'Usuário cadastrado com sucesso.', 'user' => $newUser], 201);
    }

// 3. EDITAR USUÁRIO (Alterar dados de terceiros e alternar a flag is_admin)
    public function updateOtherUser(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $targetUser = User::find($id);
        if (!$targetUser) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        // Impede que o admin logado remova o seu próprio privilégio acidentalmente
        if ($targetUser->id === $request->user()->id && $request->is_admin == false) {
            return response()->json(['message' => 'Você não pode revogar seu próprio privilégio de administrador.'], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $targetUser->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $targetUser->id,
            'is_admin' => 'required|boolean',
        ]);

        $targetUser->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(['message' => 'Usuário atualizado com sucesso.', 'user' => $targetUser]);
    }

// 4. RESETAR SENHA PARA O PADRÃO (senha@321)
    public function resetPassword(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $targetUser = User::find($id);
        if (!$targetUser) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $targetUser->password = Hash::make('senha@321');
        $targetUser->save();

        return response()->json(['message' => 'Senha resetada com sucesso para o padrão "senha@321".']);
    }

    // Método para deslogar e revogar o token atual
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso e token revogado.'
        ]);
    }
}
