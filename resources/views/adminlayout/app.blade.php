<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý hệ thống</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* ===== Intro full-screen overlay (scoped) ===== */
        .admin-intro-overlay {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            background: linear-gradient(-45deg,#0f172a,#111827,#1e293b,#0f172a);
            background-size: 400% 400%;
            animation: adminIntroBgMove 12s ease infinite;
        }

        .admin-intro-root {
            width: 90%;
            max-width: 1300px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 60px;
            color: #fff;
        }

        .admin-intro-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: adminIntroFadeIn 1.5s ease forwards;
        }

        .admin-intro-image img {
            width: clamp(250px, 40vw, 500px);
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 30px 50px rgba(0,0,0,0.7));
        }

        .admin-intro-text {
            flex: 1;
            max-width: 550px;
            animation: adminIntroSlideIn 1.2s ease forwards;
        }

        .admin-intro-text h1 {
            font-size: clamp(28px, 4vw, 50px);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .admin-intro-text p {
            opacity: 0.75;
            margin-bottom: 40px;
            font-size: clamp(14px, 1.2vw, 16px);
        }

        .admin-intro-button {
            padding: 14px 50px;
            border-radius: 40px;
            border: none;
            font-weight: 600;
            background: linear-gradient(135deg,#3b82f6,#2563eb);
            color: white;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(59,130,246,0.3);
        }

        .admin-intro-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(59,130,246,0.6);
        }

        .admin-intro-fade-out {
            opacity: 0;
            transform: scale(1.05);
            transition: 0.6s;
        }

        @keyframes adminIntroSlideIn {
            from {
                opacity: 0;
                transform: translateX(-60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes adminIntroFadeIn {
            from {
                opacity: 0;
                transform: translateX(60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes adminIntroBgMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @media (max-width: 900px) {
            .admin-intro-root {
                flex-direction: column;
                text-align: center;
                gap: 40px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    @if(session('show_admin_intro'))
    <div id="adminIntroModal" class="admin-intro-overlay">
        <div class="admin-intro-root">
            <div class="admin-intro-image">
                <img src="{{ asset('images/logo/yamato.jpg') }}" alt="Admin Image">
            </div>

            <div class="admin-intro-text">
            <h1>Hệ thống quản trị<br>khóa học tiếng Nhật</h1>
                <p>
                    Hệ thống quản trị đào tạo hiện đại.
                    Quản lý bảng chữ cái, Kanji, bài học Minna no Nihongo và dữ liệu khóa học thông minh.
                </p>
                <button type="button" class="admin-intro-button" onclick="enterAdminSystem()">
                    Truy cập quản trị
                </button>
            </div>
        </div>
    </div>

    <script>
        function enterAdminSystem() {
            const modal = document.getElementById('adminIntroModal');
            if (!modal) return;
            modal.classList.add('admin-intro-fade-out');
            setTimeout(() => {
                modal.remove();
            }, 600);
        }
    </script>
    @endif

    <!-- Sidebar -->
    <div class="flex">
        @include('adminlayout.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1">
            @include('adminlayout.header')
            
            <!-- Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
