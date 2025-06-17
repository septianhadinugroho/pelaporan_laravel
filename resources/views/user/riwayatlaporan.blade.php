<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - Sistem Pelaporan Fasilitas</title>
    {{-- CSS yang dipindah ke public/assets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/riwayat.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-logo">
            <button class="menu-toggle">
                <div class="menu-icon"></div>
            </button>
            {{-- Updated href to use route() helper for Laravel routes --}}
            <a href="{{ route('index') }}">Beranda</a>
            <a href="{{ route('lapor') }}">Lapor</a>
            <a href="{{ route('riwayat.laporan') }}" class="active-nav">Riwayat Laporan</a>
        </div>
        <div class="user-info">
            {{-- Display authenticated user's name --}}
            <span>{{ Auth::user()->name ?? 'Pengguna' }}</span>
            <img src="{{ asset('assets/img/avatar.png') }}" alt="User Avatar" class="avatar">
        </div>
    </nav>

    <main class="history-container">
        <h1>Riwayat Laporan</h1>

        <div class="history-list">
            {{-- Loop through reports passed from the controller --}}
            {{-- Assuming $laporans is an array/collection of report objects --}}
            @forelse ($laporans as $report)
                <div class="history-item">
                    <div class="history-image">
                        {{-- Display report media if available, otherwise a default image --}}
                        @if ($report->media)
                            <img src="{{ asset('storage/' . $report->media) }}" alt="Report Image">
                        @else
                            <img src="{{ asset('assets/img/report-image.jpg') }}" alt="Default Report Image">
                        @endif
                    </div>
                    <div class="history-details">
                        <div class="detail-row">
                            <div class="detail-label">Tanggal</div>
                            {{-- Format date using Carbon (Laravel's default date handling) --}}
                            <div class="detail-value">: {{ $report->tanggal_laporan->format('d F Y') }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Kategori</div>
                            {{-- Access category name through the relationship --}}
                            <div class="detail-value">: {{ $report->kategori->name ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value">: {{ $report->deskripsi }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            {{-- Access status name through the relationship --}}
                            <div class="detail-value" style="font-weight: bold; color: {{
                                ($report->status->nama_status == 'Ditangani') ? 'var(--success-color)' :
                                (($report->status->nama_status == 'Ditolak') ? 'var(--error-color)' : 'var(--primary-color)')
                            }};">: {{ $report->status->nama_status ?? 'Diproses' }}</div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Message if no reports are found --}}
                <div class="no-reports">
                    <p>Belum ada riwayat laporan.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div id="profilePopup" class="profile-popup hidden">
        <img src="{{ asset('assets/img/avatar.png') }}" alt="Avatar" class="profile-avatar">
        {{-- Display authenticated user's name and email --}}
        <h3>{{ Auth::user()->name ?? '' }}</h3>
        <p>{{ Auth::user()->email ?? '' }}</p>
        <button id="logoutBtn" class="logout-btn">Log out</button>
    </div>

    <div id="confirmLogoutModal" class="modal hidden">
        <div class="modal-content center">
            <h2>Anda yakin ingin keluar?</h2>
            <div class="modal-actions">
                {{-- Logout form using Laravel's route and CSRF token --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button id="confirmYes" class="btn-confirm" type="submit">Ya</button>
                    <button id="confirmNo" type="button" class="btn-cancel">Tidak</button>
                </form>
            </div>
        </div>
    </div>

    {{-- JS yang dipindah ke public/assets --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>