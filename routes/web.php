<?php

use App\Http\Controllers\NotaController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*Route::get('/personas',[PersonaController::class,'index'])->name('personas.index');
Route::get('/personas/create',[PersonaController::class,'create'])->name('personas.create');
Route::post('personas',[PersonaController::class,'store'])->name('personas.store');
Route::get('personas/{persona}/edit',[PersonaController::class,'edit'])->name('personas.edit');
Route::put('personas/{persona}',[PersonaController::class,'update'])->name('personas.update');
Route::delete('personas/{persona}',[PersonaController::class,'destroy'])->name('personas.destroy');*/

Route::resource('users',UserController::class)->names('users');
Route::resource('ventas',VentaController::class)->names('ventas');
Route::get('/ventas/{venta}/html',[VentaController::class,'html'])->name('ventas.html');
Route::get('/ventas/{venta}/pdf',[VentaController::class,'pdf'])->name('ventas.pdf');
Route::get('/ventas/{venta}/xml',[VentaController::class,'xml'])->name('ventas.xml');
Route::get('/ventas/{venta}/cdr',[VentaController::class,'cdr'])->name('ventas.cdr');
Route::resource('organizacions',OrganizacionController::class)->names('organizacions');

Route::resource('notas',NotaController::class)->names('notas');
Route::get('/notas/{nota}/html',[NotaController::class,'html'])->name('notas.html');
Route::get('/notas/{nota}/pdf',[NotaController::class,'pdf'])->name('notas.pdf');
Route::get('/notas/{nota}/xml',[NotaController::class,'xml'])->name('notas.xml');
Route::get('/notas/{nota}/cdr',[NotaController::class,'cdr'])->name('notas.cdr');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
