document.addEventListener('DOMContentLoaded', function() {
    // Initialize form with today's date
    const dateInput = document.getElementById('reportDate');
    if (dateInput) {
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        dateInput.value = `${yyyy}-${mm}-${dd}`;
    }

    // Character counter for description
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');

    if (descriptionInput && charCount) {
        descriptionInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;

            // Change color when approaching limit
            if (currentLength > 100) {
                charCount.style.color = 'var(--warning-color)';
            } else if (currentLength > 110) {
                charCount.style.color = 'var(--error-color)';
            } else {
                charCount.style.color = '';
            }
        });
    }

    // Custom file input styling
    const fileInput = document.getElementById('mediaUpload');
    const fileButton = document.querySelector('.file-upload-btn');

    if (fileInput && fileButton) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileButton.textContent = `Selected: ${this.files[0].name}`;
            } else {
                fileButton.textContent = 'Choose Files';
            }
        });
    }

    // --- IMPORTANT CHANGES BELOW ---

    // Remove the event listener for handleFormSubmit on the form.
    // The form will now submit directly to Laravel's route.
    // const reportForm = document.getElementById('reportForm');
    // if (reportForm) {
    //     reportForm.removeEventListener('submit', handleFormSubmit); // Removed this line
    // }

    // Remove modal handling for success. Laravel will handle redirection.
    const okButton = document.getElementById('okButton');
    if (okButton) {
        okButton.addEventListener('click', function() {
            toggleModal('successModal', false);
            // No client-side redirect here. Laravel's controller handles it.
        });
    }
});

// Remove the handleFormSubmit, saveReport, and getData functions,
// as they are no longer needed for server-side form submission.
// Laravel's controller will handle validation, saving, and redirection.

/*
// Original handleFormSubmit function - REMOVE OR COMMENT OUT THIS ENTIRE FUNCTION BLOCK
function handleFormSubmit(event) {
    // event.preventDefault(); // This line MUST be removed if you want Laravel to process the form

    // Get form data
    const formData = new FormData(event.target);
    const reporterName = formData.get('reporterName');
    const date = formData.get('reportDate');
    const category = formData.get('category');
    const description = formData.get('description');
    const file = document.getElementById('mediaUpload').files[0];

    // Validate form data (This can primarily be handled by Laravel validation)
    if (!date) {
        showError('Tanggal harus diisi', document.getElementById('reportDate'));
        return;
    }

    // Note: 'category' name attribute in HTML is 'kategori_id'
    // const categoryValue = formData.get('kategori_id'); // Corrected name
    if (!formData.get('kategori_id')) { // Use the correct name from Blade
        showError('Kategori harus dipilih', document.getElementById('category'));
        return;
    }

    if (!description) {
        showError('Deskripsi harus diisi', document.getElementById('description'));
        return;
    }

    // Create report object - NOT NEEDED for server-side submission
    // const report = {
    //     id: Date.now().toString(),
    //     reporterName,
    //     date,
    //     category,
    //     description,
    //     imageUrl: file ? URL.createObjectURL(file) : null,
    //     status: 'Diproses',
    //     createdAt: new Date().toISOString()
    // };

    // Save report to localStorage - NOT NEEDED, Laravel handles DB saving
    // saveReport(report);

    // Show success modal - NOT NEEDED for direct server-side redirect
    // toggleModal('successModal', true);
}

// Original saveReport function - REMOVE OR COMMENT OUT THIS ENTIRE FUNCTION BLOCK
function saveReport(report) {
    let reports = getData('reports') || [];
    reports.push(report);
    storeData('reports', reports);
}

// Original getData function - REMOVE OR COMMENT OUT THIS ENTIRE FUNCTION BLOCK
function getData(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}
*/