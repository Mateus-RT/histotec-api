<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas Públicas
Route::post('/login', [AuthController::class, 'login']);

// Rotas Protegidas (Exigem Token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    // Rotas do próprio usuário logado
    Route::put('/me', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rotas de Controle Administrativo (Filtro Interno is_admin nos métodos)
    Route::get('/users', [AuthController::class, 'index']);               // Listar todos
    Route::post('/users', [AuthController::class, 'registerNewUser']);     // Criar novo
    Route::put('/users/{id}', [AuthController::class, 'updateOtherUser']); // Editar dados/privilégio
    Route::patch('/users/{id}/reset-password', [AuthController::class, 'resetPassword']); // Resetar senha
});
