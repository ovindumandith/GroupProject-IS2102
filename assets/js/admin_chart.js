// Dummy data for the charts
const counselorData = {
  professional: 30,
  studentLevel: 20,
};

const studentsData = [500, 450, 550, 600, 650, 700, 750]; // Mock data for student registrations over time

// Function to create a simple pie chart (for Counselor Breakdown)
function createPieChart() {
  const chart = document.getElementById("counselor-chart");
  const totalCounselors =
    counselorData.professional + counselorData.studentLevel;

  const professionalPercentage =
    (counselorData.professional / totalCounselors) * 100;
  const studentLevelPercentage =
    (counselorData.studentLevel / totalCounselors) * 100;

  chart.innerHTML = `
        <div class="pie-chart-label" style="top: 20px; left: 20px;">Professional: ${Math.round(
          professionalPercentage
        )}%</div>
        <div class="pie-chart-label" style="top: 50px; left: 20px;">Student Level: ${Math.round(
          studentLevelPercentage
        )}%</div>
        <svg width="100%" height="100%" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" transform="rotate(-90)">
            <circle r="16" cx="16" cy="16" fill="transparent" stroke="#28a745" stroke-width="32" stroke-dasharray="${professionalPercentage} 100" class="pie-chart-segment professional"></circle>
            <circle r="16" cx="16" cy="16" fill="transparent" stroke="#dc3545" stroke-width="32" stroke-dasharray="${studentLevelPercentage} 100" stroke-dashoffset="${professionalPercentage}" class="pie-chart-segment student-level"></circle>
        </svg>
    `;
}

// Function to create a bar chart (for Students Registered Over Time)
function createBarChart() {
  const chart = document.getElementById("students-chart");
  const max = Math.max(...studentsData);

  chart.innerHTML = `
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            ${studentsData
              .map(
                (data, index) => `
                <rect x="${index * 40 + 10}" y="${
                  100 - (data / max) * 100
                }" width="30" height="${
                  (data / max) * 100
                }" fill="#007bff" class="bar"></rect>
            `
              )
              .join("")}
        </svg>
    `;
}

// Add hover effect for better interactivity on the charts
function addHoverEffects() {
  const bars = document.querySelectorAll(".bar");
  bars.forEach((bar) => {
    bar.addEventListener("mouseover", () => {
      bar.setAttribute("fill", "#0056b3");
    });
    bar.addEventListener("mouseout", () => {
      bar.setAttribute("fill", "#007bff");
    });
  });
}

// Initial chart setup
createPieChart();
createBarChart();
addHoverEffects();
