@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h3 class="text-center fw-bold text-success mb-4">Tạo Tài Khoản</h3>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/register" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nhập tên của bạn">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Nhập email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required placeholder="Ít nhất 6 ký tự">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Xác nhận mật khẩu">
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill mb-3">Đăng ký ngay</button>
                    
                    <div class="text-center mt-2">
                        <span class="text-muted">Đã có tài khoản?</span> 
                        <a href="/login" class="text-decoration-none fw-bold text-primary">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection