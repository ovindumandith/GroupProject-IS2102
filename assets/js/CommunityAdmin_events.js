// CommunityAdmin_notifications.js

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    // Check for status messages in URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    // Show appropriate alert based on status
    if (status) {
        switch(status) {
            case 'success':
                showAlert('Event added successfully!', 'success');
                break;
            case 'fail':
                showAlert('Failed to add event. Please try again.', 'error');
                break;
            case 'invalid':
                showAlert('Please fill in all required fields.', 'warning');
                break;
            case 'deleted':
                showAlert('Event deleted successfully!', 'success');
                break;
            case 'deletefail':
                showAlert('Failed to delete event. Please try again.', 'error');
                break;
            case 'updated':
                showAlert('Event updated successfully!', 'success');
                break;
            case 'updatefail':
                showAlert('Failed to update event. Please try again.', 'error');
                break;
        }
    }
    
    // Confirm before deleting an event
    const deleteForms = document.querySelectorAll('form[onsubmit]');
    deleteForms.forEach(form => {
        form.onsubmit = function(e) {
            if (!confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
            return true;
        };
    });
    
    // Form validation for adding/editing events
    const eventForm = document.querySelector('.notification-form');
    if (eventForm) {
        eventForm.onsubmit = function(e) {
            const title = document.getElementById('title').value.trim();
            const date = document.getElementById('date').value.trim();
            const description = document.getElementById('description').value.trim();
            const category = document.getElementById('category').value.trim();
            
            if (!title || !date || !description || !category) {
                showAlert('Please fill in all required fields.', 'warning');
                e.preventDefault();
                return false;
            }
            
            // Validate date is in the future
            const eventDate = new Date(date);
            const now = new Date();
            if (eventDate < now) {
                showAlert('Event date must be in the future.', 'warning');
                e.preventDefault();
                return false;
            }
            
            return true;
        };
    }
});

// Function to show custom alert
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert ${type}`;
    alertDiv.textContent = message;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.classList.add('fade-out');
        setTimeout(() => {
            alertDiv.remove();
        }, 500);
    }, 5000);
}

// Search functionality
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#eventsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}