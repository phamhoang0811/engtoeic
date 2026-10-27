<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngToeic - Học tiếng Anh thực chiến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #294b73;
            --bs-primary-rgb: 41, 75, 115;
            --bs-link-color: #294b73;
            --bs-link-hover-color: #1d3654;
        }

        /* CSS tạo hình tròn Avatar có chữ cái đầu */
        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #ffc107; /* Màu vàng nổi bật */
            color: #212529;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .roadmap-dropdown .dropdown-menu {
            min-width: 13rem;
        }

        .roadmap-submenu-item {
            position: relative;
        }

        .roadmap-submenu {
            display: none;
            position: absolute;
            top: -0.5rem;
            left: 100%;
            min-width: 14rem;
            padding: 0.5rem 0;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            list-style: none;
        }

        .roadmap-submenu-item:hover > .roadmap-submenu,
        .roadmap-submenu-item:focus-within > .roadmap-submenu {
            display: block;
        }

        @media (min-width: 992px) {
            .roadmap-dropdown:hover > .dropdown-menu,
            .roadmap-dropdown:focus-within > .dropdown-menu {
                display: block;
            }
        }

        @media (max-width: 991.98px) {
            .roadmap-submenu {
                display: block;
                position: static;
                min-width: 0;
                margin: 0 0.75rem 0.5rem;
                padding: 0;
                border: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Thanh điều hướng Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-4" href="/">🚀 EngToeic</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    
                    <!-- Lộ trình học -->
                    <li class="nav-item dropdown roadmap-dropdown me-3">
                        <div class="d-flex align-items-center">
                            <a class="nav-link text-white fw-semibold pe-1" href="/">Lộ trình học</a>
                            <button class="btn btn-link nav-link text-white p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Mở menu lộ trình học">
                                <span aria-hidden="true">▾</span>
                            </button>
                        </div>
                        <ul class="dropdown-menu shadow border-0 rounded-3">
                            @foreach([450, 650, 990] as $targetScore)
                                <li class="roadmap-submenu-item">
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="/score-detail?score={{ $targetScore }}">
                                        Mục tiêu {{ $targetScore }} TOEIC <span aria-hidden="true">›</span>
                                    </a>
                                    <ul class="roadmap-submenu">
                                        <li><a class="dropdown-item" href="/topics?score={{ $targetScore }}">Từ vựng theo chủ đề</a></li>
                                        <li><a class="dropdown-item" href="/grammars?score={{ $targetScore }}">Lý thuyết ngữ pháp</a></li>
                                        <li><a class="dropdown-item" href="/score-detail?score={{ $targetScore }}#exercises">Bài tập</a></li>
                                        <li><a class="dropdown-item" href="/score-detail?score={{ $targetScore }}#listening">Luyện nghe</a></li>
                                        <li><a class="dropdown-item" href="/score-detail?score={{ $targetScore }}">Tất cả nội dung</a></li>
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item me-3">
                            <a class="nav-link text-white fw-semibold" href="/translate">Phiên dịch / Tra cứu</a>
                        </li>
                    @endauth

                    @guest
                        <!-- Chưa đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link text-white fw-bold" href="/login">Đăng nhập</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-light btn-sm text-primary fw-bold rounded-pill px-3" href="/register">Đăng ký</a>
                        </li>
                    @endguest

                    @auth
                        <!-- Đã đăng nhập: Avatar tròn + Dropdown menu -->
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- Chữ cái đầu của tên -->
                                <div class="avatar-circle">
                                    {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-white fw-bold d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            
                            <!-- Menu thông tin xổ xuống khi bấm -->
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                                <li class="px-3 py-2 text-center border-bottom bg-light rounded-top">
                                    <div class="fw-bold text-dark fs-6">{{ Auth::user()->name }}</div>
                                    <small class="text-muted d-block">{{ Auth::user()->email }}</small>
                                    @if(Auth::user()->role === 'admin')
                                        <span class="badge bg-danger mt-1">Quản trị viên</span>
                                    @else
                                        <span class="badge bg-success mt-1">Học viên</span>
                                    @endif
                                </li>

                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold text-primary" href="/admin">
                                            ⚙️ Trang quản trị (Admin)
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                @endif

                                <li>
                                    <form action="/logout" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger fw-semibold">
                                            🚪 Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Khu vực nội dung chính -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Chân trang -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">©  2026 EngToeic Portfolio Project | Developed by Hoang Pham 2k5</p>
    </footer>

    <!-- Thư viện Bootstrap 5 JS (Bắt buộc phải có để bấm xổ Menu) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>