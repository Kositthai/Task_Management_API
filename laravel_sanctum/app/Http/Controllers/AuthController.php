<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses; 

    public function login() {
    return 'This is login';
    }


    public function register(StoreUserRequest $request) {

        $request->validated($request->all()); 

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email, 
            'password' => Hash::make($request->password)
        ]); 

        return $this->success([
            'user' => $user, 
            'token' => $user->createToken('Api token of' . $user->name)->plainTextToken ]);
    }  
    
    public function logout() {
        return response()->json('This is my logout');
    } 
}
