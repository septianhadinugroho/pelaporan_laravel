<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

            {{-- Assuming a route for admin to view all reports --}}
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
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    {{-- Dynamic data from controller --}}
                    <div class="stat-card-number">{{ $totalReports ?? 0 }}</div>
                    <div class="stat-card-text">Laporan Masuk</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    {{-- Dynamic data from controller --}}
                    <div class="stat-card-number">{{ $pendingReports ?? 0 }}</div>
                    <div class="stat-card-text">Laporan Belum Ditanggapi</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    {{-- Dynamic data from controller --}}
                    <div class="stat-card-number">{{ $acceptedReports ?? 0 }}</div>
                    <div class="stat-card-text">Laporan Diterima</div>
                </div>
            </div>

            <div class="data-table-container">
                <div class="table-header">
                    <div>10 laporan terbaru</div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelapor</th>
                            <th>Kategori Laporan</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            {{-- Action column is typically here for admin --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop through latest reports from controller --}}
                        @forelse ($latestReports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td> {{-- Loop iteration for row number --}}
                                <td>{{ $report->nama_pelapor }}</td>
                                <td>{{ $report->kategori->name ?? 'N/A' }}</td> {{-- Access category name --}}
                                <td>{{ Str::limit($report->deskripsi, 50, '...') }}</td> {{-- Limit description length --}}
                                <td>{{ $report->tanggal_laporan->format('d/m/Y') }}</td> {{-- Formatted date --}}
                                <td>
                                    <div class="action-buttons">
                                        {{-- These buttons will likely be handled by admin.js --}}
                                        <button class="btn btn-detail" data-row-id="{{ $report->id }}">Detail</button>
                                        <button class="btn btn-accept" data-row-id="{{ $report->id }}">Accept</button>
                                        <button class="btn btn-delete" data-row-id="{{ $report->id }}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">Tidak ada laporan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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