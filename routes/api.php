<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;

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
Route::post('register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::post('createcompte',[CompteController::class,'createCompte'])->name('createcompte')->middleware('auth:sanctum');
Route::get('searchcompte/{name}',[CompteController::class,'searchCompte'])->name('searchcompte');
// historique d'un compte
Route::get('historique/{numero_compte}',[CompteController::class,'compteHistorique'])->name('historique')->middleware('auth:sanctum');


// transaction
Route::post('createtransaction',[TransactionController::class,'createTransaction'])->name('createtransaction')->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
