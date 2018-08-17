<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('imei');
});
Route::get('/logout', function () {
    Auth::logout();
    return redirect('home');
});

Auth::routes();
//trang chủ
Route::get('home', 'HomeController@index');

//imeiserver
//cập nhập dữ liệu data tạm
Route::get('bak', 'IMEIController@index');
Route::get('json', 'IMEIController@json');
//hiển thị danh sách dịch vụ, tiền tệ
Route::get('imei', 'IMEIController@imei');
//hiển thị dịch vụ theo id
Route::get('imei/{id}', 'IMEIController@show');
//cập nhập giá nhập, giá bản lẻ tự động
Route::post('imei/{id}', 'IMEIController@edit');

//chiết khấu phần trăm từng user
Route::get('chietkhau', 'ClientController@index');
Route::get('chietkhau/{id}', 'ClientController@show');
Route::post('pchietkhau/{id}', 'ClientController@edit');

//thêm sửa xóa nhà cung cấp, phí giao dịch, tỉ giá
Route::get('supplier', 'SupplierController@index');
Route::post('supplier/{id}', 'SupplierController@edit');
Route::get('supplier/{id}', 'SupplierController@show');

