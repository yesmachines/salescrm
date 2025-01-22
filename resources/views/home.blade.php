@extends('layouts.default')

@section('content')
@if(auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin'))
<div class="container-xxl py-5">
  <div class="card-header text-white d-flex align-items-center justify-content-between" style="background-color: #33A4B1;">
    <h5 class="mb-0">Statistics for {{ now()->format('F Y') }}</h5>
    <i class="bi bi-graph-up-arrow"></i>
  </div>
  <div class="card shadow-sm border-0">

    <div class="card-body">
      <div class="row mb-5">
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header text-white" style="background-color: #33A4B1;">
              <h6 class="mb-0">Quotation Statistics</h6>
            </div>

            <div class="card-body">
              <div class="chart-container" style="height: 400px;">
                <canvas id="employeePieChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header text-white" style="background-color: #33A4B1;">
              <h6 class="mb-0">Order Performance</h6>
            </div>
            <div class="card-body">
              <div class="chart-container" style="height: 400px;">
                <canvas id="orderPieChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Yearly Insights Section -->
      <div class="row mb-5">
        <!-- Employee Yearly Pie Chart -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header text-white" style="background-color: #33A4B1;">
              <h6 class="mb-0">Yearly Quotation Statistics</h6>
            </div>
            <div class="card-body">
              <div class="chart-container" style="height: 400px;">
                <canvas id="employeeYearlyPieChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Yearly Pie Chart -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header text-white" style="background-color: #33A4B1;">
              <h6 class="mb-0">Yearly Order Performance</h6>
            </div>
            <div class="card-body">
              <div class="chart-container" style="height: 400px;">
                <canvas id="orderYearlyPieChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quotation Status Bar Chart -->
      <div class="row">
        <div class="col-md-12">
          <div class="card shadow-sm border-0">
            <div class="card-header text-white" style="background-color: #33A4B1;">
              <h6 class="mb-0">Quotation Status Overview</h6>
            </div>
            <div class="card-body">
              <div class="chart-container" style="height: 550px;">
                <canvas id="quotationStatusChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@else
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pg-header-wth-tab pt-7">
        <div class="d-flex">
            <div class="d-flex flex-wrap justify-content-between flex-1">
                <div class="mb-lg-0 mb-2 me-8">
                    <h1 class="pg-title">Welcome back</h1>
                    <p>Dashboard will be available soon with new updates !!</p>
                </div>

            </div>
        </div>

    </div>
    <!-- /Page Header -->

</div>
@endif


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const employeeQuotationStatus = @json($employeeQuotationStatus);
  const labels = employeeQuotationStatus.map(stat => stat.employee_name);
  const quotationCounts = employeeQuotationStatus.map(stat => stat.quotation_count);
  const totalMargins = employeeQuotationStatus.map(stat => stat.total_margin);
  const images = employeeQuotationStatus.map(stat => stat.employee_image);

  const gradientColors = [
    'rgba(75, 192, 192, 0.7)',  // Soft teal
    'rgba(54, 162, 235, 0.7)',  // Calm blue
    'rgba(153, 102, 255, 0.7)', // Muted purple
    'rgba(255, 159, 64, 0.7)',  // Warm orange
    'rgba(255, 99, 132, 0.7)',  // Soft pink
    'rgba(99, 255, 218, 0.7)',  // Light mint
    'rgba(0, 204, 255, 0.7)',  // Bright cyan
    'rgba(255, 205, 86, 0.7)',  // Light amber
    'rgba(204, 204, 255, 0.7)', // Light lavender
    'rgba(127, 127, 255, 0.7)', // Cool blue
  ];

  const ctx = document.getElementById('employeePieChart').getContext('2d');

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        label: 'Quotation Count',
        data: quotationCounts,
        backgroundColor: gradientColors,
        hoverBackgroundColor: gradientColors.map(color => color.replace('0.7', '1')),
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 15
      }]
    },
    options: {
      responsive: true,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: '#333',
            font: {
              family: "'Inter', sans-serif",
              size: 14,
              weight: 'bold'
            },
            boxWidth: 20,  // Adjusts the size of the color box next to the label
          },
          // This is to make the labels appear in a row
          display: true,
          align: 'start',
          reverse: false,
          fullWidth: true
        },
        tooltip: {
          enabled: false,
          external: function(context) {
            let tooltipEl = document.getElementById('chartjs-tooltip');
            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chartjs-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
              tooltipEl.style.borderRadius = '8px';
              tooltipEl.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
              tooltipEl.style.padding = '12px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'opacity 0.2s ease';
              tooltipEl.style.opacity = 0;
              tooltipEl.style.transform = 'translate(-50%, -120%)';
              document.body.appendChild(tooltipEl);
            }

            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = labels[index];
            const totalMargin = totalMargins[index];
            const quotationCount = quotationCounts[index];
            const image = images[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${image}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 10px;">
            <div style="font-weight: bold; color: #333; margin-bottom: 5px;">${employeeName}</div>
            <div style="color: #666;">Quotation Count: <strong>${quotationCount}</strong></div>
            <div style="color: #666;">Total Margin: <strong>${totalMargin.toFixed(2)} AED</strong></div>
            </div>
            `;

            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
        }
      },
      layout: {
        padding: {
          top: 20,
          bottom: 20
        }
      }
    }
  });

});



document.addEventListener('DOMContentLoaded', function () {
  const employeeOrderStatus = @json($employeeOrderStatus);
  const employeeNames = employeeOrderStatus.map(stat => stat.user_name);
  const totalValues = employeeOrderStatus.map(stat => stat.total_amount);
  const imageUrls = employeeOrderStatus.map(stat => stat.employee_image_url);

  const ctx = document.getElementById('orderPieChart').getContext('2d');

  const fixedColors = [
    'rgba(255, 159, 64, 0.7)',  // Light Orange
    'rgba(75, 192, 192, 0.7)',  // Light Teal
    'rgba(153, 102, 255, 0.7)', // Light Purple
    'rgba(54, 162, 235, 0.7)',  // Light Blue
    'rgba(255, 99, 132, 0.7)',  // Light Red
    'rgba(255, 206, 86, 0.7)',  // Light Yellow
    'rgba(255, 182, 193, 0.7)', // Light Pink
    'rgba(0, 204, 255, 0.7)',   // Light Sky Blue
    'rgba(255, 99, 71, 0.7)',   // Light Tomato
    'rgba(144, 238, 144, 0.7)', // Light Green
  ];

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: employeeNames,
      datasets: [
        {
          label: 'Order Margin',
          data: totalValues,
          backgroundColor: fixedColors,
          hoverBackgroundColor: fixedColors.map(color => color.replace('0.7', '1')),
          borderWidth: 2,
          borderColor: '#fff',
        },
      ],
    },
    options: {
      responsive: true,
      cutout: '65%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#4A4A4A',
            font: {
              family: "'Inter', sans-serif",
              size: 14,
            },
          },
        },
        tooltip: {
          enabled: false, // Disable default tooltip
          external: function (context) {
            let tooltipEl = document.getElementById('chart-tooltip');
            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chart-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
              tooltipEl.style.borderRadius = '8px';
              tooltipEl.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
              tooltipEl.style.padding = '12px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'opacity 0.2s ease';
              tooltipEl.style.opacity = 0;
              tooltipEl.style.transform = 'translate(-50%, -120%)';
              document.body.appendChild(tooltipEl);
            }

            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            // Get the hovered section index
            const dataPoint = tooltipModel.dataPoints[0];
            if (!dataPoint) return;

            const index = dataPoint.dataIndex;
            const employeeName = employeeNames[index];
            const totalValue = totalValues[index];
            const imageUrl = imageUrls[index];
            const percentage = ((totalValue / totalValues.reduce((a, b) => a + b, 0)) * 100).toFixed(2);

            tooltipEl.innerHTML = `
            <div style="text-align: center; padding: 8px; font-size: 12px;">
            <img src="${window.location.origin}/storage/${imageUrl}" alt="${employeeName}" style="width: 40px; height: 40px; border-radius: 50%; margin-bottom: 6px;">
            <div style="font-weight: bold; color: #333; margin-bottom: 4px; font-size: 14px;">${employeeName}</div>
            <div style="color: #666; font-size: 12px;">Margin: <strong>${totalValue} AED</strong></div>
            </div>
            `;

            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          },
        },
      },
      layout: {
        padding: {
          top: 10,
          bottom: 10,
        },
      },
      animation: {
        animateScale: true,
        animateRotate: true,
        easing: 'easeInOutQuart',
      },
      hover: {
        mode: 'nearest',
        animationDuration: 500,
      },
    },
  });
});



document.addEventListener('DOMContentLoaded', function () {
  const employeeQuotationStatus = @json($employeeYearlyQuotationStatus);
  const labels = employeeQuotationStatus.map(stat => stat.employee_name);
  const quotationCounts = employeeQuotationStatus.map(stat => stat.quotation_count);
  const totalMargins = employeeQuotationStatus.map(stat => stat.total_margin);
  const images = employeeQuotationStatus.map(stat => stat.employee_image);

  // Gradient Colors
  const ctx = document.getElementById('employeeYearlyPieChart').getContext('2d');
  const gradientColors = quotationCounts.map((_, index) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, `rgba(${index * 50}, 99, 132, 1)`);
    gradient.addColorStop(1, `rgba(${index * 25}, 162, 235, 1)`);
    return gradient;
  });

  new Chart(ctx, {
    type: 'doughnut', // Changed to doughnut for a modern look
    data: {
      labels: labels,
      datasets: [{
        label: 'Quotation Count',
        data: quotationCounts,
        backgroundColor: gradientColors,
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 10, // Add hover effect
      }]
    },
    options: {
      responsive: true,
      cutout: '60%', // Adjust the doughnut hole size
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: '#333',
            font: {
              family: "'Inter', sans-serif",
              size: 14
            },
            padding: 20
          }
        },
        tooltip: {
          enabled: false,
          external: function (context) {
            let tooltipEl = document.getElementById('chartjs-tooltip');
            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chartjs-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(0, 0, 0, 0.75)';
              tooltipEl.style.color = '#fff';
              tooltipEl.style.borderRadius = '8px';
              tooltipEl.style.padding = '12px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.2s ease';
              tooltipEl.style.fontFamily = "'Inter', sans-serif";
              tooltipEl.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.25)';
              document.body.appendChild(tooltipEl);
            }

            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = labels[index];
            const totalMargin = totalMargins[index];
            const quotationCount = quotationCounts[index];
            const image = images[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${image}" alt="${employeeName}"
            style="width: 60px; height: 60px; border-radius: 50%; margin-bottom: 10px; border: 2px solid #fff;">
            <div style="font-size: 14px; font-weight: bold;">${employeeName}</div>
            <div style="font-size: 12px;">Quotation Count: <strong>${quotationCount}</strong></div>
            <div style="font-size: 12px;">Total Margin: <strong>${totalMargin.toFixed(2)} AED</strong></div>
            </div>
            `;

            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
        }
      },
      hover: {
        onHover: function (e, activeElements) {
          if (activeElements.length) {
            e.native.target.style.cursor = 'pointer';
          } else {
            e.native.target.style.cursor = 'default';
          }
        }
      }
    }
  });
});


