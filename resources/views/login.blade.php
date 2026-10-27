@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h3 class="text-center fw-bold text-primary mb-4">Đăng Nhập</h3>
                
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Nhập email của bạn">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill mb-3">Đăng nhập</button>
                    
                    <div class="text-center my-3">
                        <span class="text-muted small">HOẶC</span>
                    </div>

                    <a href="/auth/google" class="btn btn-outline-danger w-100 fw-bold rounded-pill d-flex align-items-center justify-content-center gap-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                            <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.687 0-4.875 2.16-4.875 4.834s2.188 4.834 4.875 4.834c2.58 0 4.141-1.42 4.357-3.275H8v-3.01h7.545z"/>
                        </svg>
                        Đăng nhập bằng Google
                    </a>

                    <div class="text-center mt-2">
                        <span class="text-muted">Chưa có tài khoản?</span> 
                        <a href="/register" class="text-decoration-none fw-bold text-success">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection