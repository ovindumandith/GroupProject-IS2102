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
