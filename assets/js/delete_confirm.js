// assets/js/confirmationModal.js
document.addEventListener('DOMContentLoaded', function() {
    let deleteForm = null;
    const modal = document.getElementById('confirmationModal');
    const cancelBtn = document.getElementById('cancelDelete');
    const confirmBtn = document.getElementById('confirmDelete');

    // Attach event listeners to all delete forms
    document.querySelectorAll('form.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            deleteForm = this;
            showModal();
        });
    });

    function showModal() {
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function hideModal() {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        deleteForm = null;
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', hideModal);
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {
            if (deleteForm) {
                deleteForm.removeEventListener('submit', arguments.callee);
                deleteForm.submit();
            }
            hideModal();
        });
    }

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            hideModal();
        }
    });
});