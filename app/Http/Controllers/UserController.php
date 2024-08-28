<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela raca
            $user = User::all();

            // Armazena os registros no array de resposta
            $array['users'] = $user;
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = $e->getMessage();
        }

        return $array;
    }
}
