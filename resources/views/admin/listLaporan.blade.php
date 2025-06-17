<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Laporan</title>
    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
    <div class="header">
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
        <div class="profile">
            {{-- Display authenticated admin's name --}}
            <div class="profile-text">{{ Auth::user()->name ?? 'Super Admin' }}</div>
            <div class="profile-img">
                <i class="fas fa-user"></i> {{-- Using font-awesome icon --}}
                {{-- Or if you have an actual avatar image for admin: --}}
                {{-- <img src="{{ asset('assets/img/avatar.png') }}" alt="Admin Avatar" style="width: 100%; height: 100%; border-radius: 50%;"> --}}
            </div>
        </div>
    </div>

    <div class="sidebar-overlay"></div>

    <div class="main-container">
        <div class="sidebar">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-book" style="font-size: 70px;"></i>
                </div>
            </div>

            {{-- Navigation Links using route() helper --}}
            <a href="{{ route('dashboard') }}" class="menu-item">
                <div class="menu-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="menu-text">Dashboard</div>
            </a>

            <a href="{{ route('admin.laporan.index') }}" class="menu-item">
                <div class="menu-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="menu-text">Laporan</div>
            </a>

            {{-- Logout link --}}
            <a href="#" class="menu-item" id="logout-link">
                <div class="menu-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="menu-text">Log Out</div>
            </a>
        </div>

        <div class="main-content">

            <div class="filter-container">
                <div class="filter-label">Filter by:</div>
                <select class="filter-select" id="kategori-filter">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                    @endforeach
                </select>

                <select class="filter-select" id="status-filter">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="data-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelapor</th>
                            <th>Kategori Laporan</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->nama_pelapor }}</td>
                                <td>{{ $report->kategori->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($report->deskripsi, 50, '...') }}</td>
                                <td>{{ $report->tanggal_laporan->format('d/m/Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-detail" data-row-id="{{ $report->id }}"
                                            data-nama="{{ $report->nama_pelapor }}"
                                            data-tanggal="{{ $report->tanggal_laporan->format('d/m/Y') }}"
                                            data-kategori="{{ $report->kategori->name ?? 'N/A' }}"
                                            data-deskripsi="{{ $report->deskripsi }}"
                                            data-media="{{ $report->media ? asset('storage/' . $report->media) : '' }}">Detail</button>
                                        <button class="btn btn-accept {{ ($report->status->nama_status == 'Dalam Antrian') ? 'btn-waiting' : '' }}"
                                            data-row-id="{{ $report->id }}"
                                            data-current-status="{{ $report->status->nama_status }}">
                                            {{ $report->status->nama_status }}
                                        </button>
                                        <button class="btn btn-delete" data-row-id="{{ $report->id }}">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">Tidak ada laporan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="pagination-left">
                    <span id="page-info">Halaman {{ $laporans->currentPage() }} dari {{ $laporans->lastPage() }}</span>
                </div>
                <div class="pagination-right">
                    {{-- Use Laravel's built-in pagination links --}}
                    {{ $laporans->links('pagination::bootstrap-4') }} {{-- You might need to publish pagination views: php artisan vendor:publish --tag=laravel-pagination --}}
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Laporan</h2>
                <span class="close-modal" id="closeDetailBtn">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="modal-nama">Nama Pelapor</label>
                    <input type="text" id="modal-nama" class="form-input" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="modal-tanggal">Tanggal</label>
                    <input type="text" id="modal-tanggal" class="form-input" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="modal-kategori">Kategori</label>
                    <input type="text" id="modal-kategori" class="form-input" value="" readonly>
                </div>
                <div class="form-row">
                    <div class="form-group form-group-image">
                        <label>Gambar/Video</label>
                        <div class="image-container">
                            <img id="modal-media" src="" alt="Gambar Laporan" style="width: 100%; max-width: 300px; height: auto; border-radius: 8px;" />
                        </div>
                    </div>
                    <div class="form-group form-group-desc">
                        <label>Deskripsi</label>
                        <div class="desc-container">
                            <p id="modal-deskripsi"></p>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn-primary" onclick="document.getElementById('detailModal').style.display='none'; document.body.style.overflow='auto';">Kembali</button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Hapus Data Laporan</h2>
            </div>
            <div class="modal-body">
                <p style="text-align: center; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus laporan ini?</p>
                <div class="modal-buttons">
                    <button id="cancelDeleteBtn" class="btn-secondary">Kembali</button>
                    <button id="confirmDeleteBtn" class="btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout Modal --}}
    <div id="logoutModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Konfirmasi Logout</h2>
            </div>
            <div class="modal-body">
                <p style="text-align: center; margin-bottom: 20px;">Apakah Anda yakin ingin keluar?</p>
                <div class="modal-buttons">
                    {{-- Logout form using Laravel's route and CSRF token --}}
                    <form id="logout-form-admin" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button id="cancelLogoutBtn" type="button" class="btn-secondary">Kembali</button>
                        <button id="confirmLogoutBtn" type="submit" class="btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin JavaScript --}}
    <script src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>