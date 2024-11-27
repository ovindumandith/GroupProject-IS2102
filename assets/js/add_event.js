// Get elements
const openFormButton = document.getElementById('openFormButton');
const popupForm = document.getElementById('popupForm');
const closeFormButton = document.getElementById('closeFormButton');
const eventForm = document.getElementById('eventForm');

// Open the popup
openFormButton.addEventListener('click', () => {
  popupForm.style.display = 'flex';
});

// Close the popup
closeFormButton.addEventListener('click', () => {
  popupForm.style.display = 'none';
});

// Close popup when clicking outside the form
window.addEventListener('click', (event) => {
  if (event.target === popupForm) {
    popupForm.style.display = 'none';
  }
});

// Handle form submission
eventForm.addEventListener('submit', (e) => {
  e.preventDefault();

  // Gather form data
  const title = document.getElementById('eventTitle').value;
  const description = document.getElementById('eventDescription').value;
  const date = document.getElementById('eventDate').value;
  const startTime = document.getElementById('startTime').value;
  const endTime = document.getElementById('endTime').value;

  // Log the event data (you can replace this with backend integration)
  console.log({
    title,
    description,
    date,
    startTime,
    endTime
  });

  alert('Event added successfully!');

  // Reset form and close popup
  eventForm.reset();
  popupForm.style.display = 'none';
});
