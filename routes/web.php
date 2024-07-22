<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ApiController;


Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/cfdi-listar', [ApiController::class, 'getCfdiList']);
Route::post('/cfdi-cancelar', [ApiController::class, 'cancelCdfi'])->name('api.cancelCdfi');
Route::post('/cfdu-enviar-factura-email', [ApiController::class, 'sendEmail'])->name('api.sendEmail');

