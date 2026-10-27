<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WordController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslationController;

// ====================================================
// 1. NHÓM CÔNG KHAI (Chưa đăng nhập vẫn truy cập được)
// ====================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Route Đăng nhập Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ====================================================
// 2. NHÓM BẢO MẬT (Bắt buộc phải đăng nhập mới truy cập)
// ====================================================
Route::middleware('auth')->group(function () {
 // CRUD Chủ đề (Dành cho Admin)
    Route::get('/admin/topics', [TopicController::class, 'adminIndex']);
    Route::get('/admin/topics/create', [TopicController::class, 'create']);
    Route::post('/admin/topics', [TopicController::class, 'store']);
    Route::get('/admin/topics/{id}/edit', [TopicController::class, 'edit']);
    Route::put('/admin/topics/{id}', [TopicController::class, 'update']);
    Route::delete('/admin/topics/{id}', [TopicController::class, 'destroy']);

 
// CRUD Từ vựng (Admin)
    Route::get('/admin/words', [WordController::class, 'adminIndex']);
    Route::get('/admin/words/create', [WordController::class, 'create']);
    Route::post('/admin/words', [WordController::class, 'store']);
    Route::get('/admin/words/{id}/edit', [WordController::class, 'edit']);
    Route::put('/admin/words/{id}', [WordController::class, 'update']);
    Route::delete('/admin/words/{id}', [WordController::class, 'destroy']);
    // Trang chủ lộ trình
    Route::get('/', function () {
        return view('dashboard');
    });

    // Trang chọn danh mục con theo mốc điểm
    Route::get('/score-detail', function (Request $request) {
        $score = $request->query('score', 450);
        return view('score_detail', compact('score'));
    });

    // Ngữ pháp
    Route::get('/grammars', [GrammarController::class, 'index']);
    Route::get('/grammar/{id}', [GrammarController::class, 'show']);

    // Chủ đề & Từ vựng
    Route::get('/topics', [TopicController::class, 'index']);
    Route::get('/learn', [WordController::class, 'index']);
    Route::get('/translate', [TranslationController::class, 'index']);
    Route::post('/translate', [TranslationController::class, 'translate']);

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Trang Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});