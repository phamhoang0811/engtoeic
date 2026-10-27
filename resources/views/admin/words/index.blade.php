@extends('admin.layouts.admin_app')

@section('title', 'Quản lý Từ vựng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Danh sách Từ vựng</h4>
    <a href="/admin/words/create" class="btn btn-primary fw-bold">+ Thêm Từ vựng</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="/admin/words" method="GET" class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label for="word-search" class="form-label fw-bold">Tìm từ vựng</label>
                <input id="word-search" type="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Từ tiếng Anh, nghĩa hoặc ví dụ">
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="word-topic" class="form-label fw-bold">Chủ đề</label>
                <select id="word-topic" name="topic_id" class="form-select">
                    <option value="">Tất cả chủ đề</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" @selected(($filters['topic_id'] ?? '') == $topic->id)>{{ $topic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="word-part-of-speech" class="form-label fw-bold">Loại từ</label>
                <select id="word-part-of-speech" name="part_of_speech" class="form-select">
                    <option value="">Tất cả loại từ</option>
                    @foreach($partsOfSpeech as $partOfSpeech)
                        <option value="{{ $partOfSpeech }}" @selected(($filters['part_of_speech'] ?? '') === $partOfSpeech)>{{ \App\Models\Word::partOfSpeechLabel($partOfSpeech) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
                <a href="/admin/words" class="btn btn-outline-secondary">Xóa</a>
            </div>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="min-width: 1000px;">
            <thead class="table-dark">
                <tr>
                    <th>Ảnh</th>
                    <th class="ps-4">ID</th>
                    <th>Từ vựng</th>
                    <th>Loại từ</th>
                    <th>Nghĩa</th>
                    <th>Đồng nghĩa / Trái nghĩa</th>
                    <th>Thuộc Chủ đề</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($words as $word)
                    <tr>
                        <td>
                            @if($word->image)
                                <img src="{{ \Illuminate\Support\Str::startsWith($word->image, ['http://', 'https://']) ? $word->image : asset('storage/' . $word->image) }}" alt="{{ $word->word }}" width="64" height="48" class="rounded object-fit-cover">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="ps-4 fw-bold">#{{ $word->id }}</td>
                        <td class="fw-bold text-primary fs-5">{{ $word->word }}</td>
                        <td>
                            <span class="badge bg-info text-dark border">{{ $word->part_of_speech_label }}</span>
                        </td>
                        <td class="fw-semibold">{{ $word->meaning }}</td>
                        <td class="small">
                            @if($word->synonyms)
                                <div><strong>=</strong> {{ $word->synonyms }}</div>
                                @if($word->synonyms_meaning)
                                    <div class="text-muted">{{ $word->synonyms_meaning }}</div>
                                @endif
                            @endif
                            @if($word->antonyms)
                                <div><strong>&gt;&lt;</strong> {{ $word->antonyms }}</div>
                                @if($word->antonyms_meaning)
                                    <div class="text-muted">{{ $word->antonyms_meaning }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="text-secondary">{{ $word->topic->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="/admin/words/{{ $word->id }}/edit" class="btn btn-sm btn-warning text-white fw-bold me-1">✏️ Sửa</a>
                            <form action="/admin/words/{{ $word->id }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa từ này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger fw-bold">🗑️ Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy từ vựng phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection