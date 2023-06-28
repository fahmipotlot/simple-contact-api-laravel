<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereRaw('LOWER(email) = ?', strtolower(request()->email))->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }

        $authenticated = \Auth::attempt([
            'email' => $user->email,
            'password' => request()->password
        ]);

        if ($authenticated) {
            $user->tokens()->delete();
            $user = \Auth::user();
            $user->token = $user->createToken('auth_token')->plainTextToken;;

            return $user;
        } else {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt(request()->password),
            'name' => $request->name
        ]);

        $authenticated = \Auth::attempt([
            'email' => $user->email,
            'password' => request()->password
        ]);

        if ($authenticated) {
            $user = \Auth::user();
            $user->token = $user->createToken('auth_token')->plainTextToken;;

            return $user;
        } else {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }
    }

    public function profile()
    {
        return \Auth::user();
    }
}
