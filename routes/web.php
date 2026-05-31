<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignDetectorController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Sign Detector
    Route::get('/sign-detector', [SignDetectorController::class, 'index'])->name('sign-detector');
    Route::post('/api/sign-explain', function(\Illuminate\Http\Request $request) {
        $letter = $request->letter;
        return response()->json(['explanation' => "The ASL sign for '$letter': Practice forming this handshape clearly in front of your webcam. Consistent hand positioning improves recognition accuracy significantly."]);
    })->name('sign.explain');

    // Admin only
    Route::middleware(['App\Http\Middleware\AdminMiddleware'])->group(function () {
        Route::resource('students', StudentController::class)->except(['show']);
    });

    // Both roles
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});

require __DIR__.'/auth.php';