// Testimonial Slider JavaScript
let currentTestimonialSlide = 0;
const testimonialSlides = document.querySelectorAll(".testimonial");
const testimonialPrevBtn = document.querySelector(".prev");
const testimonialNextBtn = document.querySelector(".next");

function showTestimonialSlide(index) {
  testimonialSlides.forEach((slide, i) => {
    slide.classList.remove("active");
    if (i === index) {
      slide.classList.add("active");
    }
  });
}

testimonialPrevBtn.addEventListener("click", () => {
  currentTestimonialSlide =
    currentTestimonialSlide > 0
      ? currentTestimonialSlide - 1
      : testimonialSlides.length - 1;
  showTestimonialSlide(currentTestimonialSlide);
});

testimonialNextBtn.addEventListener("click", () => {
  currentTestimonialSlide =
    currentTestimonialSlide < testimonialSlides.length - 1
      ? currentTestimonialSlide + 1
      : 0;
  showTestimonialSlide(currentTestimonialSlide);
});

let autoTestimonialSlide = setInterval(() => {
  currentTestimonialSlide =
    currentTestimonialSlide < testimonialSlides.length - 1
      ? currentTestimonialSlide + 1
      : 0;
  showTestimonialSlide(currentTestimonialSlide);
}, 5000);

document
  .querySelector(".testimonial-slider")
  .addEventListener("mouseenter", () => {
    clearInterval(autoTestimonialSlide);
  });

document
  .querySelector(".testimonial-slider")
  .addEventListener("mouseleave", () => {
    autoTestimonialSlide = setInterval(() => {
      currentTestimonialSlide =
        currentTestimonialSlide < testimonialSlides.length - 1
          ? currentTestimonialSlide + 1
          : 0;
      showTestimonialSlide(currentTestimonialSlide);
    }, 5000);
  });
