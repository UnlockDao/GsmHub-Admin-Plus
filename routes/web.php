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

use App\Http\Controllers\SQL;
use Illuminate\Support\Facades\Auth;
Route::get('login','Auth\LoginController@getLogin')->name('login');
Route::post('login','Auth\LoginController@postLogin')->name('login');
Route::get('/', function () {
    $sql = new SQL();
    $sql->Admin();
    $sql->clientGroup();
    $sql->createSupplier();
    $sql->imeiPricing();
    $sql->currencyPricing();
    return redirect('imei');
});
Route::get('/logout', function () {
    Auth::logout();
    return redirect('imei');
});
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
Route::post('addsupplier', 'SupplierController@add');
Route::get('supplier/{id}', 'SupplierController@show');


//thêm sửa xóa currencie
Route::get('currencie', 'CurrencieController@index');
Route::post('currencie/{id}', 'CurrencieController@edit');
Route::post('addcurrencie', 'CurrencieController@add');
Route::get('currencie/{id}', 'CurrencieController@show');
Route::get('currencie/{squirrel}/{any}', 'CurrencieController@status');
Route::get('defaultcurrency/{squirrel}/{any}', 'CurrencieController@defaultcurrency');
