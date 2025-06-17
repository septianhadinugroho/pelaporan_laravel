<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Beranda - Sistem Pelaporan Fasilitas</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS yang dipindah ke public/assets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"> 
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}"> 
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-logo">
            <button class="menu-toggle">
                <div class="menu-icon"></div>
            </button>
            <a href="{{ route('index') }}" class="active-nav">Dashboard</a>
            <a href="{{ route('lapor') }}">Lapor</a>
            <a href="{{ route('riwayat.laporan') }}">Riwayat Laporan</a>
        </div>
        <div class="user-info">
            <span>{{ Auth::user()->name ?? 'Pengguna' }}</span>
            <img src="{{ asset('assets/img/avatar.png') }}" alt="User Avatar" class="avatar">
        </div>
    </nav>

    <!-- Main Content -->
    <main class="dashboard-container">
        <div class="welcome-banner">
            <div class="welcome-content">
                <h1>Selamat Datang, {{ Auth::user()->name ?? 'Pengguna' }}!</h1>
                <p>Website Pelaporan Fasilitas</p>
                <hr class="divider">
            </div>

            <div class="slider-container">
                <div class="slider">
                    <div class="slide active">
                        <img src="{{ asset('assets/img/fotohome1.jpg') }}" alt="Classroom">
                    </div>
                    <div class="slide">
                        <img src="{{ asset('assets/img/fotohome2.jpg') }}" alt="Facility 1">
                    </div>
                    <div class="slide">
                        <img src="{{ asset('assets/img/fotohome3.jpg') }}" alt="Facility 2">
                    </div>
                </div>
                <div class="slider-controls">
                    <div class="slider-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                    </div>
                </div>
            </div>
        </div>

        <section class="latest-reports">
            <h2 class="section-title">Pelaporan Terbaru</h2>

            @forelse ($laporan as $item)
                <div class="report-card">
                    <div class="reporter">
                        <img src="{{ asset('assets/img/avatar.png') }}" alt="Reporter Avatar" class="avatar">
                        <div class="reporter-info">
                            <h3>{{ $item->judul ?? 'Unknown' }}</h3>
                            <span>{{ $item->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="report-content">
                        @if ($item->media)
                            <img src="{{ asset('storage/' . $item->media) }}" alt="Report Image" class="report-image">
                        @endif
                        <p>{{ $item->deskripsi ?? '' }}</p>
                    </div>
                </div>
            @empty
                <p>Belum ada laporan</p>
            @endforelse
        </section>

    </main>

    <!-- Popup Profile -->
    <div id="profilePopup" class="profile-popup hidden">
        <img src="{{ asset('assets/img/avatar.png') }}" alt="Avatar" class="profile-avatar">
        <h3>{{ Auth::user()->name ?? '' }}</h3>
        <p>{{ Auth::user()->email ?? '' }}</p>
        <button id="logoutBtn" class="logout-btn">Log out</button>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div id="confirmLogoutModal" class="modal hidden">
        <div class="modal-content center">
            <h2>Anda yakin ingin keluar?</h2>
            <div class="modal-actions">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button id="confirmYes" class="btn-confirm">Ya</button>
                    <button id="confirmNo" type="button" class="btn-cancel">Tidak</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS yang dipindah ke public/assets -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

</body>
</html>
