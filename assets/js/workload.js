

// Add event listeners for enhanced hover effects
document.addEventListener('DOMContentLoaded', function() {
  const card = document.querySelector('.service-card');
  const btn = document.querySelector('.read-more-btn');
  
  // Optional: Add more interactive elements if needed
  card.addEventListener('mouseenter', function() {
      btn.classList.add('btn-hover');
  });
  
  card.addEventListener('mouseleave', function() {
      btn.classList.remove('btn-hover');
  });
  
  // Optional: Add click event for the Read More button
  btn.addEventListener('click', function(e) {
      e.preventDefault();
      alert('More information about Assessment and Education would appear here.');
  });
});

