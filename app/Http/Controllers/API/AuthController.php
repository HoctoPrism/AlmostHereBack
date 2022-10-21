<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {

        $request->validate(
            [
                'lastname' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6'
            ],
            [
                'email.email' => 'Ce champ attend un donnée de type email',
                'email.required' => 'Ce champ est requis',
                'password.required' => 'Ce champ est requis',
                'password.string' => 'Ce champ attend un donnée de type text'
            ]
        );

        if (empty($request->roles)) {
            $request->roles = json_encode(["ROLE_USER"]);
        } else {
            json_encode($request->roles);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],
            [
                'email.email' => 'Ce champ attend un donnée de type email',
                'email.required' => 'Ce champ est requis',
                'password.required' => 'Ce champ est requis',
                'password.string' => 'Ce champ attend un donnée de type text',

            ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'L\'email ou le mot de passe n\'est pas bon',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'token' => $token,
        ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function currentUser()
    {
        return response()->json(Auth::user());
    }

}
