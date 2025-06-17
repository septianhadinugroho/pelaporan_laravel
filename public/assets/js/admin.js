document.addEventListener('DOMContentLoaded', function() {
    // Get references to elements
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    // Toggle sidebar on hamburger menu click
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // Hide the menu toggle icon when sidebar is active
        if (sidebar.classList.contains('active')) {
            menuToggle.style.visibility = 'hidden';
        } else {
            menuToggle.style.visibility = 'visible';
        }
    });
    
    // Close sidebar when clicking on overlay
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        menuToggle.style.visibility = 'visible';
    });
    
    // Close sidebar when window is resized to desktop view
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            menuToggle.style.visibility = 'visible';
        }
        
        // Ensure the sidebar height is always full height
        adjustSidebarHeight();
    });
    
    // Close sidebar when clicking on menu items (for mobile)
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                menuToggle.style.visibility = 'visible';
            }
        });
    });
    
    // Function to adjust sidebar height
    function adjustSidebarHeight() {
        const mainContainerHeight = document.querySelector('.main-container').offsetHeight;
        const windowHeight = window.innerHeight;
        const headerHeight = document.querySelector('.header').offsetHeight;
        
        // Set sidebar height to either the main container height or viewport height minus header
        const sidebarHeight = Math.max(mainContainerHeight, windowHeight - headerHeight);
        sidebar.style.height = sidebarHeight + 'px';
    }
    
    // Initial adjustment
    adjustSidebarHeight();
    
    // Re-adjust on window resize
    window.addEventListener('resize', adjustSidebarHeight);
    
    // Re-adjust when content changes (might affect height)
    const observer = new MutationObserver(adjustSidebarHeight);
    observer.observe(document.querySelector('.main-content'), {
        childList: true,
        subtree: true
    });

    // Modal elements
    const detailModal = document.getElementById('detailModal');
    const deleteModal = document.getElementById('deleteModal');
    const logoutModal = document.getElementById('logoutModal');
    const closeDetailBtn = document.getElementById('closeDetailBtn');
    const closeModalBtn = document.querySelector('.close-modal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

    // Connect logout button to the modal
    const logoutLink = document.getElementById('logout-link');
    
    // Connect logout button to the modal
    if (logoutLink) {
        logoutLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default navigation
            openLogoutModal();
        });
    }

    // Function to open detail modal
    function openDetailModal(rowData) {
        // Populate modal with data
        document.getElementById('modal-nama').value = rowData.nama; //
        document.getElementById('modal-tanggal').value = rowData.tanggal; //
        document.getElementById('modal-kategori').value = rowData.kategori; //
        document.getElementById('modal-deskripsi').textContent = rowData.deskripsi; //
        const mediaElement = document.getElementById('modal-media'); //
        if (rowData.media) { //
            mediaElement.src = rowData.media; //
            mediaElement.style.display = 'block'; //
        } else { //
            mediaElement.style.display = 'none'; //
        }
        
        // Show the modal
        detailModal.style.display = 'block'; //
        
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden'; //
    }
    
    // Function to close detail modal
    function closeDetailModal() {
        detailModal.style.display = 'none'; //
        
        // Restore body scrolling
        document.body.style.overflow = 'auto'; //
    }
    
    // Function to open delete confirmation modal
    function openDeleteModal(rowId) {
        // Store the row ID for deletion (could be used later with AJAX)
        deleteModal.dataset.rowId = rowId; //
        
        // Show the modal
        deleteModal.style.display = 'block'; //
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden'; //
    }
    
    // Function to close delete modal
    function closeDeleteModal() {
        deleteModal.style.display = 'none'; //
        
        // Restore body scrolling
        document.body.style.overflow = 'auto'; //
    }

    // Function to open logout confirmation modal
    function openLogoutModal() {
        // Show the modal
        logoutModal.style.display = 'block'; //
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden'; //
    }
    
    // Function to close logout modal
    function closeLogoutModal() {
        logoutModal.style.display = 'none'; //
        
        // Restore body scrolling
        document.body.style.overflow = 'auto'; //
    }
    
    // Handle logout confirmation
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            // This is already handled by the form submission action="{{ route('logout') }}"
            // window.location.href = 'login.html'; // Remove this line
        });
    }
    
    // Handle logout cancellation
    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', closeLogoutModal); //
    }
    
    // Handle status update (accept button)
    function handleStatusUpdate(reportId, currentStatusName, buttonElement) {
        let newStatusId;
        let newStatusName;

        // Determine the new status based on the current status
        if (currentStatusName === "Menunggu") {
            newStatusId = 2; // Assuming 'Selesai' has ID 2
            newStatusName = "Selesai";
        } else if (currentStatusName === "Selesai") {
            newStatusId = 1; // Assuming 'Menunggu' has ID 1
            newStatusName = "Menunggu";
        } else {
            console.error("Unexpected status encountered:", currentStatusName);
            return;
        }

        // Make an AJAX request to update the status in the database
        fetch(`/admin/laporan/${reportId}/update-status`, { //
            method: 'POST', //
            headers: { //
                'Content-Type': 'application/json', //
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') //
            },
            body: JSON.stringify({ status_id: newStatusId }) //
        })
        .then(response => { //
            if (!response.ok) { //
                return response.json().then(err => { throw new Error(err.message || 'Something went wrong'); }); //
            }
            return response.json(); //
        })
        .then(data => { //
            if (data.success) { //
                // Update the button text and class on successful update
                buttonElement.textContent = newStatusName; //
                if (newStatusName === "Menunggu") { //
                    buttonElement.classList.remove('btn-accepted'); //
                    buttonElement.classList.add('btn-waiting'); //
                } else if (newStatusName === "Selesai") { //
                    buttonElement.classList.remove('btn-waiting'); //
                    buttonElement.classList.add('btn-accepted'); //
                }
                console.log(`Status updated for report ${reportId} to ${newStatusName}`); //
            } else { //
                console.error('Failed to update status:', data.message); //
                alert('Gagal memperbarui status: ' + data.message); //
            }
        })
        .catch(error => { //
            console.error('Error updating status:', error); //
            alert('Terjadi kesalahan saat memperbarui status. Silakan coba lagi.'); //
        });
    }
    
    // Handle row deletion
    function handleDelete(rowId) {
        // Find the row with the matching ID
        const rows = document.querySelectorAll('tbody tr'); //
        let rowToDelete = null; //
        
        rows.forEach(row => { //
            const firstCell = row.querySelector('td:first-child'); //
            if (firstCell && firstCell.textContent === rowId) { //
                rowToDelete = row; //
            }
        });
        
        if (rowToDelete) { //
            // Check if there's an action row that follows
            const nextRow = rowToDelete.nextElementSibling; //
            if (nextRow && nextRow.classList.contains('action-row')) { //
                nextRow.remove(); //
            }
            
            // Remove the row
            rowToDelete.remove(); //
            
            // Here you would typically make an AJAX call to delete from the server
            console.log(`Row ${rowId} deleted`); //
            
            // Update pagination if needed
            if (typeof displayRows === 'function') { //
                displayRows(); //
            }
        }
        
        // Close the modal
        closeDeleteModal(); //
    }

    // Function to handle responsive table behavior
    function setupResponsiveTable() {
        const tableRows = document.querySelectorAll('.data-table-container tbody tr:not(.action-row)'); //
        
        // First, clean up any existing action rows to prevent duplication
        const existingActionRows = document.querySelectorAll('.action-row'); //
        existingActionRows.forEach(row => row.remove()); //
        
        // For each data row, create a corresponding action row
        tableRows.forEach(row => { //
            // Get the row ID (for data-row-id attributes)
            const rowId = row.querySelector('td:first-child').textContent; //
            
            // Get the action buttons from the last cell
            const actionCell = row.querySelector('td:last-child'); //
            if (!actionCell) return; // Skip if no action cell //
            
            const actionButtons = actionCell.querySelector('.action-buttons'); //
            if (!actionButtons) return; // Skip if no buttons //
            
            // Add data-row-id to each original button
            const originalButtons = actionButtons.querySelectorAll('button'); //
            originalButtons.forEach(button => { //
                button.setAttribute('data-row-id', rowId); //
            });
            
            // Create a new row for actions
            const actionRow = document.createElement('tr'); //
            actionRow.className = 'action-row'; //
            actionRow.style.display = window.innerWidth <= 992 ? 'table-row' : 'none'; //
            
            // Create a cell that spans all columns
            const actionCell2 = document.createElement('td'); //
            actionCell2.colSpan = 5; // span all visible columns //
            
            // Create a container for the buttons
            const buttonContainer = document.createElement('div'); //
            buttonContainer.className = 'action-buttons-responsive'; //
            
            // Clone the buttons
            buttonContainer.innerHTML = actionButtons.innerHTML; //
            
            // Add data-row-id to each cloned button
            const clonedButtons = buttonContainer.querySelectorAll('button'); //
            clonedButtons.forEach(button => { //
                button.setAttribute('data-row-id', rowId); //
            });
            
            // Add the buttons to the cell
            actionCell2.appendChild(buttonContainer); //
            
            // Add the cell to the row
            actionRow.appendChild(actionCell2); //
            
            // Insert the action row after the current row
            row.parentNode.insertBefore(actionRow, row.nextSibling); //
        });
    }
    
    // Add event delegation to the whole table
    document.querySelector('.data-table-container').addEventListener('click', function(event) {
        // Check if a button was clicked
        const button = event.target.closest('button'); //
        
        if (!button) return; // Not a button click //
        
        // Get the row ID from the button
        const rowId = button.getAttribute('data-row-id'); //
        if (!rowId) return; // No row ID data attribute //
        
        // Detail button
        if (button.classList.contains('btn-detail')) { //
            // Find the row by directly searching for a cell with matching content
            let row = null; //
            const allRows = document.querySelectorAll('.data-table-container tbody tr:not(.action-row)'); //
            for (let i = 0; i < allRows.length; i++) { //
                const firstCell = allRows[i].querySelector('td:first-child'); //
                if (firstCell && firstCell.textContent === rowId) { //
                    row = allRows[i]; //
                    break;
                }
            }
            
            if (row) { //
                // Extract data from the row
                const rowData = { //
                    nama: row.querySelector('td:nth-child(2)').textContent, //
                    kategori: row.querySelector('td:nth-child(3)').textContent, //
                    deskripsi: row.querySelector('td:nth-child(4)').textContent, //
                    tanggal: row.querySelector('td:nth-child(5)').textContent, //
                    media: button.getAttribute('data-media') // Get media URL from data attribute
                };
                
                // Open the modal with this data
                openDetailModal(rowData); //
            }
        }
        
        // Accept button
        else if (button.classList.contains('btn-accept')) { //
            const currentStatus = button.getAttribute('data-current-status'); //
            handleStatusUpdate(rowId, currentStatus, button); //
        }
        
        // Delete button
        else if (button.classList.contains('btn-delete')) { //
            openDeleteModal(rowId); //
        }
    });
    
    // Setup responsive table
    setupResponsiveTable(); //
    
    // Also run when window is resized
    window.addEventListener('resize', function() { //
        const actionRows = document.querySelectorAll('.action-row'); //
        
        // Show/hide action rows based on window width
        actionRows.forEach(row => { //
            row.style.display = window.innerWidth <= 992 ? 'table-row' : 'none'; //
        });
        
        // Show/hide the regular action column
        const actionCells = document.querySelectorAll('th:nth-child(6), td:nth-child(6)'); //
        actionCells.forEach(cell => { //
            cell.style.display = window.innerWidth <= 992 ? 'none' : 'table-cell'; //
        });
    });
    
    // Close modals with close buttons
    if (closeModalBtn) { //
        closeModalBtn.addEventListener('click', closeDetailModal); //
    }
    
    if (closeDetailBtn) { //
        closeDetailBtn.addEventListener('click', closeDetailModal); //
    }
    
    if (cancelDeleteBtn) { //
        cancelDeleteBtn.addEventListener('click', closeDeleteModal); //
    }
    
    // Handle delete confirmation
    if (confirmDeleteBtn) { //
        confirmDeleteBtn.addEventListener('click', function() { //
            const rowId = deleteModal.dataset.rowId; //
            handleDelete(rowId); //
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) { //
        if (event.target === detailModal) { //
            closeDetailModal(); //
        } else if (event.target === deleteModal) { //
            closeDeleteModal(); //
        } else if (event.target === logoutModal) { //
            closeLogoutModal(); //
        }
    });
    
    // Handle escape key to close modals
    document.addEventListener('keydown', function(event) { //
        if (event.key === 'Escape') { //
            closeDetailModal(); //
            closeDeleteModal(); //
            closeLogoutModal(); //
        }
    });
    
    // Pagination functionality
    const tableRows = document.querySelectorAll('.data-table-container tbody tr:not(.action-row)'); //
    const rowsPerPage = 10; // Maximum 10 items per page //
    let currentPage = 1; //
    const totalPages = Math.ceil(tableRows.length / rowsPerPage); //
    
    // Pagination buttons
    const prevButton = document.querySelector('.pagination-btn:first-child'); //
    const nextButton = document.querySelector('.pagination-btn:last-child'); //
    const pageNumbersContainer = document.getElementById('page-numbers'); //
    
    // Function to display rows for current page
    function displayRows() {
        const startIndex = (currentPage - 1) * rowsPerPage; //
        const endIndex = startIndex + rowsPerPage; //
        
        // Hide all rows first
        tableRows.forEach((row, index) => { //
            row.style.display = 'none'; //
            
            // If there's an action row after this row, hide it too
            const nextRow = row.nextElementSibling; //
            if (nextRow && nextRow.classList.contains('action-row')) { //
                nextRow.style.display = 'none'; //
            }
        });
        
        // Show only rows for current page
        for (let i = startIndex; i < endIndex && i < tableRows.length; i++) { //
            tableRows[i].style.display = 'table-row'; //
            
            // If there's an action row after this row, show it too if necessary
            const nextRow = tableRows[i].nextElementSibling; //
            if (nextRow && nextRow.classList.contains('action-row')) { //
                nextRow.style.display = window.innerWidth <= 992 ? 'table-row' : 'none'; //
            }
        }
        
        // Update page number display
        updatePageNumbers(); //
        
        // Disable/enable prev/next buttons
        prevButton.disabled = currentPage === 1; //
        nextButton.disabled = currentPage === totalPages; //
        
        // Visual indication for disabled buttons
        prevButton.style.opacity = currentPage === 1 ? '0.5' : '1'; //
        nextButton.style.opacity = currentPage === totalPages ? '0.5' : '1'; //
    }
    
    // Update page numbers display
    function updatePageNumbers() {
        if (pageNumbersContainer) { //
            pageNumbersContainer.innerHTML = ''; //
            
            // Create page number buttons
            for (let i = 1; i <= totalPages; i++) { //
                const pageBtn = document.createElement('button'); //
                pageBtn.className = 'pagination-btn page-number'; //
                if (i === currentPage) { //
                    pageBtn.classList.add('active'); //
                }
                pageBtn.textContent = i; //
                
                pageBtn.addEventListener('click', function() { //
                    currentPage = i; //
                    displayRows(); //
                });
                
                pageNumbersContainer.appendChild(pageBtn); //
            }
        }
        
        // Update page info text
        const pageInfo = document.getElementById('page-info'); //
        if (pageInfo) { //
            pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`; //
        }
    }
    
    // Event listeners for pagination buttons
    if (prevButton) { //
        prevButton.addEventListener('click', function() { //
            if (currentPage > 1) { //
                currentPage--; //
                displayRows(); //
            }
        });
    }
    
    if (nextButton) { //
        nextButton.addEventListener('click', function() { //
            if (currentPage < totalPages) { //
                currentPage++; //
                displayRows(); //
            }
        });
    }
    
    // Initialize pagination
    displayRows(); //

    // Filter functionality
    const kategoriFilter = document.getElementById('kategori-filter'); //
    const statusFilter = document.getElementById('status-filter'); //

    function applyFilters() {
        const selectedKategori = kategoriFilter.value; //
        const selectedStatus = statusFilter.value; //

        window.location.href = `{{ route('admin.laporan.index') }}?kategori=${selectedKategori}&status=${selectedStatus}`;
    }

    if (kategoriFilter) {
        kategoriFilter.addEventListener('change', applyFilters); //
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters); //
    }

    // Set filter selections based on URL parameters on page load
    const urlParams = new URLSearchParams(window.location.search); //
    const urlKategori = urlParams.get('kategori'); //
    const urlStatus = urlParams.get('status'); //

    if (urlKategori && kategoriFilter) {
        kategoriFilter.value = urlKategori; //
    }
    if (urlStatus && statusFilter) {
        statusFilter.value = urlStatus; //
    }

    // Handle delete button click for actual deletion (using Fetch API)
    document.querySelector('.data-table-container').addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.btn-delete');
        if (deleteButton) {
            const reportId = deleteButton.getAttribute('data-row-id');
            openDeleteModal(reportId);
        }
    });

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const reportId = deleteModal.dataset.rowId;
            fetch(`/admin/laporan/${reportId}/delete`, { // Assuming you'll add this route
                method: 'POST', // or DELETE, depending on your route definition
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Failed to delete report'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Laporan berhasil dihapus.');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Gagal menghapus laporan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting report:', error);
                alert('Terjadi kesalahan saat menghapus laporan.');
            })
            .finally(() => {
                closeDeleteModal();
            });
        });
    }

});