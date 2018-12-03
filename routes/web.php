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

Route::get('/', function () {
    $sql = new SQL();
    $sql->run();
    return redirect('home');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('home');
});

Route::get('login','Auth\LoginController@getLogin')->name('login');
Route::post('login','Auth\LoginController@postLogin')->name('login');

Route::get('/home', 'HomeController@index')->name('home');


//trang chủ
//imeiserver
//hiển thị danh sách dịch vụ, tiền tệ
Route::get('imei', 'IMEIController@imei');
//hiển thị dịch vụ theo id
Route::get('imei/{id}', 'IMEIController@show');
//xóa dịch vụ imei
Route::get('imeidelete/{id}', 'IMEIController@delete');
//cập nhập giá nhập, giá bản lẻ tự động
Route::post('imei/{id}', 'IMEIController@edit');
//cập nhập nhà cung cấp
Route::post('updatesupplier/{id}', 'IMEIController@updatesupplier');
//cập nhập status
Route::get('imei/{squirrel}/{any}', 'IMEIController@status');
//xuất giá
Route::get('imeiexport', 'Export@exportimei');
Route::get('serverexport', 'Export@exportserver');

//chiết khấu phần trăm từng user
Route::get('clientgroup', 'ClientController@index');
Route::get('clientgroup/{id}', 'ClientController@show');
Route::post('clientgroup/{id}', 'ClientController@edit');
Route::get('clientgroup/{squirrel}/{any}', 'ClientController@status');
Route::get('clientgroupdelete/{id}', 'ClientController@delete');

//thêm sửa xóa nhà cung cấp, phí giao dịch, tỉ giá
Route::get('supplier', 'SupplierController@index');
Route::post('supplier/{id}', 'SupplierController@edit');
Route::post('addsupplier', 'SupplierController@add');
Route::get('supplier/{id}', 'SupplierController@show');
Route::get('supplierdelete/{id}', 'SupplierController@delete');


//thêm sửa xóa currencie
Route::get('currencie', 'CurrencieController@index');
Route::post('currencie/{id}', 'CurrencieController@edit');
Route::post('addcurrencie', 'CurrencieController@add');
Route::get('currencie/{id}', 'CurrencieController@show');
Route::get('currencie/{squirrel}/{any}', 'CurrencieController@status');
Route::get('defaultcurrency/{squirrel}/{any}', 'CurrencieController@defaultcurrency');

//ServerserviceController
Route::get('serverservice', 'ServerserviceController@index');
Route::get('serverservice/{id}', 'ServerserviceController@show');
Route::post('updatesupplierserver/{id}', 'ServerserviceController@updatesupplier');
Route::post('serverservice/{id}', 'ServerserviceController@edit');
Route::post('serverservicewise/{id}', 'ServerserviceController@editwise');
Route::get('service/{squirrel}/{any}', 'ServerserviceController@status');
Route::get('serverdelete/{id}', 'ServerserviceController@delete');

Route::get('role', 'Auth\LoginController@role');
Route::get('role/{squirrel}/{any}', 'Auth\LoginController@status');
//Invoice Report
Route::get('invoicereport', 'InvoiceReportController@index');
//Utility
Route::get('reprice', 'Utility@Request');
//Service Report
Route::get('serverorder', 'ServerorderController@index');
Route::get('imeiorder', 'ImeiorderController@index');

Route::get('members', 'MemberController@index');

Route::get('mail', 'MailController@index');

Route::get('profitreport', 'ProfitController@index');
Route::get('cronjobprofit', 'ProfitCronController@runcron');
Route::get('runcronrange', 'ProfitCronController@runcronrange');
Route::post('reloadprofit', 'ProfitCronController@reloadprofit');