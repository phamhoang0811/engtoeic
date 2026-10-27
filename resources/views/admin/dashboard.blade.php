@extends('admin.layouts.admin_app')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white p-4 rounded-4 border-start border-primary border-4">
            <h5 class="text-muted fw-bold">Tổng số Từ vựng</h5>
            <h2 class="fw-bold text-primary">0</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white p-4 rounded-4 border-start border-success border-4">
            <h5 class="text-muted fw-bold">Tổng số Chủ đề</h5>
            <h2 class="fw-bold text-success">0</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white p-4 rounded-4 border-start border-warning border-4">
            <h5 class="text-muted fw-bold">Học viên đăng ký</h5>
            <h2 class="fw-bold text-warning">1</h2>
        </div>
    </div>
</div>
@endsection