
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("notificationsTable").getElementsByTagName('tbody')[0];

    searchInput.addEventListener("keyup", function() {
      const filter = this.value.toLowerCase();
      const rows = table.getElementsByTagName("tr");

      for (let i = 0; i < rows.length; i++) {
        const text = rows[i].innerText.toLowerCase();
        rows[i].style.display = text.includes(filter) ? "" : "none";
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      // Delete notification buttons
      const deleteButtons = document.querySelectorAll('.delete-notification-btn');
      
      deleteButtons.forEach(button => {
          button.addEventListener('click', function() {
              const form = this.closest('.delete-notification-form');
              const notificationId = form.dataset.id;
              
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Submit the form
                      form.submit();
                      
                      // Optional: Show success message after deletion
                      // The actual success/failure will be handled by the PHP redirect
                  }
              });
          });
      });
  
      // Handle status messages from PHP
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');
      
      if (status) {
          let title, icon;
          
          switch(status) {
              case 'deleted':
                  title = 'Deleted!';
                  text = 'The notification has been deleted.';
                  icon = 'success';
                  break;
              case 'deletefail':
                  title = 'Error!';
                  text = 'Failed to delete notification.';
                  icon = 'error';
                  break;
              case 'success':
                  title = 'Success!';
                  text = 'Notification sent and post deleted successfully.';
                  icon = 'success';
                  break;
              case 'fail':
                  title = 'Error!';
                  text = 'Failed to send notification.';
                  icon = 'error';
                  break;
              case 'invalid':
                  title = 'Warning!';
                  text = 'Please fill in all fields.';
                  icon = 'warning';
                  break;
              case 'updated':
                  title = 'Updated!';
                  text = 'Notification updated successfully.';
                  icon = 'success';
                  break;
              case 'updatefail':
                  title = 'Error!';
                  text = 'Failed to update notification.';
                  icon = 'error';
                  break;
          }
          
          if (title) {
              Swal.fire({
                  title: title,
                  text: text,
                  icon: icon,
                  confirmButtonText: 'OK'
              }).then(() => {
                  // Remove the status parameter from URL
                  const cleanUrl = window.location.pathname;
                  window.history.replaceState({}, document.title, cleanUrl);
              });
          }
      }
  });