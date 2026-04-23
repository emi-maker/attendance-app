<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AttendanceRequestController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStaffController;
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

// 管理者・勤怠一覧画面
Route::get('/admin/attendance/list',[AttendanceController::class, 'adminlist']);

//ログイン画面
Route::get('/admin/login', function () {
    return view('auth.admin.login');
});

// ログイン処理
Route::post('/admin/login', [AdminAuthController::class, 'login']);

//出勤処理 
Route::post('/attendance/start', [AttendanceController::class, 'start']);

//休憩処理
Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);

//休憩戻り処理
Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);

//退勤処理
Route::post('/attendance/end', [AttendanceController::class, 'end']);

//一覧処理
Route::get('/attendance/list', [AttendanceController::class, 'userlist']);

//勤怠詳細
Route::middleware('auth')->group(function () {
    Route::get('/attendance/detail/date/{date}', [AttendanceController::class, 'detailByDate']);
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);
});    

//勤怠修正
Route::put('/attendance/update/{id}', [AttendanceController::class, 'update']);

Route::post('/attendance/store', [AttendanceController::class, 'store']);

//申請一覧
Route::middleware('check.role')->group(function () {
    Route::get('/stamp_correction_request/list', [AttendanceRequestController::class, 'index']);
});

Route::get('/admin/attendance/detail/{userId}/{date}',
[AdminAttendanceController::class, 'detail']);

Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'show']);


Route::get('/admin/staff/list', [AdminStaffController::class, 'list']);

Route::get('/admin/attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendance']);

Route::get('/stamp_correction_request/approve/{id}', [AttendanceRequestController::class, 'approve']);

Route::put('/stamp_correction_request/approve/{id}', [AttendanceRequestController::class, 'updateApprove']);

