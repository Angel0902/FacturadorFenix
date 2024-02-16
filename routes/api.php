<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout']);
Route::post('refresh', [AuthController::class,'refresh']);
Route::post('me', [AuthController::class,'me']);

Route::post('invoices/send',[InvoiceController::class,'send'])->middleware('auth:api');
Route::post('invoices/xml',[InvoiceController::class,'xml'])->middleware('auth:api');
Route::post('invoices/pdf',[InvoiceController::class,'pdf'])->middleware('auth:api');

Route::post('notes/send',[NoteController::class,'send'])->middleware('auth:api');
Route::post('notes/xml',[NoteController::class,'xml'])->middleware('auth:api');
Route::post('notes/pdf',[NoteController::class,'pdf'])->middleware('auth:api');