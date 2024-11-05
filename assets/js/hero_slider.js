// Hero Slider JavaScript
let currentHeroSlide = 0;
const heroSlides = document.querySelectorAll(".image-slide");
const heroPrevBtn = document.querySelector(".prev");
const heroNextBtn = document.querySelector(".next");

function showHeroSlide(index) {
  heroSlides.forEach((slide, i) => {
    slide.classList.remove("active");
    if (i === index) {
      slide.classList.add("active");
    }
  });
}

heroPrevBtn.addEventListener("click", () => {
  currentHeroSlide =
    currentHeroSlide > 0 ? currentHeroSlide - 1 : heroSlides.length - 1;
  showHeroSlide(currentHeroSlide);
});

heroNextBtn.addEventListener("click", () => {
  currentHeroSlide =
    currentHeroSlide < heroSlides.length - 1 ? currentHeroSlide + 1 : 0;
  showHeroSlide(currentHeroSlide);
});

let autoHeroSlide = setInterval(() => {
  currentHeroSlide =
    currentHeroSlide < heroSlides.length - 1 ? currentHeroSlide + 1 : 0;
  showHeroSlide(currentHeroSlide);
}, 5000);

document.querySelector(".image-slider").addEventListener("mouseenter", () => {
  clearInterval(autoHeroSlide);
});

document.querySelector(".image-slider").addEventListener("mouseleave", () => {
  autoHeroSlide = setInterval(() => {
    currentHeroSlide =
      currentHeroSlide < heroSlides.length - 1 ? currentHeroSlide + 1 : 0;
    showHeroSlide(currentHeroSlide);
  }, 5000);
});
