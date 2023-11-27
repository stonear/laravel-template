<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'ASC')
            ->paginate();

        return view('users.index', compact('users'));
    }
}
