<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function index(Request $request, Response $response){
        $address = $request->ip();
        return response(['address'=>$address])
                ->header('Content-Type', 'application/json');
    }

    // registration action
    public function register(RegisterRequest $request){
        try{
            $user = User::create([
                        'name'=>$request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);

            $token = $user->createToken('email')->plainTextToken;
            $data = ['user'=>$user , 'token'=>$token];
            
            return $this->success($data,'Successfully registered');
        }catch(\Illuminate\Validation\ValidationException $e){
            throw $e;
        }catch (\Exception $e){
            \Log::error($e->getMessage());
            return $this->error('Server error occurred', 500);
        }
    }

    // login action
     public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('email')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token
        ];

        return $this->success($data,'Successfully logged in', 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
         return $this->success([], 'Successfully logged out', 200);
    }
    
}
