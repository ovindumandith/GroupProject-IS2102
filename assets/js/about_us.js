document.addEventListener("DOMContentLoaded", () => {
  // Animate statistics numbers
  const stats = document.querySelectorAll(".stat-number");

  const animateStats = () => {
    stats.forEach((stat) => {
      const target = parseInt(stat.getAttribute("data-target"));
      const duration = 2000; // Animation duration in milliseconds
      const step = target / (duration / 16); // 60 FPS
      let current = 0;

      const updateStat = () => {
        current += step;
        if (current < target) {
          stat.textContent = Math.round(current);
          requestAnimationFrame(updateStat);
        } else {
          stat.textContent = target;
        }
      };

      updateStat();
    });
  };

  // Intersection Observer for stats animation
  const statsObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateStats();
          statsObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  const statsContainer = document.querySelector(".stats-container");
  if (statsContainer) {
    statsObserver.observe(statsContainer);
  }

  // Add scroll reveal animation for team members
  const teamMembers = document.querySelectorAll(".team-member");
  const teamObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
          teamObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  teamMembers.forEach((member) => {
    member.style.opacity = "0";
    member.style.transform = "translateY(20px)";
    member.style.transition = "all 0.6s ease-out";
    teamObserver.observe(member);
  });
});
