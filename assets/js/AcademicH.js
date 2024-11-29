document.getElementById('academic-help-form').addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Your question has been submitted!');
  });
  
  
// Select all FAQ question elements
document.querySelectorAll('.faq-question').forEach(question => {
  question.addEventListener('click', () => {
    const faqItem = question.parentElement;
    faqItem.classList.toggle('active');
  });
});