<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - Sistem Pelaporan Fasilitas</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS yang dipindah ke public/assets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lapor.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-logo">
            <button class="menu-toggle">
                <div class="menu-icon"></div>
            </button>
            <a href="{{ route('index') }}">Beranda</a>
            <a href="{{ route('lapor') }}" class="active-nav">Lapor</a>
            <a href="{{ route('riwayat.laporan') }}">Riwayat Laporan</a>
        </div>
        <div class="user-info">
            {{-- Menggunakan Auth::user()->name untuk menampilkan nama user yang login --}}
            <span>{{ Auth::user()->name ?? 'Pengguna' }}</span>
            <img src="{{ asset('assets/img/avatar.png') }}" alt="User Avatar" class="avatar">
        </div>
    </nav>

    <main class="report-container">
        <h1>Buat Laporan</h1>

        <div class="report-form-container">
            {{-- Mengarahkan form ke route untuk menyimpan laporan (misal: 'laporan.store') --}}
            {{-- Tambahkan method POST dan CSRF token untuk keamanan Laravel --}}
            <form id="reportForm" class="report-form" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="reporterName">Nama Pelapor</label>
                    {{-- Menggunakan Auth::user()->name untuk value, dan disabled agar tidak bisa diubah --}}
                    <input type="text" id="reporterName" name="reporterName" value="{{ Auth::user()->name ?? '' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="reportDate">Tanggal</label>
                    <input type="date" id="reportDate" name="reportDate" placeholder="mm/dd/yyyy">
                </div>

                <div class="form-group">
                    <label for="category">Kategori</label>
                    <div class="select-wrapper">
                        {{-- Data kategori diambil dari database --}}
                        <select id="category" name="kategori_id">
                            <option value="" selected disabled>Pilih kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mediaUpload">Gambar/Video</label>
                    <div class="file-upload">
                        <input type="file" id="mediaUpload" name="media" accept="image/*,video/mp4">
                        <button type="button" class="file-upload-btn">Choose Files</button>
                    </div>
                    <div class="file-info">
                        <p>Accepted file types:</p>
                        <p>Image files (.jpg, .jpeg, .png, .gif, .webp, .bmp) Max: 10 MB</p>
                        <p>Video files (.mp4) Max: 40 MB</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="deskripsi" placeholder="Deskripsi"></textarea>
                    <div class="char-count"><span id="charCount">0</span>/110 karakter</div>
                </div>

                <div class="form-submit">
                    <button type="submit" class="submit-button">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </main>

    {{-- Modal ini akan di-handle oleh JavaScript jika laporan berhasil disimpan secara asinkron atau setelah redirect --}}
    <div id="successModal" class="modal hidden">
        <div class="modal-content">
            <h2>Laporan Berhasil Dibuat!</h2>
            <button id="okButton" class="ok-button">Oke</button>
        </div>
    </div>

    <div id="profilePopup" class="profile-popup hidden">
        <img src="{{ asset('assets/img/avatar.png') }}" alt="Avatar" class="profile-avatar">
        <h3>{{ Auth::user()->name ?? '' }}</h3>
        <p>{{ Auth::user()->email ?? '' }}</p>
        <button id="logoutBtn" class="logout-btn">Log out</button>
    </div>

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

    {{-- JS yang dipindah ke public/assets --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/lapor.js') }}"></script>
</body>
</html>