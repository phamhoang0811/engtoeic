@extends('admin.layouts.admin_app')

@section('title', 'Chỉnh sửa Chủ đề')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- QUAN TRỌNG: action có ID và có enctype để sửa ảnh --}}
        <form action="/admin/topics/{{ $topic->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Chủ đề <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $topic->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mốc điểm mục tiêu <span class="text-danger">*</span></label>
                <select name="target_score" class="form-select" required>
                    <option value="450" @selected(old('target_score', $topic->target_score) == 450)>TOEIC 450+ (Nền tảng cơ bản)</option>
                    <option value="650" @selected(old('target_score', $topic->target_score) == 650)>TOEIC 650+ (Mục tiêu trung cấp)</option>
                    <option value="990" @selected(old('target_score', $topic->target_score) == 990)>TOEIC 990+ (Chinh phục đỉnh cao)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh minh họa chủ đề</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="form-text">JPG, PNG, GIF hoặc WebP; tối đa 10 MB.</div>
                
                {{-- Hiển thị ảnh cũ hiện tại --}}
                @if($topic->image)
                    <div class="mt-2">
                        <p class="text-muted small mb-1">Ảnh hiện tại:</p>
                        <img src="{{ asset('storage/' . $topic->image) }}" width="120" class="rounded shadow-sm">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                            <label class="form-check-label" for="remove_image">Xóa ảnh hiện tại</label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $topic->description) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning text-white fw-bold">Cập nhật</button>
                <a href="/admin/topics" class="btn btn-light fw-bold">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection