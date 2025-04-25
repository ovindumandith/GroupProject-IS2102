/**
 * RelaxU - Counselor Listing Page JavaScript
 * This script handles the functionality for the counselor listing page
 */

document.addEventListener("DOMContentLoaded", function () {
  // Elements
  const searchInput = document.getElementById("searchInput");
  const typeFilter = document.getElementById("typeFilter");
  const showFiltersBtn = document.getElementById("showFiltersBtn");
  const advancedFilters = document.getElementById("advancedFilters");
  const quickContactButtons = document.querySelectorAll(".quick-contact-btn");
  const scheduleModal = document.getElementById("scheduleModal");
  const closeModal = document.querySelector(".close-modal");
  const scheduleForm = document.getElementById("scheduleForm");
  const counselorCards = document.querySelectorAll(".counselor-card");

  // Initialize animations
  initializeAnimations();

  // Filter functionality
  if (searchInput) {
    searchInput.addEventListener("input", filterCounselors);
  }

  if (typeFilter) {
    typeFilter.addEventListener("change", filterCounselors);
  }

  // Toggle advanced filters
  if (showFiltersBtn && advancedFilters) {
    showFiltersBtn.addEventListener("click", function () {
      advancedFilters.classList.toggle("show");

      // Change button text based on state
      if (advancedFilters.classList.contains("show")) {
        showFiltersBtn.innerHTML = '<i class="fas fa-times"></i> Hide Filters';
      } else {
        showFiltersBtn.innerHTML =
          '<i class="fas fa-sliders"></i> More Filters';
      }
    });
  }

  // Modal functionality for quick contact
  if (quickContactButtons.length > 0 && scheduleModal) {
    quickContactButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const counselorId = this.getAttribute("data-id");

        // You could use the counselor ID to pre-fill information or fetch data
        // For now, we'll just open the modal
        openModal();
      });
    });

    // Close modal when clicking the X
    if (closeModal) {
      closeModal.addEventListener("click", closeModalFunction);
    }

    // Close modal when clicking outside the content
    window.addEventListener("click", function (event) {
      if (event.target === scheduleModal) {
        closeModalFunction();
      }
    });

    // Handle schedule form submission
    if (scheduleForm) {
      scheduleForm.addEventListener("submit", function (event) {
        event.preventDefault();

        // Here you would typically send the data to the server
        // For demo purposes, we'll just show an alert and close the modal

        // Create a toast notification
        showToast("Appointment scheduled successfully!", "success");

        // Close the modal
        closeModalFunction();

        // Reset the form
        scheduleForm.reset();
      });
    }
  }

  // Function to filter counselors based on search and filters
  function filterCounselors() {
    const searchText = searchInput ? searchInput.value.toLowerCase() : "";
    const selectedType = typeFilter ? typeFilter.value : "all";

    counselorCards.forEach((card) => {
      const name = card
        .querySelector(".counselor-name")
        .textContent.toLowerCase();
      const type = card.getAttribute("data-type").toLowerCase();
      const specialization = card
        .querySelector(".specialization .value")
        .textContent.toLowerCase();
      const description = card.querySelector(".description")
        ? card.querySelector(".description").textContent.toLowerCase()
        : "";

      // Check if card matches search text
      const matchesSearch =
        name.includes(searchText) ||
        specialization.includes(searchText) ||
        description.includes(searchText);

      // Check if card matches type filter
      const matchesType =
        selectedType === "all" ||
        type.toLowerCase() === selectedType.toLowerCase();

      // Show or hide card based on filters
      if (matchesSearch && matchesType) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });

    // Check if no results are found
    checkNoResults();
  }

  // Function to check if no results are found and display a message
  function checkNoResults() {
    let visibleCards = 0;

    counselorCards.forEach((card) => {
      if (card.style.display !== "none") {
        visibleCards++;
      }
    });

    // Remove existing no results message if any
    const existingNoResults = document.querySelector(".no-results");
    if (existingNoResults) {
      existingNoResults.remove();
    }

    // Display no results message if no cards are visible
    if (visibleCards === 0) {
      const counselorList = document.querySelector(".counselor-list");

      const noResults = document.createElement("div");
      noResults.className = "no-results";
      noResults.innerHTML = `
                <img src="/GroupProject-IS2102/assets/images/no-results.svg" alt="No counselors found">
                <h3>No Counselors Found</h3>
                <p>We couldn't find any counselors matching your search criteria. Please try adjusting your filters or search term.</p>
            `;

      counselorList.appendChild(noResults);
    }
  }

  // Function to open the schedule modal
  function openModal() {
    if (scheduleModal) {
      scheduleModal.classList.add("show");
      document.body.style.overflow = "hidden"; // Prevent scrolling
    }
  }

  // Function to close the schedule modal
  function closeModalFunction() {
    if (scheduleModal) {
      scheduleModal.classList.remove("show");
      document.body.style.overflow = ""; // Re-enable scrolling
    }
  }

  // Function to show toast notification
  function showToast(message, type = "info") {
    // Create toast element
    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;

    // Add icon based on type
    let icon = "info-circle";
    if (type === "success") icon = "check-circle";
    if (type === "error") icon = "exclamation-circle";
    if (type === "warning") icon = "exclamation-triangle";

    toast.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
            <button class="toast-close"><i class="fas fa-times"></i></button>
        `;

    // Add to document
    document.body.appendChild(toast);

    // Show toast with animation
    setTimeout(() => {
      toast.classList.add("show");
    }, 10);

    // Add close button functionality
    const closeBtn = toast.querySelector(".toast-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        toast.classList.remove("show");
        setTimeout(() => {
          toast.remove();
        }, 300);
      });
    }

    // Auto-close after 5 seconds
    setTimeout(() => {
      if (document.body.contains(toast)) {
        toast.classList.remove("show");
        setTimeout(() => {
          if (document.body.contains(toast)) {
            toast.remove();
          }
        }, 300);
      }
    }, 5000);
  }

  // Function to initialize animations for elements
  function initializeAnimations() {
    // Animate counselor cards on scroll
    const fadeInElements = document.querySelectorAll(
      ".counselor-card, .page-header, .filter-section, .help-section"
    );

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("fade-in");
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
      }
    );

    fadeInElements.forEach((element) => {
      observer.observe(element);
    });
  }

  // Add CSS for animations and toast
  addDynamicStyles();

  // Function to add dynamic styles
  function addDynamicStyles() {
    const style = document.createElement("style");
    style.textContent = `
            .fade-in {
                animation: fadeIn 0.6s ease forwards;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Toast Notifications */
            .toast {
                position: fixed;
                bottom: 20px;
                right: 20px;
                max-width: 350px;
                background-color: #333;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                z-index: 9999;
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            }
            
            .toast.show {
                opacity: 1;
                transform: translateY(0);
            }
            
            .toast i {
                margin-right: 10px;
                font-size: 1.2rem;
            }
            
            .toast-success {
                background-color: #009f77;
            }
            
            .toast-error {
                background-color: #e74c3c;
            }
            
            .toast-warning {
                background-color: #f39c12;
            }
            
            .toast-close {
                margin-left: 15px;
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.3s;
            }
            
            .toast-close:hover {
                opacity: 1;
            }
        `;

    document.head.appendChild(style);
  }
});
