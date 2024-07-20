<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/cfdi-list', [ApiController::class, 'getCfdiList']);
Route::post('/cfdi-cancel', [ApiController::class, 'cancelCdfi'])->name('api.cancelCdfi');
Route::post('/cfdu-sendEmail', [ApiController::class, 'sendEmail'])->name('api.sendEmail');

