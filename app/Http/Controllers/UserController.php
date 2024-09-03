<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll()
    {
        $array = ['error' => ''];
        try {
            $user = User::all();
            $array['users'] = $user;
        } catch (\Exception $e) {
            $array['error'] = $e->getMessage();
        }
        return response()->json($array);
    }
}

