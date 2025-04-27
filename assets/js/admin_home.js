/**
 * RelaxU Admin Dashboard JavaScript
 * Handles chart initialization and dashboard interactions
 */

document.addEventListener("DOMContentLoaded", function () {
  // Define color schemes based on RelaxU branding
  const colors = {
    primary: "#009f77",
    primaryLight: "#e8f1ec",
    primaryDark: "#007a5c",
    secondary: "#fa8128",
    secondaryDark: "#e87217",
    adminDark: "#2c3e50",
    adminDarker: "#1a2530",
    adminLight: "#34495e",
    white: "#fff",
    red: "#e74c3c",
    green: "#2ecc71",
    blue: "#3498db",
    yellow: "#f1c40f",
    orange: "#e67e22",
    purple: "#9b59b6",
  };

  // Custom chart colors for different data types
  const chartColors = {
    userRoles: [
      colors.green, // Student
      colors.red, // Admin
      colors.blue, // Lecturer
      colors.purple, // HOUS
      colors.orange, // Counselor
      colors.adminDark, // Super Admin
    ],
    stressLevels: [
      colors.green, // Low
      colors.yellow, // Moderate
      colors.red, // High
    ],
    appointments: [
      colors.yellow, // Pending
      colors.green, // Accepted
      colors.red, // Denied
    ],
  };

  // Common chart options
  const commonChartOptions = {
    tooltipBackground: colors.white,
    tooltipBodyColor: colors.adminLight,
    tooltipTitleColor: colors.adminDark,
    fontFamily: "'Poppins', sans-serif",
    gridColor: "#e9ecef",
  };

  // Initialize User Distribution Chart
  function initUserDistributionChart() {
    const userCtx = document.getElementById("userDistributionChart");
    if (!userCtx) return;

    const userRoleData = userChartData.values;
    const userRoleLabels = userChartData.labels;

    new Chart(userCtx, {
      type: "doughnut",
      data: {
        labels: userRoleLabels,
        datasets: [
          {
            data: userRoleData,
            backgroundColor: chartColors.userRoles,
            hoverBackgroundColor: chartColors.userRoles.map(
              (color) => color + "cc"
            ),
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "right",
            labels: {
              font: {
                family: commonChartOptions.fontFamily,
                size: 12,
              },
              padding: 20,
              usePointStyle: true,
              pointStyle: "circle",
            },
          },
          tooltip: {
            backgroundColor: commonChartOptions.tooltipBackground,
            bodyColor: commonChartOptions.tooltipBodyColor,
            titleColor: commonChartOptions.tooltipTitleColor,
            titleFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            bodyFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            borderColor: "#dddfeb",
            borderWidth: 1,
            displayColors: false,
            caretPadding: 10,
          },
        },
        cutout: "65%",
        elements: {
          arc: {
            borderWidth: 0,
          },
        },
      },
    });
  }

  // Initialize Stress Level Chart
  function initStressLevelChart() {
    const stressCtx = document.getElementById("stressLevelChart");
    if (!stressCtx) return;

    new Chart(stressCtx, {
      type: "bar",
      data: {
        labels: ["Low", "Moderate", "High"],
        datasets: [
          {
            label: "Number of Students",
            data: stressChartData,
            backgroundColor: chartColors.stressLevels,
            borderWidth: 0,
            borderRadius: 4,
            maxBarThickness: 60,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0,
          },
        },
        scales: {
          x: {
            grid: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              font: {
                family: commonChartOptions.fontFamily,
                size: 12,
              },
            },
          },
          y: {
            ticks: {
              min: 0,
              max: Math.max(...stressChartData) + 5,
              maxTicksLimit: 5,
              padding: 10,
              precision: 0,
              font: {
                family: commonChartOptions.fontFamily,
                size: 12,
              },
            },
            grid: {
              color: commonChartOptions.gridColor,
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2],
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            backgroundColor: commonChartOptions.tooltipBackground,
            bodyColor: commonChartOptions.tooltipBodyColor,
            titleMarginBottom: 10,
            titleColor: commonChartOptions.tooltipTitleColor,
            titleFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            bodyFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
              label: function (context) {
                return context.parsed.y + " students";
              },
            },
          },
        },
      },
    });
  }

  // Initialize Appointment Status Chart
  function initAppointmentStatusChart() {
    const appointmentCtx = document.getElementById("appointmentChart");
    if (!appointmentCtx) return;

    new Chart(appointmentCtx, {
      type: "pie",
      data: {
        labels: ["Pending", "Accepted", "Denied"],
        datasets: [
          {
            data: appointmentChartData,
            backgroundColor: chartColors.appointments,
            hoverBackgroundColor: chartColors.appointments.map(
              (color) => color + "cc"
            ),
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              font: {
                family: commonChartOptions.fontFamily,
                size: 12,
              },
              padding: 20,
              usePointStyle: true,
              pointStyle: "circle",
            },
          },
          tooltip: {
            backgroundColor: commonChartOptions.tooltipBackground,
            bodyColor: commonChartOptions.tooltipBodyColor,
            titleColor: commonChartOptions.tooltipTitleColor,
            titleFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            bodyFont: {
              family: commonChartOptions.fontFamily,
              size: 14,
            },
            borderColor: "#dddfeb",
            borderWidth: 1,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
              label: function (context) {
                return context.parsed + " appointments";
              },
            },
          },
        },
        elements: {
          arc: {
            borderWidth: 0,
          },
        },
      },
    });
  }

  // Initialize all charts
  function initCharts() {
    initUserDistributionChart();
    initStressLevelChart();
    initAppointmentStatusChart();
  }

  // Initialize dashboard interactions
  function initDashboardInteractions() {
    // Refresh button functionality
    const refreshBtn = document.getElementById("refresh-btn");
    if (refreshBtn) {
      refreshBtn.addEventListener("click", function () {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';

        // Simulate loading (in a real application, this would fetch fresh data via AJAX)
        setTimeout(() => {
          location.reload();
        }, 1000);
      });
    }

    // Add hover effects to stat cards
    const statCards = document.querySelectorAll(".stat-card");
    statCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-5px)";
        this.style.boxShadow = "0 8px 30px rgba(0, 0, 0, 0.15)";
      });

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0)";
        this.style.boxShadow = "0 4px 12px rgba(0, 0, 0, 0.1)";
      });
    });
  }

  // Initialize everything
  function init() {
    initCharts();
    initDashboardInteractions();
  }

  // Start initialization
  init();
});
