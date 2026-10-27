@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Tiêu đề mốc điểm được đưa ra ngoài -->
    <h2 class="text-center fw-bold text-uppercase 
        {{ $score == 450 ? 'text-success' : ($score == 650 ? 'text-warning' : 'text-danger') }}">
        Mục Tiêu: {{ $score }} TOEIC
    </h2>
    <h5 class="text-center mb-5 text-muted">Danh sách các chủ đề từ vựng</h5>
    
    <div class="row">
        @foreach($topics as $topic)
            <div class="col-12 col-sm-6 col-lg-3 mb-3">
                <div class="card h-100 shadow-sm border-2 border-{{ $score == 450 ? 'success' : ($score == 650 ? 'warning' : 'danger') }} rounded-4 overflow-hidden">
                    <div class="card-header bg-{{ $score == 450 ? 'success' : ($score == 650 ? 'warning' : 'danger') }} text-{{ $score == 650 ? 'dark' : 'white' }} py-3">
                        <h5 class="fw-bold mb-0">{{ $topic->name }}</h5>
                    </div>

                    @if($topic->image)
                        <img src="{{ asset('storage/' . $topic->image) }}" class="card-img-top" alt="{{ $topic->name }}" style="height: 160px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <p class="text-muted small">{{ $topic->description }}</p>
                        <a href="/learn?topic_id={{ $topic->id }}" class="btn btn-outline-success w-100 fw-bold">Bắt đầu học</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $topics->links() }}
    </div>
</div>
@endsection