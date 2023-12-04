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
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MeetingroomController;
use App\Http\Controllers\ViewroomController;
use App\Http\Controllers\TransportController;


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
Route::post('/edittiket', [TiketController::class,'editTiket'])->name('edit-tiket');
Route::post('/closetiket', [TiketController::class,'closedTiket'])->name('close-tiket');

Route::get('/mytiket', [MyticketController::class,'myTiket'])->name('mytiket');
Route::get('/mytiket/get', [MyticketController::class,'myTiketList'])->name('my-tiket');
Route::get('/mytiket/filter/get', [MyticketController::class,'myTiketFilter'])->name('my-filter-tiket');
Route::post('/closemytiket', [MyticketController::class,'closedTiket'])->name('close-mytiket');

// Route::get('/get/comment', [CommentController::class,'displayComment'])->name('comment-get');
Route::post('/add/comment', [CommentController::class,'addComment'])->name('comment-add');
Route::post('/get/comment', [CommentController::class,'listComment'])->name('get-comment');
Route::post('/get/count/comment', [CommentController::class,'countComment'])->name('count-comment');

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
/* Master Karyawan */
Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan');
Route::get('/karyawan/list', [KaryawanController::class,'dataList'])->name('karyawan-list');
Route::post('/karyawan/delete', [KaryawanController::class,'delete'])->name('karyawan-delete');
Route::post('/karyawan/insert', [KaryawanController::class,'insert'])->name('karyawan-insert');
Route::post('/karyawan/update', [KaryawanController::class,'update'])->name('karyawan-update');
/* History Kwintansi */
Route::get('/history', [HistoryController::class,'index'])->name('history');
Route::get('/history/list', [HistoryController::class,'dataList'])->name('history-list');
Route::post('/history/delete', [HistoryController::class,'delete'])->name('history-delete');
Route::post('/history/reprint', [HistoryController::class,'reprint'])->name('history-reprint');

/* Master Data */
// Master Counter //
Route::get('/counter', [CounterController::class,'index'])->name('counter');
Route::get('/counter/list', [CounterController::class,'dataList'])->name('counter-list');
Route::post('/counter/delete', [CounterController::class,'delete'])->name('counter-delete');
Route::post('/counter/insert', [CounterController::class,'insert'])->name('counter-insert');
// Master Category //
Route::get('/category', [CategoryController::class,'index'])->name('category');
Route::get('/category/list', [CategoryController::class,'dataList'])->name('category-list');
Route::get('/get/category', [CategoryController::class,'categoryFilter'])->name('category-get');
Route::post('/category/delete', [CategoryController::class,'delete'])->name('category-delete');
Route::post('/category/insert', [CategoryController::class,'insert'])->name('category-insert');
// Master User //
Route::get('/user', [UserController::class,'index'])->name('user');
Route::get('/user/list', [UserController::class,'dataList'])->name('user-list');
Route::get('/login/list', [UserController::class,'dataListLogin'])->name('login-list');
Route::post('/user/delete', [UserController::class,'delete'])->name('user-delete');
Route::post('/user/insert', [UserController::class,'insert'])->name('user-insert');
Route::post('/user/update', [UserController::class,'update'])->name('user-update');
Route::post('/login/update', [UserController::class,'updateLogin'])->name('login-update');
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

/* Menu Room Meeting */
// View
Route::get('/view', [ViewroomController::class,'viewRoom'])->name('view-room');
Route::get('/view/list', [ViewroomController::class,'listRoom'])->name('list-room');
// Admin
Route::get('/admin/index', [MeetingroomController::class,'adminIndex'])->name('admin-index');
Route::get('/room/get', [MeetingroomController::class,'roomList'])->name('room-list');
Route::get('/count/get', [MeetingroomController::class,'countRoom'])->name('get-count');
Route::post('/add/room', [MeetingroomController::class,'addRoom'])->name('add-room');
Route::post('/edit/room', [MeetingroomController::class,'editRoom'])->name('edit-room');
// User
Route::get('/user/index', [MeetingroomController::class,'userIndex'])->name('user-index');
Route::get('/room/user/get', [MeetingroomController::class,'roomListUser'])->name('room-list-user');
Route::post('/book/room', [MeetingroomController::class,'bookRoom'])->name('book-room');
Route::get('/get/room', [MeetingroomController::class,'getRoom'])->name('get-room');
Route::post('/cancel/room', [MeetingroomController::class,'cancelRoom'])->name('cancel-room');
Route::post('/avail/room', [MeetingroomController::class,'availRoom'])->name('avail-room');

/* Transport */
Route::post('/send/transport', [TransportController::class,'sendTransport'])->name('send-transport');
Route::post('/approve/transport', [TransportController::class,'approveTransport'])->name('approve-transport');
Route::post('/reject/transport', [TransportController::class,'rejectTransport'])->name('reject-transport');
Route::post('/transported/transport', [TransportController::class,'transportedTransport'])->name('transported-transport');
Route::post('/get/historytrans', [TransportController::class,'listTransport'])->name('history-transport');
Route::get('/get/transportid/approve', [TransportController::class,'approveOption'])->name('approve-transportid');
Route::get('/get/transportid/transported', [TransportController::class,'transportOption'])->name('transported-transportid');
Route::get('/get/transportid/create', [TransportController::class,'transportOptionCreate'])->name('create-transportid');