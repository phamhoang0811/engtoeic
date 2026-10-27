<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Admin - EngToeic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; width: 250px; flex: 0 0 250px; }
        .admin-main { min-width: 0; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark text-white p-3">
            <h4 class="text-center text-warning fw-bold mb-4">🚀 Admin Panel</h4>
            <ul class="nav flex-column gap-2">
                <ul class="nav flex-column">
    <li class="nav-item mb-2">
        <a class="nav-link rounded {{ request()->is('admin') ? 'bg-primary text-white fw-bold' : 'text-light' }}" href="/admin">
            📊 Bảng điều khiển
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link rounded {{ request()->is('admin/topics*') ? 'bg-primary text-white fw-bold' : 'text-light' }}" href="/admin/topics">
            📁 Quản lý Chủ đề
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link rounded {{ request()->is('admin/words*') ? 'bg-primary text-white fw-bold' : 'text-light' }}" href="/admin/words">
            📝 Quản lý Từ vựng
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link rounded {{ request()->is('admin/grammars*') ? 'bg-primary text-white fw-bold' : 'text-light' }}" href="/admin/grammars">
            📖 Quản lý Ngữ pháp
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link rounded {{ request()->is('admin/listenings*') ? 'bg-primary text-white fw-bold' : 'text-light' }}" href="/admin/listenings">
            🎧 Quản lý Luyện nghe
        </a>
    </li>
</ul>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger fw-bold" href="/">⬅️ Quay lại trang web</a>
                </li>
            </ul>
        </nav>

        <!-- Nội dung chính -->
        <main class="admin-main flex-grow-1 p-4">
            <!-- Header Admin -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h3 class="fw-bold text-secondary">@yield('title')</h3>
                <div class="text-dark fw-semibold">
                    Xin chào, {{ Auth::user()->name }} (Admin)
                </div>
            </div>

            <!-- Nơi đổ dữ liệu các trang con -->
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>