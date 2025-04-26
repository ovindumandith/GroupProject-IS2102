document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.card');
  const stressPopup = document.getElementById('stress-check-popup');
  const overlay = document.createElement('div'); // Create overlay element
  overlay.id = 'overlay';
  document.body.appendChild(overlay);
  
  let targetUrl = '';

  // Add data-url attributes to cards (should be in HTML)
  document.querySelectorAll('.card').forEach((card, index) => {
      const urls = [
          '../../App/views/low_level_relaxation_activities.php',
          '../../App/views/moderate_level_relaxation_activities.php',
          '../../App/views/high_level_relaxation_activities.php'
      ];
      card.dataset.url = urls[index];

  // Card click handler
  cards.forEach(card => {
      card.addEventListener('click', function(e) {
          e.preventDefault();
          targetUrl = this.dataset.url;
          
          // Show popup and overlay
          stressPopup.style.display = 'block';
          overlay.style.display = 'block';
          
          // Check storage only if you want persistent state
          // if (!localStorage.getItem('stressLevelChecked')) {
          //     stressPopup.style.display = 'block';
          // } else {
          //     window.location.href = targetUrl;
          // }
      });
  });

  //Proceed button handler
  document.getElementById('proceed-btn').addEventListener('click', function(e) {
      e.preventDefault();
      stressPopup.style.display = 'none';
      overlay.style.display = 'none';
      if(targetUrl) {
          window.location.href = targetUrl;
      }
  });

  // Check stress level button
  document.getElementById('check-stress-btn').addEventListener('click', function(e) {
      e.preventDefault();
      localStorage.setItem('stressLevelChecked', 'true');
      window.location.href = '../../App/views/stress_management/stress_management_form.php';
  });

  // Close popup when clicking overlay
  overlay.addEventListener('click', function() {
      stressPopup.style.display = 'none';
      this.style.display = 'none';
  });
});



  });