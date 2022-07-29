<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required'
        ]);
        $password = Hash::make($request->password);
        $register = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $password
        ]);

        if ($register) {
            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'data' => $register
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Fail!',
                'data' => ''
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (Hash::check($validated['password'], $user->password)) {
            $apiToken = base64_encode(str::random(40));
            $user->update([
                'api_token' => $apiToken
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login Success!',
                'data' => [
                    'user' => $user,
                    'api_token' => $apiToken
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Fail!',
                'data' => ''
            ]);
        }
    }
}
