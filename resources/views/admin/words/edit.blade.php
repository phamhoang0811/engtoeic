@extends('admin.layouts.admin_app')

@section('title', 'Chỉnh sửa Từ vựng')

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

        <form action="/admin/words/{{ $word->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold" for="topic_id">Chủ đề <span class="text-danger">*</span></label>
                <select name="topic_id" id="topic_id" class="form-select" required>
                    <option value="">-- Chọn chủ đề --</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" @selected(old('topic_id', $word->topic_id) == $topic->id)>{{ $topic->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="word">Từ vựng <span class="text-danger">*</span></label>
                <input type="text" name="word" id="word" class="form-control" value="{{ old('word', $word->word) }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold" for="part_of_speech">Loại từ <span class="text-danger">*</span></label>
                    <select name="part_of_speech" id="part_of_speech" class="form-select" required>
                        <option value="">-- Chọn loại từ --</option>
                        @foreach(\App\Models\Word::partOfSpeechOptions() as $code => $label)
                            <option value="{{ $code }}" @selected(\App\Models\Word::normalizePartOfSpeech(old('part_of_speech', $word->part_of_speech)) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold" for="phonetic">Phiên âm</label>
                    <input type="text" name="phonetic" id="phonetic" class="form-control" value="{{ old('phonetic', $word->phonetic) }}" placeholder="Ví dụ: /ˈkɒntrækt/">
                    <div class="form-text">Nhập phiên âm IPA, ví dụ /ˈkɒntrækt/.</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="meaning">Nghĩa <span class="text-danger">*</span></label>
                <input type="text" name="meaning" id="meaning" class="form-control" value="{{ old('meaning', $word->meaning) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="example">Câu ví dụ <span class="text-danger">*</span></label>
                <textarea name="example" id="example" class="form-control" rows="3" placeholder="Viết một câu tiếng Anh có chứa từ vựng đang thêm" required>{{ old('example', $word->example) }}</textarea>
                <div class="form-text">Câu ví dụ cần có từ vựng đang thêm.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="synonyms">Từ đồng nghĩa (=)</label>
                <textarea name="synonyms" id="synonyms" class="form-control" rows="2" placeholder="Ví dụ: home, household">{{ old('synonyms', $word->synonyms) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="synonyms_meaning">Nghĩa của từ đồng nghĩa</label>
                <textarea name="synonyms_meaning" id="synonyms_meaning" class="form-control" rows="2" placeholder="Nhập nghĩa theo đúng thứ tự từ đồng nghĩa">{{ old('synonyms_meaning', $word->synonyms_meaning) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="antonyms">Từ trái nghĩa (&gt;&lt;)</label>
                <textarea name="antonyms" id="antonyms" class="form-control" rows="2" placeholder="Ví dụ: stranger">{{ old('antonyms', $word->antonyms) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="antonyms_meaning">Nghĩa của từ trái nghĩa</label>
                <textarea name="antonyms_meaning" id="antonyms_meaning" class="form-control" rows="2" placeholder="Nhập nghĩa theo đúng thứ tự từ trái nghĩa">{{ old('antonyms_meaning', $word->antonyms_meaning) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="image">Ảnh minh họa</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="form-text">JPG, PNG, GIF hoặc WebP; tối đa 10 MB.</div>
                @if($word->image)
                    @php
                        $imageUrl = \Illuminate\Support\Str::startsWith($word->image, ['http://', 'https://'])
                            ? $word->image
                            : asset('storage/' . $word->image);
                    @endphp
                    <div class="mt-2">
                        <p class="text-muted small mb-1">Ảnh hiện tại:</p>
                        <img src="{{ $imageUrl }}" alt="{{ $word->word }}" width="120" class="rounded shadow-sm">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                            <label class="form-check-label" for="remove_image">Xóa ảnh hiện tại</label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning text-white fw-bold">Cập nhật</button>
                <a href="/admin/words" class="btn btn-light fw-bold">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection
