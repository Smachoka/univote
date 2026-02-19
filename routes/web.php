<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Student;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false]); // Disable public registration; admin creates accounts

// Public homepage (accessible to everyone, logged in or not)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect based on role (for backward compatibility or additional routing)
Route::get('/dashboard-redirect', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard.redirect');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Elections CRUD
        Route::resource('elections', Admin\ElectionController::class);

        // Election status toggles
        Route::patch('elections/{election}/activate', [Admin\ElectionController::class, 'activate'])
            ->name('elections.activate');
        Route::patch('elections/{election}/close', [Admin\ElectionController::class, 'close'])
            ->name('elections.close');

        // Positions (nested under elections)
        Route::prefix('elections/{election}/positions')
            ->name('elections.positions.')
            ->group(function () {
                Route::get('/', [Admin\PositionController::class, 'index'])->name('index');
                Route::get('/create', [Admin\PositionController::class, 'create'])->name('create');
                Route::post('/', [Admin\PositionController::class, 'store'])->name('store');
                Route::delete('/{position}', [Admin\PositionController::class, 'destroy'])->name('destroy');
            });

        // Candidates (nested under positions)
        Route::prefix('elections/{election}/positions/{position}/candidates')
            ->name('elections.positions.candidates.')
            ->group(function () {
                Route::get('/create', [Admin\CandidateController::class, 'create'])->name('create');
                Route::post('/', [Admin\CandidateController::class, 'store'])->name('store');
                Route::get('/{candidate}', [Admin\CandidateController::class, 'show'])->name('show');
                Route::get('/{candidate}/edit', [Admin\CandidateController::class, 'edit'])->name('edit');
                Route::put('/{candidate}', [Admin\CandidateController::class, 'update'])->name('update');
                Route::patch('/{candidate}/approve', [Admin\CandidateController::class, 'approve'])->name('approve');
                Route::delete('/{candidate}', [Admin\CandidateController::class, 'destroy'])->name('destroy');
            });

        // Results
        Route::get('elections/{election}/results', [Admin\ResultController::class, 'show'])
            ->name('elections.results');
    });

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [Student\VoteController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/elections/{election}/vote', [Student\VoteController::class, 'show'])
            ->name('vote');

        Route::post('/elections/{election}/vote', [Student\VoteController::class, 'store'])
            ->name('vote.store');

        Route::get('/elections/{election}/confirmation', [Student\VoteController::class, 'confirmation'])
            ->name('confirmation');
    });