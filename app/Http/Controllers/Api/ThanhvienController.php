<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Thanhvien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

class ThanhvienController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thanhvien= Thanhvien::get();
        return $thanhvien;
    }
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make( Input::only('email'),

            array(
                'email'  =>'unique:users',
            )
        );

        if ($validator->fails()) {

            return Thanhvien::where('email',$request->input('email'))->first();

        }else{

            $thanhvien = new Thanhvien();
            $thanhvien->email = $request->input('email');
            $thanhvien->save();
            return $thanhvien;

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
        $thanhvien = Thanhvien::findOrFail($id);
        return new ApiRecurce($thanhvien);
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
    public function update(Request $request)
    {
        $thanhvien = Thanhvien::find($request->input('user_id'));
        $thanhvien->name = $request->input('name');
        $thanhvien->password = $request->input('password');
        $thanhvien->email = $request->input('email');
        $thanhvien->save();

        return $thanhvien;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
