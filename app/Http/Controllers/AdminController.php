<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Chặn người dùng thường vào trang admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return view('admin.dashboard');
    }
}