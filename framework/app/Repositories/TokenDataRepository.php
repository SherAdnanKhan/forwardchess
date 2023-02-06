<?php

namespace App\Repositories;

use App\Models\User\User;
use Illuminate\Http\Request;

class TokenDataRepository
{
    public function getToken(Request $request)
    {
        $request->validate([
            'email'                => 'required|string|email',
            'password'             => 'required|string|min:8'
        ]);

        $user = User::where([
            'email'    => $request->email,
            'password' => md5($request->password)
        ])->firstOrFail();
        
        $token = $user->createToken('Forwardchess Password Grant Client')->accessToken;


        return response()->json(['token' => $token],200);
    }
}
