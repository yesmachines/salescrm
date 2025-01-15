@extends('layouts.default')

@section('content')
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
              <div class="chart-container" style="height: 500px;">
                <canvas id="quotationStatusChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const employeeQuotationStatus = @json($employeeQuotationStatus);
  const labels = employeeQuotationStatus.map(stat => stat.employee_name);
  const quotationCounts = employeeQuotationStatus.map(stat => stat.quotation_count);
  const totalMargins = employeeQuotationStatus.map(stat => stat.total_margin);
  const images = employeeQuotationStatus.map(stat => stat.employee_image);
  const customColors = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)',
    'rgba(255, 182, 193, 1)' ,
    'rgba(255, 206, 86, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(255, 255, 0, 1)',
    'rgba(138, 43, 226, 1)'
  ];

  const colors = customColors.slice(0, employeeQuotationStatus.length);

  const ctx = document.getElementById('employeePieChart').getContext('2d');

  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        label: 'Quotation Count',
        data: quotationCounts,
        backgroundColor: colors,
        borderColor: '#fff',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: '#333',
            font: {
              family: "'Inter', sans-serif"
            }
          }
        },
        tooltip: {
          enabled: false,
          external: function(context) {

            let tooltipEl = document.getElementById('chartjs-tooltip');
            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chartjs-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
              tooltipEl.style.borderRadius = '5px';
              tooltipEl.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
              tooltipEl.style.padding = '10px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.1s ease';
              document.body.appendChild(tooltipEl);
            }


            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            // Set tooltip content
            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = labels[index];
            const totalMargin = totalMargins[index];
            const quotationCount = quotationCounts[index];
            const image = images[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${image}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 5px;">
            <div><strong>${employeeName}</strong></div>
            <div>Quotation Count: ${quotationCount}</div>
            <div>Total Margin: ${totalMargin.toFixed(2)} AED</div>
            </div>
            `;

            // Position the tooltip
            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
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


  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: employeeNames,
      datasets: [{
        label: 'Order Margin',
        data: totalValues,
        backgroundColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        borderColor: 'rgba(255, 255, 255, 1)',
        imageUrls: imageUrls
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          enabled: false,
          external: function(context) {

            let tooltipEl = document.getElementById('chart-tooltip');

            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chart-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
              tooltipEl.style.borderRadius = '5px';
              tooltipEl.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
              tooltipEl.style.padding = '10px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.1s ease';
              document.body.appendChild(tooltipEl);
            }

            // Hide tooltip if not visible
            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            // Set tooltip content
            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = employeeNames[index];
            const totalValue = totalValues[index]; // Get the total margin
            const imageUrl = imageUrls[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${imageUrl}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 5px;">
            <div><strong>${employeeName}</strong></div>
            <div>Total Margin: ${totalValue}</div> <!-- Show total margin -->
            </div>
            `;

            // Position the tooltip
            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
        }
      }
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {

  const employeeQuotationStatus = @json($employeeYearlyQuotationStatus);
  const labels = employeeQuotationStatus.map(stat => stat.employee_name);
  const quotationCounts = employeeQuotationStatus.map(stat => stat.quotation_count);
  const totalMargins = employeeQuotationStatus.map(stat => stat.total_margin);
  const images = employeeQuotationStatus.map(stat => stat.employee_image);
  const customColors = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)',
    'rgba(255, 182, 193, 1)' ,
    'rgba(255, 206, 86, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(255, 255, 0, 1)',
    'rgba(138, 43, 226, 1)'
  ];

  const colors = customColors.slice(0, employeeQuotationStatus.length);

  const ctx = document.getElementById('employeeYearlyPieChart').getContext('2d');

  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        label: 'Quotation Count',
        data: quotationCounts,
        backgroundColor: colors,
        borderColor: '#fff',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: '#333',
            font: {
              family: "'Inter', sans-serif"
            }
          }
        },
        tooltip: {
          enabled: false,
          external: function(context) {

            let tooltipEl = document.getElementById('chartjs-tooltip');
            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chartjs-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
              tooltipEl.style.borderRadius = '5px';
              tooltipEl.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
              tooltipEl.style.padding = '10px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.1s ease';
              document.body.appendChild(tooltipEl);
            }


            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            // Set tooltip content
            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = labels[index];
            const totalMargin = totalMargins[index];
            const quotationCount = quotationCounts[index];
            const image = images[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${image}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 5px;">
            <div><strong>${employeeName}</strong></div>
            <div>Quotation Count: ${quotationCount}</div>
            <div>Total Margin: ${totalMargin.toFixed(2)} AED</div>
            </div>
            `;

            // Position the tooltip
            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
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


  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: employeeNames,
      datasets: [{
        label: 'Order Margin',
        data: totalValues,
        backgroundColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        borderColor: 'rgba(255, 255, 255, 1)',
        imageUrls: imageUrls
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          enabled: false,
          external: function(context) {

            let tooltipEl = document.getElementById('chart-tooltip');

            if (!tooltipEl) {
              tooltipEl = document.createElement('div');
              tooltipEl.id = 'chart-tooltip';
              tooltipEl.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
              tooltipEl.style.borderRadius = '5px';
              tooltipEl.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
              tooltipEl.style.padding = '10px';
              tooltipEl.style.pointerEvents = 'none';
              tooltipEl.style.position = 'absolute';
              tooltipEl.style.transition = 'all 0.1s ease';
              document.body.appendChild(tooltipEl);
            }

            // Hide tooltip if not visible
            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
              tooltipEl.style.opacity = 0;
              return;
            }

            // Set tooltip content
            const index = tooltipModel.dataPoints[0].dataIndex;
            const employeeName = employeeNames[index];
            const totalValue = totalValues[index]; // Get the total margin
            const imageUrl = imageUrls[index];

            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${imageUrl}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 5px;">
            <div><strong>${employeeName}</strong></div>
            <div>Total Margin: ${totalValue}</div> <!-- Show total margin -->
            </div>
            `;

            // Position the tooltip
            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
            tooltipEl.style.opacity = 1;
          }
        }
      }
    }
  });
});


