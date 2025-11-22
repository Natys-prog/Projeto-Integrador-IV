<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpiController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\AlertaController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/info', [InfoController::class, 'show'])->name('info');
Route::get('/init-db', [HomeController::class, 'initDatabase'])->name('init-db');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// EPI routes
Route::get('/epi', [EpiController::class, 'index'])->name('epi.index');
Route::get('/funcionarios', [FuncionarioController::class, 'index'])->name('funcionarios.index');
Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
