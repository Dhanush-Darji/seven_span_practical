<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hobby;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::with('hobbies');
            
            if($request->hobby) {
                $user_ids = Hobby::where('hobby','like','%'.$request->hobby.'%')->get()->pluck('user_id');
                $users = $users->whereIn('id',$user_ids);
            }
            
            $users = $users->get();

            return response([
                'users' => $users
            ],200);
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all() , [
                'first_name' => 'required|alpha|min:2',
                'last_name' => 'required|alpha|min:2',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'image' => 'required|mimes:jpg,png',
                'status' => 'required|in:active,deactive'
            ]);
    
            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors(),
                ], 500);
            }

            if($request->image) {
                $image_name = time() . rand(100000,999999) . '.' . $request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('images',$request->image,$image_name);
            }


            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $image_name,
                'status' => $request->status,
            ];

            $user = User::create($data);

            if($user) {
                return response ([
                    'message' => 'User created successfully.',
                    'user' => $user,
                ],200);
            }else {
                return response ([
                    'message' => 'User not created'
                ],500);
            }
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all() , [
                'first_name' => 'required|alpha|min:2',
                'last_name' => 'required|alpha|min:2',
                'email' => 'required|email|unique:users,email,'.$id,
                'status' => 'required|in:active,deactive'
            ]);
    
            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors(),
                ], 500);
            }

            $user = User::find($id);

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'status' => $request->status,
            ];

            if($request->image) {
                $image_name = time() . rand(100000,999999) . '.' . $request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('images',$request->image,$image_name);

                // delete old image
                Storage::disk('public')->delete('images/'.$user->image);
                $data['image'] = $image_name;
            }

            $update = $user->update($data);

            if($update) {
                $user = User::find($id);
                return response ([
                    'user' => $user
                ],200);
            }else {
                return response ([
                    'message' => 'User not updated'
                ],500);
            }
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $user = User::find($id);

            // delete user image 
            Storage::disk('public')->delete('images/'.$user->image);

            $delete = $user->delete();

            if($delete) {
                return response ([
                    'message' => 'User delete successfully.'
                ],200);
            }else {
                return response ([
                    'message' => 'User not deleted.'
                ],500);
            }

        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
            ], 500);
        }
    }
}
