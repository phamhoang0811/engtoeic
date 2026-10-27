@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-6">
            <div class="card h-100 border border-primary-subtle border-2 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h2 class="h4 fw-bold mb-1">Phiên dịch / Tra cứu từ điển</h2>
                    <p class="mb-0">Dịch từ, câu và đoạn văn Anh ↔ Việt</p>
                </div>
                <div class="card-body p-4">
                    <form action="/translate" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="direction" class="form-label fw-bold">Chiều dịch</label>
                            <select id="direction" name="direction" class="form-select">
                                <option value="en-vi" @selected(old('direction', $direction ?? 'en-vi') === 'en-vi')>Anh → Việt</option>
                                <option value="vi-en" @selected(old('direction', $direction ?? 'en-vi') === 'vi-en')>Việt → Anh</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="text" class="form-label fw-bold">Từ, câu hoặc đoạn văn</label>
                            <textarea id="text" name="text" class="form-control" rows="5" maxlength="5000" placeholder="Nhập nội dung cần dịch (tối đa 5.000 ký tự)" required>{{ old('text', $input ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold">Dịch / Tra cứu</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <section class="card h-100 border border-primary-subtle border-2 shadow-sm rounded-4" aria-live="polite">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h2 class="h4 fw-bold mb-0">Kết quả dịch / tra cứu</h2>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-0" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        @if(isset($result))
                            <h3 class="h6 text-muted fw-bold">
                                {{ !empty($alreadyInTargetLanguage) ? 'Nội dung đã ở ngôn ngữ đích' : 'Bản dịch' }}
                            </h3>
                            <p class="mb-3 fs-5" style="white-space: pre-wrap;">{{ $result }}</p>
                        @endif

                        @if(isset($dictionaryWord))
                            <div @if(isset($result)) class="border-top pt-3" @endif>
                                <h3 class="h5 text-primary fw-bold">{{ $dictionaryWord->word }}</h3>
                                @if($dictionaryWord->part_of_speech)
                                    <p class="mb-1"><strong>Loại từ:</strong> {{ $dictionaryWord->part_of_speech_label }}</p>
                                @endif
                                @if($dictionaryWord->phonetic)
                                    <p class="mb-1"><strong>Phiên âm:</strong> {{ $dictionaryWord->phonetic }}</p>
                                @endif
                                <p class="mb-1"><strong>Nghĩa:</strong> {{ $dictionaryWord->meaning }}</p>
                                @if($dictionaryWord->example)
                                    <p class="mb-1"><strong>Ví dụ:</strong> {{ $dictionaryWord->example }}</p>
                                @endif
                                @if($dictionaryWord->synonyms)
                                    <p class="mb-1"><strong>= Đồng nghĩa:</strong> {{ $dictionaryWord->synonyms }}</p>
                                    @if($dictionaryWord->synonyms_meaning)
                                        <p class="mb-1"><strong>Nghĩa đồng nghĩa:</strong> {{ $dictionaryWord->synonyms_meaning }}</p>
                                    @endif
                                @endif
                                @if($dictionaryWord->antonyms)
                                    <p class="mb-1"><strong>&gt;&lt; Trái nghĩa:</strong> {{ $dictionaryWord->antonyms }}</p>
                                    @if($dictionaryWord->antonyms_meaning)
                                        <p class="mb-1"><strong>Nghĩa trái nghĩa:</strong> {{ $dictionaryWord->antonyms_meaning }}</p>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if(!isset($result) && !isset($dictionaryWord))
                            <p class="text-muted mb-0">Kết quả dịch hoặc thông tin tra cứu sẽ hiển thị ở đây sau khi bạn gửi nội dung.</p>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
