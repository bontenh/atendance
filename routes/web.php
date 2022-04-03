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


//top画面のルート
Route::get('/', function(){return redirect()->action('TopController@top');});
Route::get('/top', 'TopController@top');
Route::get('/register', 'TopController@move_register');
Route::get('/login', 'TopController@move_login');

//管理者の登録とログイン処理
Route::post('/register/do_admin_register','TopController@do_admin_register');
Route::post('/login/do_admin_login','TopController@do_admin_login');

//出席利用画面のルート
Route::get('/attendance','AttendanceController@move_attendance');
Route::get('/attendance/move_doattendance','AttendanceController@move_doattendance');

//出席の入室と退室の処理
Route::post('/attendance/in_attendance','AttendanceController@in_attendance');
Route::post('/attendance/out_attendance','AttendanceController@out_attendance');

//ログアウト
Route::get('/logout','TopController@logout');

//管理者メニュー
Route::get('/manegement/manege_menu','ManegementController@move_manege_menu');

//利用者管理画面のルート
Route::get('/manegement/manege_user','ManegementController@move_manege_user');
Route::get('/manegement/manege_new_user','ManegementController@move_manege_new_user');
Route::get('/manegement/change_manege_user','ManegementController@move_change_manege_user');
Route::get('/manegement/softdelete_manege_user','ManegementController@move_softdelete_manege_user');


//利用者情報の、変更、と、登録を削除、と、新規登録、と、完全に削除、と、再登録
Route::post('/manegement/change_manege_user','ManegementController@change_manege_user');
Route::post('/manegement/softdelete_manege_user','ManegementController@softdelete_manege_user');
Route::post('/manegement/manege_new_user','ManegementController@manege_new_user');
Route::post('/manegement/manege_delete_user','ManegementController@manege_delete_user');
Route::post('/manegement/manege_re_register_user','ManegementController@manege_re_register_user');

//実績管理画面のルート
Route::get('/manegement/manege_performance','PerformanceController@move_manege_performance');
Route::get('/manegement/change_perform_user','PerformanceController@move_change_perform_user');
Route::get('/manegement/perform_new_user','PerformanceController@move_perform_new_user');
Route::get('/manegement/output_excel','PerformanceController@move_output_excel');

//実績記録の変更、と、登録を削除と実績記録の作成
Route::post('/manegement/change_perform_user','PerformanceController@change_perform_user');
Route::post('/manegement/delete_perform_user','PerformanceController@delete_perform_user');
Route::post('/manegement/perform_new_user','PerformanceController@perform_new_user');

//excel出力
Route::get('manegement/output_perform_excel','PerformanceController@export');
Route::get('manegement/output_school_perform_excel','PerformanceController@school_export');
Route::get('manegement/output_select_excel','PerformanceController@move_output_select_excel');
Route::post('manegement/output_select_excel','PerformanceController@output_select_excel');