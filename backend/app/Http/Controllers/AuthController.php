<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index(Request $request, Response $response){
        $address = $request->ip();
        return response(['address'=>$address])
                ->header('Content-Type', 'application/json');
    }

    // registration action
    public function register(Request $request){
        $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]);

        $user = User::create([
                    'name'=>$request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

        $token = $user->createToken('email')->plainTextToken;
        $data = ['user'=>$user , 'token'=>$token];
         return response($data, 200)
                ->header('Content-Type', 'application/json');

        // return response()->json([
        //         'user' => $user,
        //         'token' => $token
        //     ], 201);
    }

    // login action
     public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('email')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token
        ];

        // return response()->json([
        //     'user' => $user,
        //     'token' => $token
        // ]);
        return response($data, 200)
                ->header('Content-Type', 'application/json');
    }
}
