document.addEventListener("DOMContentLoaded", () => {
  const images = [
    {
      src: "../../assets/images/workload/93.png",
      title: "Trust in your ability to manage – you’ve got this!",
      subtitle: "Every challenge is an opportunity to grow."
    },
    {
      src: "../../assets/images/workload/94.png",
      title: "Believe in yourself – you can achieve greatness!",
      subtitle: "Consistency is the key to success."
    }
  ];

  const imageElements = [
    document.getElementById("headerImage1"),
    document.getElementById("headerImage2")
  ];
  const titleElement = document.getElementById("mainTitle");
  const subtitleElement = document.getElementById("subtitle");
  const overlayText = document.querySelector(".overlay-text");

  let currentIndex = 0;
  let currentImageIndex = 0;

  const changeContent = () => {
    // Hide current image and text
    imageElements[currentImageIndex].classList.remove("active");
    overlayText.classList.remove("active");

    // Update the index
    currentIndex = (currentIndex + 1) % images.length;
    currentImageIndex = (currentImageIndex + 1) % imageElements.length;

    const nextImage = images[currentIndex];
    const nextImageElement = imageElements[currentImageIndex];

    // Delay for fade-out effect
    setTimeout(() => {
      // Update image and text
      nextImageElement.src = nextImage.src;
      titleElement.textContent = nextImage.title;
      subtitleElement.textContent = nextImage.subtitle;

      // Show the new image and text
      nextImageElement.classList.add("active");
      overlayText.classList.add("active");
    }, 0); // No gap between fade-out and fade-in

  };

  // Set initial content
  overlayText.classList.add("active");

  // Change the content every 5 seconds
  setInterval(changeContent, 5000);
});


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

document.addEventListener('DOMContentLoaded', function() {
  const typingTextElement = document.getElementById('typing-text');
  const phrases = [
      'Healthier, Happier Life',
      'Strength and Mobility',
      'Recovery and Wellness',
      'Balance and Flexibility'
  ];
  
  let phraseIndex = 0;
  let charIndex = 0;
  let isDeleting = false;
  let typingSpeed = 100; // Base typing speed in milliseconds
  
  function typeText() {
      const currentPhrase = phrases[phraseIndex];
      
      // If deleting, remove a character, otherwise add a character
      if (isDeleting) {
          typingTextElement.textContent = currentPhrase.substring(0, charIndex - 1);
          charIndex--;
      } else {
          typingTextElement.textContent = currentPhrase.substring(0, charIndex + 1);
          charIndex++;
      }
      
      // Adjust typing speed
      let speed = typingSpeed;
      
      if (isDeleting) {
          // Faster when deleting
          speed = typingSpeed / 2;
      } else if (charIndex === currentPhrase.length) {
          // Pause at the end of phrase
          speed = typingSpeed * 3;
          isDeleting = true;
      } else if (charIndex === 0) {
          // Move to next phrase when done deleting
          isDeleting = false;
          phraseIndex = (phraseIndex + 1) % phrases.length;
          // Pause before starting new phrase
          speed = typingSpeed * 1.5;
      }
      
      // Continue the typing loop
      setTimeout(typeText, speed);
  }
  
  // Start the typing animation
  typeText();
});