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
    return redirect('imei');
});

Auth::routes();
//trang chủ
//imeiserver
//hiển thị danh sách dịch vụ, tiền tệ
Route::get('imei', 'IMEIController@imei');
//hiển thị dịch vụ theo id
Route::get('imei/{id}', 'IMEIController@show');
//cập nhập giá nhập, giá bản lẻ tự động
Route::post('imei/{id}', 'IMEIController@edit');
//cập nhập nhà cung cấp
Route::post('updatesupplier/{id}', 'IMEIController@updatesupplier');
//cập nhập status
Route::get('imei/{squirrel}/{any}', 'IMEIController@status');

//chiết khấu phần trăm từng user
Route::get('clientgroup', 'ClientController@index');
Route::get('clientgroup/{id}', 'ClientController@show');
Route::post('clientgroup/{id}', 'ClientController@edit');

//thêm sửa xóa nhà cung cấp, phí giao dịch, tỉ giá
Route::get('supplier', 'SupplierController@index');
Route::post('supplier/{id}', 'SupplierController@edit');

Route::get('supplier/{id}', 'SupplierController@show');