// Fetch data from Laravel
const quotationStats = @json($quotationStatus);

// Prepare data for Chart.js
const statuses = Object.keys(quotationStats); // X-axis labels: Quotation statuses
const employees = new Set(); // Collect all employees
const datasets = [];

// Collect all unique employees across statuses
statuses.forEach(status => {
  quotationStats[status].forEach(entry => {
    employees.add(entry.employee_name);
  });
});

const employeeList = Array.from(employees); // Convert to an array

// Build datasets for each employee
employeeList.forEach((employeeName, index) => {
  const data = statuses.map(status => {
    const entry = quotationStats[status].find(e => e.employee_name === employeeName);
    return entry
    ? {
      total_margin: entry.total_margin, // Total margin value
      count: entry.quotation_count // Quotation count
    }
    : { total_margin: 0, count: 0 }; // Default if no data
  });

  datasets.push({
    label: employeeName, // Employee name as dataset label
    data: data.map(d => d.total_margin), // Total margin for Chart.js
    backgroundColor: `hsl(${(index * 360) / employeeList.length}, 70%, 50%)`, // Unique color
    borderColor: `hsl(${(index * 360) / employeeList.length}, 70%, 40%)`,
    borderWidth: 1,
    countData: data.map(d => d.count) // Keep track of counts for tooltips
  });
});

// Create the stacked bar chart
const ctx = document.getElementById('quotationStatusChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: statuses, // X-axis: Quotation statuses
    datasets: datasets // Employee-based datasets
  },
  options: {
    responsive: true,
    plugins: {
      tooltip: {
        callbacks: {
          label: function (context) {
            const value = context.raw;
            const employeeName = context.dataset.label;
            const count = context.dataset.countData[context.dataIndex];
            return [
              `${employeeName}`, // Employee name
              `Quote Count: ${count}`, // Quotation count
              `Margin: $${value}`, // Total margin
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
        stacked: true, // Enable stacking on the X-axis
        title: {
          display: true,
          text: 'Quotation Status'
        }
      },
      y: {
        stacked: true, // Enable stacking on the Y-axis
        title: {
          display: true,
          text: 'Total Margin'
        },
        beginAtZero: true,
      }
    }
  }
});
</script>

@endsection
