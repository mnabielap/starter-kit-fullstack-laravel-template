<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'title' => 'User Management'
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'title' => 'Create New User'
        ]);
    }

    public function edit()
    {
        return view('users.edit', [
            'title' => 'Edit User'
        ]);
    }
}