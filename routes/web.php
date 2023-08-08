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
Route::post('/simulasi', [KwitansiController::class,'getSimulasi'])->name('simulasi-kwitansi');
Route::get('/print', [KwitansiController::class,'print'])->name('print-kwitansi');

Route::get('/kwitansi/cuti', [CutiController::class,'index'])->name('kwitansi-cuti');
Route::post('/simulasi/cuti', [CutiController::class,'getSimulasi'])->name('simulasi-cuti');
Route::get('/print/cuti', [CutiController::class,'print'])->name('print-cuti');
