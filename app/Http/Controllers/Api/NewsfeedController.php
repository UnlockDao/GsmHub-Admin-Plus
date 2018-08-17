<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Newsfeed;
use App\Thanhvien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsfeedController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $newsfeed = new Newsfeed();
        $data = $request->input('picture');
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $image = base64_decode($data);
        $filename= uniqid(). '.png';
        $file = public_path('imagepost/'.$filename);
        $success = file_put_contents($file, $image);

        $newsfeed->imagenew = $filename;
        $newsfeed->user_id = $request->input('user_id');
        $newsfeed->thread = $request->input('thread');
        $newsfeed->content = $request->input('content');
        $newsfeed->save();

        return $newsfeed;

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

}
