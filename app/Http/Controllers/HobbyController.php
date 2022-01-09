<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HobbyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $hobbies = Hobby::get();
            return response ([
                'hobbies' => $hobbies,
            ],200);
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
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
                'hobby' => 'required|min:2',
            ]);
    
            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors(),
                    'type' => 'danger'
                ], 500);
            }

            $data = [
                'user_id' => $request->user()->id,
                'hobby' => $request->hobby,
            ];

            $hobby = Hobby::create($data);

            if($hobby) {
                return response ([
                    'message' => 'hobby creates successfully.',
                    'hobby' => $hobby,
                ],200);
            }else {
                return response ([
                    'message' => 'Hobby not created.'
                ],500);
            }
            
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
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
                'hobby' => 'required|min:2',
            ]);
    
            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors(),
                    'type' => 'danger'
                ], 500);
            }

            $data = [
                'user_id' => $request->user()->id,
                'hobby' => $request->hobby,
            ];

            $update = Hobby::where('id',$id)->update($data);

            if($update) {
                $hobby = Hobby::find($id);
                return response ([
                    'message' => 'hobby updated successfully.',
                    'hobby' => $hobby,
                ],200);
            }else {
                return response ([
                    'message' => 'Hobby not updated.'
                ],500);
            }
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
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
        try {
            $delete = Hobby::where('id',$id)->delete();
            if($delete) {
                return response([
                    'message' => 'Hobby deleted successfully.'
                ],200);
            }else {
                return response ([
                    'message' => 'Hobby not deleted.'
                ],500);
            }
        }catch(Exception $e) {
            return response([
                'errors' => [__('Please try again after some time.')],
                'type' => 'danger'
            ], 500);
        }
    }
}
