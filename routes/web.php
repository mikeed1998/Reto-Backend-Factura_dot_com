<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ApiController;


Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/cfdi-list', [ApiController::class, 'getCfdiList']);
Route::post('/cfdi-cancel', [ApiController::class, 'cancelCdfi'])->name('api.cancelCdfi');
Route::post('/cfdu-sendEmail', [ApiController::class, 'sendEmail'])->name('api.sendEmail');

