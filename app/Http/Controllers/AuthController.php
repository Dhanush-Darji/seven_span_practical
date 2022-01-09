<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all() , [
                'email' => 'required|email',
                'password' => 'required'
            ]);
    
            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors(),
                    'type' => 'danger'
                ], 500);
            }

            $user= User::where('email',$request->email)->first();

            if($user) {
                if(Hash::check($request->password,$user->password)) {
                    $token = $user->createToken('token')->plainTextToken;

                    return response([
                        'user' => $user,
                        'token' => $token
                    ],200);
                }else {
                    return response ([
                        'errors' => 'Please check your email and password',
                    ],500);
                }
            }else {
                return response([
                    'errors' => 'Admin not found',
                ],500);
            }
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response([
                'message' => 'logout successfully.'
            ],200);

        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
            ], 500);
        }
    }
}
