<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttendanceController;
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

Route::get('/attendance',[AttendanceController::class, 'index']);  


Route::get('/', function () {
    return redirect('/login');
});    

// 管理者ログイン画面表示
Route::get('/admin/login', function () {
    return view('auth.admin.login');
});
//ログイン処理管理者
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// 管理者・勤怠一覧画面
Route::get('/admin/attendance/list', function () {
    return view('admin.attendance.list');
});

//管理者ログアウト
Route::post('/admin/logout', function () {
    return redirect('/admin/login');
});

//出勤処理 
Route::post('/attendance/start', [AttendanceController::class, 'start']);

//休憩処理
Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);

//休憩戻り処理
Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);

//退勤処理
Route::post('/attendance/end', [AttendanceController::class, 'end']);

//一覧処理
Route::get('/attendance/list', [AttendanceController::class, 'list']);

//勤怠詳細
Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);

//勤怠修正
Route::post('/attendance/update/{id}', [AttendanceController::class, 'update']);

//修正申請
Route::post('/attendance/update/{id}', [AttendanceController::class, 'update']);