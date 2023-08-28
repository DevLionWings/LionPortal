<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\MyticketController;
use App\Http\Controllers\AbsensipayrollController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\KaryawanController;

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

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/', [DashboardController::class,'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login-page');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* Menu Attendance */
Route::get('/absensi', [AbsensiController::class,'absensi'])->name('absensi');
Route::get('/absensi/get', [AbsensiController::class,'getAbsensi'])->name('get-absensi');

/* Menu Tiket */
Route::get('/tiket', [TiketController::class,'tiket'])->name('tiket');
Route::get('/tiket/get', [TiketController::class,'tiketList'])->name('get-tiket');
Route::get('/tiket/filter/get', [TiketController::class,'tiketFilter'])->name('filter-tiket');
Route::post('/addtiket', [TiketController::class,'addTiket'])->name('add-tiket');
Route::get('/addform', [TiketController::class,'addForm'])->name('add.form');
Route::post('/updatetiket', [TiketController::class,'updateTiket'])->name('update-tiket');
Route::post('/closetiket', [TiketController::class,'closedTiket'])->name('close-tiket');

Route::get('/mytiket', [MyticketController::class,'myTiket'])->name('mytiket');
Route::get('/mytiket/get', [MyticketController::class,'myTiketList'])->name('my-tiket');
Route::post('/closemytiket', [MyticketController::class,'closedTiket'])->name('close-mytiket');

// Route::get('/get/comment', [CommentController::class,'displayComment'])->name('comment-get');
Route::post('/add/comment', [CommentController::class,'addComment'])->name('comment-add');
Route::post('/get/comment', [CommentController::class,'listComment'])->name('get-comment');

/* Upload */
// Route::post('/download', [TiketController::class,'downloadFile'])->name('download.file');
Route::post('/download/file', [UploadController::class,'download'])->name('download-file');

/* Email */ 
Route::get('send-mail', [MailController::class, 'index'])->name('email');

/* Chart-Today */ 
Route::get('get/stattoday', [ChartController::class, 'getStatToday'])->name('get.stat');
Route::get('get/month', [ChartController::class, 'getDataTicketingMonth'])->name('get.month');
Route::get('get/year', [ChartController::class, 'getDataTicketingYear'])->name('get.year');

/* Menu HRIS */
/* Attendance Payroll */
Route::get('/absensipayroll', [AbsensipayrollController::class,'absenpayroll'])->name('absensipayroll');
Route::get('/get/absensipayroll', [AbsensipayrollController::class,'getAbsenPerkas'])->name('get-absensipayroll');
Route::get('/filter/absensipayroll', [AbsensipayrollController::class,'filterAbsenPerkas'])->name('filter-absensipayroll');
Route::post('/update/shift', [AbsensipayrollController::class,'updateShift'])->name('update-shift');
Route::post('/update/shiftbulk', [AbsensipayrollController::class,'updateShiftBulk'])->name('update-shiftbulk');
Route::get('/shift', [AbsensipayrollController::class,'getShift'])->name('get-shift');

/* Print Kwitansi */
Route::get('/kwitansi', [KwitansiController::class,'kwitansi'])->name('kwitansi');
Route::get('/print', [KwitansiController::class,'print'])->name('print-kwitansi');
Route::get('/kwitansi/list', [KwitansiController::class,'getList'])->name('list-kwitansi');
Route::post('/simulasi', [KwitansiController::class,'getSimulasi'])->name('simulasi-kwitansi');
Route::post('/insert', [KwitansiController::class,'temporer'])->name('insert-kwitansi');
Route::post('/delete', [KwitansiController::class,'delete'])->name('delete-kwitansi');

Route::get('/kwitansi/cuti', [CutiController::class,'index'])->name('kwitansi-cuti');
Route::post('/simulasi/cuti', [CutiController::class,'getSimulasi'])->name('simulasi-cuti');
Route::get('/print/cuti', [CutiController::class,'print'])->name('print-cuti');

Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan');
Route::get('/karyawan/list', [KaryawanController::class,'dataList'])->name('karyawan-list');
Route::post('/karyawan/delete', [KaryawanController::class,'delete'])->name('karyawan-delete');
Route::post('/karyawan/insert', [KaryawanController::class,'insert'])->name('karyawan-insert');
Route::post('/karyawan/update', [KaryawanController::class,'update'])->name('karyawan-update');

/* Master Data */
// Master Counter //
Route::get('/counter', [CounterController::class,'index'])->name('counter');
Route::get('/counter/list', [CounterController::class,'dataList'])->name('counter-list');
Route::post('/counter/delete', [CounterController::class,'delete'])->name('counter-delete');
Route::post('/counter/insert', [CounterController::class,'insert'])->name('counter-insert');
// Master Category //
Route::get('/category', [CategoryController::class,'index'])->name('category');
Route::get('/category/list', [CategoryController::class,'dataList'])->name('category-list');
Route::post('/category/delete', [CategoryController::class,'delete'])->name('category-delete');
Route::post('/category/insert', [CategoryController::class,'insert'])->name('category-insert');
// Master User //
Route::get('/user', [UserController::class,'index'])->name('user');
Route::get('/user/list', [UserController::class,'dataList'])->name('user-list');
Route::post('/user/delete', [UserController::class,'delete'])->name('user-delete');
Route::post('/user/insert', [UserController::class,'insert'])->name('user-insert');
Route::post('/user/update', [UserController::class,'update'])->name('user-update');
// Master Departement //
Route::get('/department', [DepartmentController::class,'index'])->name('department');
Route::get('/department/list', [DepartmentController::class,'dataList'])->name('department-list');
Route::post('/department/delete', [DepartmentController::class,'delete'])->name('department-delete');
Route::post('/department/insert', [DepartmentController::class,'insert'])->name('department-insert');
// Master Role //
Route::get('/role', [RoleController::class,'index'])->name('role');
Route::get('/role/list', [RoleController::class,'dataList'])->name('role-list');
Route::post('/role/delete', [RoleController::class,'delete'])->name('role-delete');
Route::post('/role/insert', [RoleController::class,'insert'])->name('role-insert');
// Master Plantation // 
Route::get('/plant', [PlantController::class,'index'])->name('plant');
Route::get('/plant/list', [PlantController::class,'dataList'])->name('plant-list');
Route::post('/plant/delete', [PlantController::class,'delete'])->name('plant-delete');
Route::post('/plant/insert', [PlantController::class,'insert'])->name('plant-insert');