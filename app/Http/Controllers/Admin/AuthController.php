<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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

            $admin = Admin::where('email',$request->email)->first();

            if($admin) {
                if(Hash::check($request->password,$admin->password)) {
                    $token = $admin->createToken('token')->plainTextToken;

                    return response([
                        'admin' => $admin,
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
