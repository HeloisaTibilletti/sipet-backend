<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissions
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Função 1 tem acesso total
        if ($user->id_funcao == 1) {
            return $next($request);
        }

        // Função 2 não pode adicionar, editar ou deletar
        if ($user->id_funcao == 2) {
            // Para rotas que permitem apenas visualização, você pode continuar
            if ($permission === 'view') {
                return $next($request);
            }

            // Para rotas de adicionar, editar e deletar, você retorna um erro
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        // Caso o usuário não tenha permissão, retorna acesso negado
        return response()->json(['error' => 'Acesso negado'], 403);
    }
}
