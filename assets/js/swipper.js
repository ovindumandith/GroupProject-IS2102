let currentSlide = 0;

// Select elements
const cardWrapper = document.querySelector('.card-wrapper');
const cards = document.querySelectorAll('.card');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

// Calculate the total number of slides
const totalSlides = cards.length;

// Function to update the swiper position
function updateSwiper() {
  const slideWidth = cards[0].clientWidth + parseInt(getComputedStyle(cards[0]).marginRight);
  const offset = -(currentSlide * slideWidth);
  cardWrapper.style.transform = `translateX(${offset}px)`;
}

// Event Listeners for navigation buttons
prevButton.addEventListener('click', () => {
  currentSlide--;
  if (currentSlide < 0) {
    currentSlide = totalSlides - 1; // Loop to last slide
  }
  updateSwiper();
});

nextButton.addEventListener('click', () => {
  currentSlide++;
  if (currentSlide >= totalSlides) {
    currentSlide = 0; // Loop to first slide
  }
  updateSwiper();
});

// Optional: Enable swipe gestures for touch devices
let startX, isDragging = false;

cardWrapper.addEventListener('touchstart', (e) => {
  startX = e.touches[0].clientX;
  isDragging = true;
});

cardWrapper.addEventListener('touchmove', (e) => {
  if (!isDragging) return;
  const currentX = e.touches[0].clientX;
  const diff = startX - currentX;

  if (Math.abs(diff) > 50) { // Swipe threshold
    if (diff > 0) { // Swipe left
      nextButton.click();
    } else { // Swipe right
      prevButton.click();
    }
    isDragging = false;
  }
});

cardWrapper.addEventListener('touchend', () => {
  isDragging = false;
});

// Optional: Enable keyboard navigation
document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') {
    prevButton.click();
  } else if (e.key === 'ArrowRight') {
    nextButton.click();
  }
});
