<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/video-info', [DownloadController::class, 'getVideoInfo']);
Route::post('/download', [DownloadController::class, 'download']);
