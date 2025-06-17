// admin.js - Add this to your existing admin.js file

document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Delete functionality
    let deleteReportId = null;
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Handle delete button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) {
            deleteReportId = e.target.getAttribute('data-row-id');
            deleteModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    });

    // Cancel delete
    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteReportId = null;
    });

    // Confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteReportId) {
            // Show loading state
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.textContent = 'Menghapus...';

            fetch(`/admin/laporan/${deleteReportId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from table
                    const row = document.querySelector(`[data-row-id="${deleteReportId}"]`).closest('tr');
                    if (row) {
                        row.remove();
                    }
                    
                    // Close modal
                    deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    
                    // Show success message (you can customize this)
                    alert('Laporan berhasil dihapus!');
                    
                    // Refresh page to update pagination and numbering
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Gagal menghapus laporan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus laporan');
            })
            .finally(() => {
                // Reset button state
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.textContent = 'Hapus';
                deleteReportId = null;
            });
        }
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteReportId = null;
        }
    });

    // Detail modal functionality (if not already exists)
    const detailModal = document.getElementById('detailModal');
    const closeDetailBtn = document.getElementById('closeDetailBtn');

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-detail')) {
            const nama = e.target.getAttribute('data-nama');
            const tanggal = e.target.getAttribute('data-tanggal');
            const kategori = e.target.getAttribute('data-kategori');
            const deskripsi = e.target.getAttribute('data-deskripsi');
            const media = e.target.getAttribute('data-media');

            document.getElementById('modal-nama').value = nama;
            document.getElementById('modal-tanggal').value = tanggal;
            document.getElementById('modal-kategori').value = kategori;
            document.getElementById('modal-deskripsi').textContent = deskripsi;
            
            const mediaImg = document.getElementById('modal-media');
            if (media) {
                mediaImg.src = media;
                mediaImg.style.display = 'block';
            } else {
                mediaImg.style.display = 'none';
            }

            detailModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    });

    if (closeDetailBtn) {
        closeDetailBtn.addEventListener('click', function() {
            detailModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    }

    // Status update functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-accept')) {
            const reportId = e.target.getAttribute('data-row-id');
            const currentStatus = e.target.getAttribute('data-current-status');
            
            // Toggle between 'Menunggu' and 'Selesai'
            const newStatus = currentStatus === 'Menunggu' ? 'Selesai' : 'Menunggu';
            const newStatusId = newStatus === 'Menunggu' ? 1 : 2; // Adjust these IDs based on your database

            fetch(`/admin/laporan/${reportId}/update-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status_id: newStatusId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button text and class
                    e.target.textContent = newStatus;
                    e.target.setAttribute('data-current-status', newStatus);
                    
                    if (newStatus === 'Menunggu') {
                        e.target.classList.remove('btn-accepted');
                        e.target.classList.add('btn-waiting');
                    } else {
                        e.target.classList.remove('btn-waiting');
                        e.target.classList.add('btn-accepted');
                    }
                } else {
                    alert('Gagal mengupdate status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate status');
            });
        }
    });

    // Logout functionality
    const logoutLink = document.getElementById('logout-link');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            logoutModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    }

    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', function() {
            logoutModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    }

    // Sidebar toggle functionality
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }
});