document.addEventListener('DOMContentLoaded', function () {
  const employeeOrderStatus = @json($employeeYearlyOrderStatus);
  const employeeNames = employeeOrderStatus.map(stat => stat.user_name);
  const totalValues = employeeOrderStatus.map(stat => stat.total_amount);
  const imageUrls = employeeOrderStatus.map(stat => stat.employee_image_url);

  const ctx = document.getElementById('orderYearlyPieChart').getContext('2d');

  const chart = new Chart(ctx, {
    type: 'doughnut', // Change to 'doughnut' for a center hole
    data: {
      labels: employeeNames,
      datasets: [{
        label: 'Order Margin',
        data: totalValues,
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(231, 76, 60, 0.7)'
        ],
        borderWidth: 2,
        borderColor: 'rgba(255, 255, 255, 1)',
        imageUrls: imageUrls
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,  // Allow resizing based on the container size
      aspectRatio: 1,  // Adjust aspect ratio to make it square (can change as needed)
      cutoutPercentage: 70, // Creates the center hole effect (70% hole)
      animation: {
        animateRotate: true,
        animateScale: true
      },
      plugins: {
        tooltip: {
          enabled: false,
          external: function(context) {
            let tooltipEl = document.getElementById('chart-tooltip');

            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chart-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
              tooltipEl.style.borderRadius = '8px';
              tooltipEl.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.3)';
              tooltipEl.style.padding = '12px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.15s ease-in-out';
              tooltipEl.style.color = '#fff';
              tooltipEl.style.fontFamily = 'Arial, sans-serif';
              tooltipEl.style.fontSize = '14px';
              document.body.appendChild(tooltipEl);
            }

            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = employeeNames[index];
            const totalValue = totalValues[index];
            const imageUrl = imageUrls[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center; padding: 4px; font-size: 12px; line-height: 1.2;">
            <img src="${window.location.origin}/storage/${imageUrl}"
            alt="${employeeName}"
            style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 4px;">
            <div style="font-weight: bold;">${employeeName}</div>
            <div>Total Margin: ${totalValue}</div>
            </div>
            `;

            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
        }
      },
      onClick: function(e, elements) {
        if (elements.length > 0) {
          const elementIndex = elements[0]._index;
          const datasetIndex = elements[0]._datasetIndex;

          // Get the segment that was clicked
          const clickedSegment = chart.getDatasetMeta(datasetIndex).data[elementIndex];

          // Animate the clicked segment to scale up
          chart.update({
            duration: 400,
            easing: 'easeOutBounce',
            onComplete: function() {
              clickedSegment.custom = {
                borderWidth: 6,
                backgroundColor: 'rgba(255, 99, 132, 0.9)'  // Change color on click (example)
              };
              chart.update();
            }
          });
        }
      },
      legend: {
        position: 'top',
        labels: {
          fontSize: 14,
          fontStyle: 'bold',
          fontColor: '#333'
        }
      }
    }
  });
});


document.addEventListener('DOMContentLoaded', function () {
  const quotationStats = @json($quotationStatus);
  console.log(quotationStats);

  if (!quotationStats) return;

  const statuses = Object.keys(quotationStats);
  const employees = new Set();
  const datasets = [];

  statuses.forEach(status => {
    quotationStats[status].forEach(entry => {
      employees.add(entry.employee_name);
    });
  });

  const employeeList = Array.from(employees);


  const createGradient = (ctx, index, totalEmployees) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    const hue = (index * 360) / totalEmployees;
    const startColor = `hsl(${hue}, 40%, 85%)`;
    const endColor = `hsl(${hue}, 45%, 75%)`;

    gradient.addColorStop(0, startColor);
    gradient.addColorStop(1, endColor);
    return gradient;
  };


  statuses.forEach((status, statusIndex) => {
    const data = employeeList.map(employeeName => {
      const entry = quotationStats[status].find(e => e.employee_name === employeeName);
      return entry ? entry.quotation_count : 0;
    });


    const marginData = employeeList.map(employeeName => {
      const entry = quotationStats[status].find(e => e.employee_name === employeeName);
      return entry ? entry.total_margin : 0;
    });

    datasets.push({
      label: status,
      data: data,
      backgroundColor: createGradient(document.getElementById('quotationStatusChart').getContext('2d'), statusIndex, statuses.length),
      borderColor: `hsl(${(statusIndex * 360) / statuses.length}, 40%, 60%)`,
      borderWidth: 1,
      stack: 'stack1',
      barThickness: 44,
      marginData: marginData,
    });
  });

  // Create the stacked bar chart
  const ctx = document.getElementById('quotationStatusChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: employeeList,
      datasets: datasets
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              const count = context.raw;
              const status = context.dataset.label;
              const employeeName = context.label;
              const margin = context.dataset.marginData[context.dataIndex];

              return [
                `${employeeName}`,
                `${status}: ${count}`,
                `Margin: $${margin}`,
              ];
            }
          }
        },
        legend: {
          position: 'top',
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Employee'
          },
          stacked: true,
        },
        y: {
          title: {
            display: true,
            text: 'Quotation Count'
          },
          stacked: true,
          beginAtZero: true,
        }
      }
    }
  });
});

</script>

@endsection
