
const counter = document.querySelector(".counter");
const target = +counter.getAttribute("data-target"); // Get the target value from data attribute

// Function to increment the counter
function incrementCounter() {
  const currentCount = +counter.innerText.replace("+", ""); // Get current value (remove "+")
  const increment = target / 100; // Determine how much to increase each time

  if (currentCount < target) {
    counter.innerText = Math.ceil(currentCount + increment) + "+"; // Increment by step value
    setTimeout(incrementCounter, 2000); // Call the function every 200ms for smooth animation
  } else {
    counter.innerText = target + "+"; // Ensure final target value is displayed
  }
}

// Start counter animation and repeat every 20 seconds
setInterval(() => {
  counter.innerText = "0+"; // Reset the counter to 0 before each new cycle
  incrementCounter(); // Start the increment again
}, 20000); // 20000ms = 20 seconds

// Initially start the increment
incrementCounter();
