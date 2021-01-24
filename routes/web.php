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

Route::get('/init', function () {
    $sql = new SQL();
    $sql->run();
    return redirect('/');
});

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('post-login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'HomeController@index');
Route::get('finance', 'HomeController@finance')->middleware('admin')->name('financedashboard');


//trang chủ
//imeiserver
//hiển thị danh sách dịch vụ, tiền tệ
Route::get('imei', 'IMEIController@index');
Route::get('preLoadimei', 'IMEIController@runStart');
//hiển thị dịch vụ theo id
Route::get('imei/{id}', 'IMEIController@edit');
Route::post('imei/{id}/update', 'IMEIController@update')->name('admin.imei.update');
//xóa dịch vụ imei
Route::get('imeidelete/{id}', 'IMEIController@delete');
//cập nhập giá nhập, giá bản lẻ tự động
Route::post('imei/{id}', 'IMEIController@edit');
//cập nhập nhà cung cấp
Route::post('updatesupplier/{id}', 'IMEIController@updatesupplier');
//cập nhập status
Route::get('imei/{squirrel}/{any}', 'IMEIController@status');
//xuất giá
Route::get('imeiexport', 'Export@exportimei')->middleware('admin');
Route::post('imeiquickedit', 'Export@imeiquickedit');
Route::post('serverquickedit', 'Export@serverquickedit');
Route::get('serverexport', 'Export@exportserver')->middleware('admin');
//sales
Route::get('imeisales', 'Sales@salesimei');
Route::post('imeisales', 'Sales@updateimei');
Route::get('serversales', 'Sales@salesserver');
Route::post('serversales', 'Sales@updateserver');

//chiết khấu phần trăm từng user
Route::get('clientgroup', 'ClientController@index');
Route::get('clientgroup/{id}', 'ClientController@show');
Route::post('clientgroup/{id}', 'ClientController@edit');
Route::get('clientgroup/{squirrel}/{any}', 'ClientController@status');
Route::get('clientgroupdelete/{id}', 'ClientController@delete');

//thêm sửa xóa nhà cung cấp, phí giao dịch, tỉ giá
Route::get('supplier', 'SupplierController@index')->middleware('admin');
Route::post('supplier/{id}', 'SupplierController@edit')->middleware('admin');
Route::post('addsupplier', 'SupplierController@add')->middleware('admin');
Route::post('supplierquickedit', 'SupplierController@quickedit')->middleware('admin');
Route::get('supplier/{id}', 'SupplierController@show')->middleware('admin');
Route::get('supplierdelete/{id}', 'SupplierController@delete')->middleware('admin');


//thêm sửa xóa currencie
Route::get('currencie', 'CurrencieController@index')->middleware('admin');
Route::post('currencie/{id}', 'CurrencieController@edit');
Route::post('addcurrencie', 'CurrencieController@add');
Route::get('currencie/{id}', 'CurrencieController@show');
Route::get('currencie/{squirrel}/{any}', 'CurrencieController@status');
Route::get('defaultcurrency/{squirrel}/{any}', 'CurrencieController@defaultcurrency');

//ServerserviceController
Route::get('serverservice', 'ServerserviceController@index');
Route::get('preLoadservice', 'ServerserviceController@runStart');
Route::get('serverservice/{id}', 'ServerserviceController@show');
Route::post('updatesupplierserver/{id}', 'ServerserviceController@updatesupplier');
Route::post('serverservice/{id}', 'ServerserviceController@edit');
Route::post('serverservicewise/{id}', 'ServerserviceController@editwise');
Route::get('service/{squirrel}/{any}', 'ServerserviceController@status');
Route::get('serverdelete/{id}', 'ServerserviceController@delete');

Route::get('role', 'Auth\RoleController@role')->middleware('admin');
Route::get('role/{squirrel}/{any}', 'Auth\RoleController@status')->middleware('admin');
Route::post('supplier_access', 'Auth\RoleController@supplier_access')->middleware('admin');

//Invoice Report
Route::get('invoicereport', 'InvoiceReportController@index');
//Utility
Route::get('reprice', 'Utility@Request');
//Service Report
Route::get('serverorder', 'ServerorderController@index');
Route::get('serverorder/{id}', 'ServerorderController@show');
Route::post('serverorder/{id}', 'ServerorderController@edit');
Route::get('imeiorder', 'ImeiorderController@index');

Route::get('members', 'MemberController@index');

Route::get('mail', 'MailController@index');

Route::get('profitreport', 'ProfitController@index')->middleware('admin');
Route::get('cronjobprofit', 'ProfitCronController@runcron');
Route::get('runcronrange', 'ProfitCronController@runcronrange');
Route::post('reloadprofit', 'ProfitCronController@reloadprofit');
Route::POST('checkCreditSuppliers', 'CronApiController@checkCreditSuppliers');

Route::get('check-transaction', 'Payment\CheckTransaction@index');
Route::get('getbalance', 'CronApiController@getBalance');
