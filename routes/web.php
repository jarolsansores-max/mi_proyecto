<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestConnectionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\AspiranteController;
use App\Http\Controllers\AlumnoController;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/test-db', TestConnectionController::class);

Route::get('/check-db', function () {
    try {
        DB::connection()->getPdo();
        return "¡Éxito! Conexión establecida con la base de datos: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Error: No se pudo conectar a la base de datos. " . $e->getMessage();
    }
});

// TEMPORARY DEBUG ROUTE - DELETE AFTER FIXING
Route::get('/debug-login', function () {
    $user = User::where('email', 'test@example.com')->first();

    if (!$user) {
        // User doesn't exist - create it right now
        $newUser = User::create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
        return response()->json([
            'status' => 'USER CREATED NOW',
            'id' => $newUser->id,
            'email' => $newUser->email,
            'password_check' => \Illuminate\Support\Facades\Hash::check('password', $newUser->password),
        ]);
    }

    $hashCheck = \Illuminate\Support\Facades\Hash::check('password', $user->password);

    return response()->json([
        'status' => 'USER EXISTS',
        'id' => $user->id,
        'email' => $user->email,
        'password_starts_with' => substr($user->password, 0, 20) . '...',
        'password_length' => strlen($user->password),
        'hash_check_password' => $hashCheck,
        'session_driver' => config('session.driver'),
        'db_connection' => config('database.default'),
        'app_env' => config('app.env'),
    ]);
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'users' => User::orderBy('created_at', 'desc')->get(),
        ]);
    })->name('dashboard');

    Route::get('/users/list', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/carreras/list', [CarreraController::class, 'index'])->name('carreras.index');
    Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');
    Route::put('/carreras/{carrera}', [CarreraController::class, 'update'])->name('carreras.update');
    Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])->name('carreras.destroy');

    Route::get('/aspirantes/list', [AspiranteController::class, 'index'])->name('aspirantes.index');
    Route::post('/aspirantes', [AspiranteController::class, 'store'])->name('aspirantes.store');
    Route::put('/aspirantes/{aspirante}', [AspiranteController::class, 'update'])->name('aspirantes.update');
    Route::delete('/aspirantes/{aspirante}', [AspiranteController::class, 'destroy'])->name('aspirantes.destroy');

    Route::get('/alumnos/list', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
    Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
});
