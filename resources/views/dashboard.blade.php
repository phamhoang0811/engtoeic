@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-2 fw-bold text-uppercase">Lộ trình chinh phục TOEIC</h2>
    <p class="text-center text-muted mb-5">Chọn mốc mục tiêu điểm số phù hợp với năng lực của bạn</p>
    
    <div class="row justify-content-center g-4">
        <!-- Ô vuông Mục tiêu 450+ -->
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100 shadow-sm border border-success-subtle border-2 text-center rounded-4 overflow-hidden">
                <div class="bg-success text-white py-4">
                    <h3 class="fw-bold mb-0">TOEIC 450+</h3>
                    <small>Nền tảng cơ bản</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <p class="text-muted small">Dành cho người mới bắt đầu, tập trung xây dựng gốc từ vựng và ngữ pháp cốt lõi.</p>
                    <a href="/score-detail?score=450" class="btn btn-outline-success fw-bold py-2 rounded-pill mt-3">Vào học 🚀</a>
                </div>
            </div>
        </div>

        <!-- Ô vuông Mục tiêu 650+ -->
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100 shadow-sm border border-warning-subtle border-2 text-center rounded-4 overflow-hidden">
                <div class="bg-warning text-dark py-4">
                    <h3 class="fw-bold mb-0">TOEIC 650+</h3>
                    <small>Mục tiêu trung cấp</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <p class="text-muted small">Mở rộng vốn từ vựng chuyên sâu công sở, luyện phản xạ nghe và cấu trúc ngữ pháp nâng cao.</p>
                    <a href="/score-detail?score=650" class="btn btn-outline-warning text-dark fw-bold py-2 rounded-pill mt-3">Vào học 🚀</a>
                </div>
            </div>
        </div>

        <!-- Ô vuông Mục tiêu 990+ -->
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100 shadow-sm border border-danger-subtle border-2 text-center rounded-4 overflow-hidden">
                <div class="bg-danger text-white py-4">
                    <h3 class="fw-bold mb-0">TOEIC 990+</h3>
                    <small>Chinh phục đỉnh cao</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <p class="text-muted small">Luyện tập các dạng bài khó, từ vựng học thuật và bứt phá điểm số tối đa tuyệt đối.</p>
                    <a href="/score-detail?score=990" class="btn btn-outline-danger fw-bold py-2 rounded-pill mt-3">Vào học 🚀</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6">
            <a href="/translate" class="text-decoration-none">
                <div class="card h-100 shadow-sm border border-primary-subtle border-2 text-center rounded-4 overflow-hidden hover-card bg-white">
                    <div class="bg-primary text-white py-4">
                        <h3 class="fw-bold mb-0">Phiên dịch / Tra cứu</h3>
                        <small>Anh ↔ Việt</small>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <p class="text-muted small">Dịch từ, câu, đoạn văn và tra nghĩa từ vựng TOEIC.</p>
                        <span class="btn btn-outline-primary fw-bold py-2 rounded-pill mt-3">Mở công cụ 🔎</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection