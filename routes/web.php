<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

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
// Auth
Route::get("/register", [AuthController::class, "indexRegister"])->name("register.form");
Route::get("/login", [AuthController::class, "indexLogin"])->name("login.form");

Route::post("/login", [AuthController::class, "login"])->name("login");
Route::post("/register", [AuthController::class, "register"])->name("register");
Route::get("/logout", [AuthController::class, "logout"])->name("logout");

Route::group(["middleware" => "auth"], function () {
    Route::get("/", [IndexController::class, "index"])->name("index");
});


