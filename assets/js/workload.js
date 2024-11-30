document.addEventListener("DOMContentLoaded", () => {
  const images = [
    {
      src: "../../assets/images/workload/93.png", // Image 1
      title: "Trust in your ability to manage – you’ve got this!",
      subtitle: "Every challenge is an opportunity to grow."
    },
    {
      src: "../../assets/images/workload/93.png", // Image 2
      title: "Believe in yourself – you can achieve greatness!",
      subtitle: "Consistency is the key to success."
    }
  ];

  const imageElement = document.getElementById("headerImage");
  const titleElement = document.getElementById("mainTitle");
  const subtitleElement = document.getElementById("subtitle");

  let currentIndex = 0;

  // Function to change the image and text with a smooth transition
  const changeContent = () => {
    // Hide the current image and text
    imageElement.classList.remove('active');
    titleElement.classList.remove('active');
    subtitleElement.classList.remove('active');

    // Update the index and content
    currentIndex = (currentIndex + 1) % images.length; // Cycle through images
    const currentImage = images[currentIndex];

    // Delay to allow fade-out effect before changing content
    setTimeout(() => {
      // Set new image and text content
      imageElement.src = currentImage.src;
      titleElement.textContent = currentImage.title;
      subtitleElement.textContent = currentImage.subtitle;

      // Show the new image and text
      imageElement.classList.add('active');
      titleElement.classList.add('active');
      subtitleElement.classList.add('active');
    }, 2000); // Match with the CSS transition duration
  };

  // Set initial content
  changeContent();

  // Change the content every 5 seconds
  setInterval(changeContent, 5000);
});
