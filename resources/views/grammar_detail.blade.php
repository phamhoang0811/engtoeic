@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Nút quay lại danh sách ngữ pháp theo đúng mốc điểm -->
            <a href="/grammars?score={{ $grammar->target_score }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold mb-4">⬅ Quay lại danh sách</a>
            
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">
                <h2 class="text-primary fw-bold mb-3">{{ $grammar->title }}</h2>
                <p class="text-muted fst-italic lead fs-6">{{ $grammar->description }}</p>
                
                <hr class="my-4">
                
                <!-- Nội dung chi tiết bài học -->
                <div class="grammar-content text-dark lh-lg">
                    {!! nl2br(e($grammar->content)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection