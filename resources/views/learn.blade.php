@extends('layouts.app')

@section('content')
<!-- Căn giữa toàn bộ theo chiều dọc -->
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 75vh;">
    
    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-4 w-100">
        
        <!-- Nút Trái (Quay lại) -->
        <!-- Nút Trái (Quay lại) -->
        <button class="btn btn-primary shadow-sm fs-4 d-flex align-items-center justify-content-center" 
                id="btn-prev" onclick="changeCard(-1)" disabled 
                style="width: 50px; height: 50px; border-radius: 50%;">
            ❮
        </button>

        <!-- Khung Card được thu gọn -->
        <div id="flashcard-container" class="position-relative" style="width: 100%; max-width: 450px;">
            @foreach($words as $index => $word)
            <div class="word-card card shadow text-center border-0" id="card-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }}; border-radius: 1rem;">
                
                <div class="position-absolute top-0 end-0 p-3 fw-bold text-muted" style="font-size: 0.9rem;">
                    {{ $index + 1 }} / {{ count($words) }}
                </div>

                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 p-md-4">
                    <!-- Ảnh được giới hạn chiều cao nhỏ lại -->
                    @if($word->image)
                        <img src="{{ \Illuminate\Support\Str::startsWith($word->image, ['http://', 'https://']) ? $word->image : asset('storage/' . $word->image) }}" alt="{{ $word->word }}" class="img-fluid rounded mb-2" style="max-height: 120px; object-fit: cover;">
                    @endif

                    <div class="position-relative w-100 px-5 mb-1">
                        <h3 class="card-title text-primary fw-bold mb-0">{{ $word->word }}</h3>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle position-absolute end-0 translate-middle-y" style="top: calc(50% + 0.75rem);" data-pronounce="{{ $word->word }}" aria-label="Nghe cách đọc từ {{ $word->word }}">🔊</button>
                    </div>
                    @if($word->phonetic)
                        <div class="text-muted mb-1">{{ $word->phonetic }}</div>
                    @endif
                    <div class="text-secondary fw-bold mb-1" style="font-size: 0.85rem;">{{ $word->part_of_speech_label ?: '(n) Danh từ' }}</div>
                    <span class="visually-hidden" id="pronunciation-status" aria-live="polite"></span>
                    
                    <hr class="w-50 mx-auto my-2">
                    
                    <h5 class="card-text text-success mb-2 fw-bold">{{ $word->meaning }}</h5>

                    <!-- Phông chữ phần ví dụ được tinh chỉnh -->
                    @if($word->example)
                        <p class="text-dark fst-italic mb-1" style="font-size: 0.9rem;"><strong>Ví dụ:</strong>
                            {{ $word->example }}
                        </p>
                    @endif

                    @if($word->synonyms)
                        <p class="text-muted mb-0" style="font-size: 0.8rem;"><strong>= Đồng nghĩa:</strong> {{ $word->synonyms }}@if($word->synonyms_meaning) ({{ $word->synonyms_meaning }})@endif</p>
                    @endif

                    @if($word->antonyms)
                        <p class="text-muted mb-0" style="font-size: 0.8rem;"><strong>&gt;&lt; Trái nghĩa:</strong> {{ $word->antonyms }}@if($word->antonyms_meaning) ({{ $word->antonyms_meaning }})@endif</p>
                    @elseif($word->family)
                        <p class="text-muted mb-0" style="font-size: 0.8rem;"><strong>Họ từ:</strong> {{ $word->family }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Nút Phải (Tiếp theo) -->
        <button class="btn btn-primary shadow-sm fs-4 d-flex align-items-center justify-content-center" 
                id="btn-next" onclick="changeCard(1)" 
                style="width: 50px; height: 50px; border-radius: 50%;">
            ❯
        </button>

    </div>
    
    <!-- Dòng thông báo hoàn thành ẩn ở dưới -->
    <div id="finish-text" class="text-success fw-bold mt-4" style="display: none; font-size: 1.1rem;">
        🎉 Nhấn dấu ✔ để hoàn thành bài học!
    </div>

</div>

<script>
    document.querySelectorAll('[data-pronounce]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!('speechSynthesis' in window) || !('SpeechSynthesisUtterance' in window)) {
                document.getElementById('pronunciation-status').textContent = 'Trình duyệt này không hỗ trợ phát âm.';
                return;
            }

            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(button.dataset.pronounce);
            utterance.lang = 'en-US';
            window.speechSynthesis.speak(utterance);
        });
    });

    let currentIndex = 0;
    const totalCards = {{ count($words) }};
    
    function changeCard(direction) {
        document.getElementById('card-' + currentIndex).style.display = 'none';
        currentIndex += direction;
        document.getElementById('card-' + currentIndex).style.display = 'block';
        
        document.getElementById('btn-prev').disabled = currentIndex === 0;
        
        let btnNext = document.getElementById('btn-next');
        let finishText = document.getElementById('finish-text');

        if (currentIndex === totalCards - 1) {
            // Đổi nút thành dấu tích xanh khi tới thẻ cuối
            btnNext.innerHTML = '✔';
            btnNext.classList.replace('btn-primary', 'btn-success');
            btnNext.onclick = function() { window.location.href = '/topics'; };
            finishText.style.display = 'block'; 
        } else {
            // Trả lại nút mũi tên bình thường
            btnNext.innerHTML = '❯';
            btnNext.classList.replace('btn-success', 'btn-primary');
            btnNext.onclick = function() { changeCard(1); };
            finishText.style.display = 'none'; 
        }
    }
</script>
@endsection