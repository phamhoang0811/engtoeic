@extends('admin.layouts.admin_app')

@section('title', 'Quản lý Chủ đề')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Danh sách Chủ đề</h4>
    <a href="/admin/topics/create" class="btn btn-primary fw-bold">+ Thêm Chủ đề Mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="/admin/topics" method="GET" class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="topic-search" class="form-label fw-bold">Tìm theo tên chủ đề</label>
                <input id="topic-search" type="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Nhập tên chủ đề">
            </div>
            <div class="col-md-4">
                <label for="target-score" class="form-label fw-bold">Mục tiêu TOEIC</label>
                <select id="target-score" name="target_score" class="form-select">
                    <option value="">Tất cả mục tiêu</option>
                    @foreach([450, 650, 990] as $score)
                        <option value="{{ $score }}" @selected(($filters['target_score'] ?? '') == $score)>TOEIC {{ $score }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
                <a href="/admin/topics" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Ảnh</th>
                    <th>Tên Chủ đề</th>
                    <th>Mục tiêu</th>
                    <th>Mô tả</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topics as $topic)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $topic->id }}</td>
                        <td>
                            @if($topic->image)
                                <img src="{{ asset('storage/' . $topic->image) }}" alt="{{ $topic->name }}" width="64" height="48" class="rounded object-fit-cover">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-bold text-primary">{{ $topic->name }}</td>
                        <td><span class="badge bg-primary">TOEIC {{ $topic->target_score }}</span></td>
                        <td class="text-muted">{{ $topic->description ?? 'Chưa có mô tả' }}</td>
                        <td class="text-center">
                            <a href="/admin/topics/{{ $topic->id }}/edit" class="btn btn-sm btn-warning text-white fw-bold me-1">✏️ Sửa</a>
                            
                            <form action="/admin/topics/{{ $topic->id }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa chủ đề này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger fw-bold">🗑️ Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy chủ đề phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection