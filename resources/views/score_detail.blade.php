@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="position-relative d-flex align-items-center justify-content-center mb-4">
        <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold position-absolute start-0">⬅ Quay lại lộ trình</a>
        <h3 class="fw-bold text-uppercase text-primary mb-0">Mục tiêu: {{ $score }} TOEIC</h3>
    </div>
    
    <p class="text-center text-muted mb-5">Chọn nội dung bạn muốn luyện tập hôm nay</p>

    <div class="row justify-content-center g-4">
        <!-- 1. Từ vựng -->
        <div class="col-md-3 col-sm-6">
            <a href="/topics?score={{ $score }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 hover-card bg-white">
                    <div class="display-4 mb-3">📚</div>
                    <h5 class="fw-bold text-dark">Từ vựng</h5>
                    <p class="text-muted small mb-0">Học từ theo chủ đề, thẻ flashcard, hình ảnh, ví dụ.</p>
                </div>
            </a>
        </div>

        <!-- 2. Lý thuyết (Ngữ pháp) -->
        <div class="col-md-3 col-sm-6">
            <a href="/grammars?score={{ $score }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 hover-card bg-white">
                    <div class="display-4 mb-3">📖</div>
                    <h5 class="fw-bold text-dark">Lý thuyết</h5>
                    <p class="text-muted small mb-0">Tổng hợp cấu trúc ngữ pháp trọng điểm TOEIC.</p>
                </div>
            </a>
        </div>

        <!-- 3. Bài tập -->
        <div class="col-md-3 col-sm-6" id="exercises">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 hover-card bg-white">
                    <div class="display-4 mb-3">📝</div>
                    <h5 class="fw-bold text-dark">Bài tập</h5>
                    <p class="text-muted small mb-0">Luyện tập câu hỏi trắc nghiệm theo từng Part.</p>
                </div>
            </a>
        </div>

        <!-- 4. Luyện nghe -->
        <div class="col-md-3 col-sm-6" id="listening">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 hover-card bg-white">
                    <div class="display-4 mb-3">🎧</div>
                    <h5 class="fw-bold text-dark">Luyện nghe</h5>
                    <p class="text-muted small mb-0">Cải thiện kỹ năng nghe hiểu Part 1, 2, 3, 4.</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Thêm hiệu ứng hover nhẹ cho các ô vuông -->
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection