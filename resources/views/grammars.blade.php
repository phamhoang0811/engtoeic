@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="position-relative d-flex align-items-center justify-content-center mb-4">
        <a href="/score-detail?score={{ $score }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold position-absolute start-0">⬅ Quay lại</a>
        <h3 class="fw-bold text-uppercase text-primary mb-0">Lý Thuyết Ngữ Pháp - Mục Tiêu {{ $score }}</h3>
    </div>
    
    <p class="text-center text-muted mb-5">Tổng hợp các chuyên đề ngữ pháp trọng điểm</p>

    <div class="row">
        @forelse($grammars as $grammar)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="card-title text-primary fw-bold mb-3">{{ $grammar->title }}</h4>
                        <p class="text-muted">{{ $grammar->description }}</p>
                    </div>
                    <a href="/grammar/{{ $grammar->id }}" class="btn btn-outline-primary btn-sm fw-bold rounded-pill px-4 align-self-start mt-3">Xem chi tiết ➡</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Chưa có chuyên đề ngữ pháp nào cho mốc điểm này. Bạn hãy chạy Seeder để thêm dữ liệu nhé!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection