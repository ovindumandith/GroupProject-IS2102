document.addEventListener("DOMContentLoaded", () => {
  // Initialize modal
  const modal = new Modal();

  // Enhanced scroll animations for features
  const observerOptions = {
    threshold: 0.2,
    rootMargin: "0px 0px -100px 0px",
  };

  const featureObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate");
        // Stagger the animation of child elements
        const img = entry.target.querySelector("img");
        const title = entry.target.querySelector("h3");
        const desc = entry.target.querySelector("p");

        if (img) img.style.transitionDelay = "0.2s";
        if (title) title.style.transitionDelay = "0.4s";
        if (desc) desc.style.transitionDelay = "0.6s";
      }
    });
  }, observerOptions);

  // Observe feature cards with enhanced animations
  document.querySelectorAll(".feature-card").forEach((card, index) => {
    card.style.transitionDelay = `${index * 0.2}s`;
    featureObserver.observe(card);
  });

  // Team members and vision-mission animations
  const generalObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate");
      }
    });
  }, observerOptions);

  document
    .querySelectorAll(".team-member, .vision-mission h2, .vision-mission p")
    .forEach((element, index) => {
      element.style.animationDelay = `${index * 0.2}s`;
      generalObserver.observe(element);
    });

  // Smooth scroll behavior
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });
});
