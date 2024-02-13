<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    use HttpResponses; 


    public function login(Request $request) {

        try {

        $request ->validate([
            'email' => 'required|string|email:unique', 
            'password'=> 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if(!auth()->attempt($credentials)) 
        {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token')->plainTextToken;
     
        return response()->json([
            'accessToken' =>$tokenResult,
            'token_type' => 'Bearer',
            ]);

        } catch(ValidationException $exception){
            return response()->json(['error' => $exception ->errors()],401); #code 401 is unauthorized 
        }
    }


    public function register(StoreUserRequest $request) {

        try {

            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email, 
                'password' => Hash::make($request->password)
            ]); 

            return $this->success([
                'user' => $user, 
                'token' => $user->createToken('Api token of' . $user->name)->plainTextToken ]);

        }catch(ValidationException $e){

            return $this->error($e->getMessage(), $e->errors()); 
        }
    }  
    
    public function logout(Request $request) {
       
        $user = auth()->user();
      
        if(auth()->user()->tokens()->delete()){
        return response()->json(['message'=> 'successfully', 'user' => $user], 200);
        }else {
        return response()->json(['message'=> 'Something went wrong', 'user' => $user], 500);
        }
    }

}
