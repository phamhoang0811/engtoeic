@extends('admin.layouts.admin_app')

@section('title', 'Thêm Chủ đề Mới')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        
        {{-- Hiển thị thông báo lỗi nếu có để biết nguyên nhân --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/topics" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
        <label class="form-label fw-bold">Tên Chủ đề <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Office, Travel, Finance..." required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Mốc điểm mục tiêu <span class="text-danger">*</span></label>
        <select name="target_score" class="form-select" required>
            <option value="450" @selected(old('target_score', 450) == 450)>TOEIC 450+ (Nền tảng cơ bản)</option>
            <option value="650" @selected(old('target_score') == 650)>TOEIC 650+ (Mục tiêu trung cấp)</option>
            <option value="990" @selected(old('target_score') == 990)>TOEIC 990+ (Chinh phục đỉnh cao)</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Ảnh minh họa chủ đề</label>
        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
        <div class="form-text">JPG, PNG, GIF hoặc WebP; tối đa 10 MB.</div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Mô tả</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả ngắn cho chủ đề...">{{ old('description') }}</textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary fw-bold">Lưu Chủ đề</button>
        <a href="/admin/topics" class="btn btn-light fw-bold">Hủy bỏ</a>
    </div>
</form>
    </div>
</div>
@endsection