<?php

namespace App\Services;

use App\Models\User;
use App\Traits\MediaServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use MediaServiceTrait;

    public function create(Request $request): User
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
        ]);
    }
}
