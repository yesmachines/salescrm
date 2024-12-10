@extends('layouts.default')

@section('content')
<div class="container-xxl">

  <div class="hk-pg-header pg-header-wth-tab pt-7">
    <div class="d-flex">
      <div class="d-flex flex-wrap justify-content-between flex-1">
        <div class="mb-lg-0 mb-2 me-8">

        </div>
      </div>
    </div>
  </div>

  <!-- Pie Chart Section -->
  <div class="card">
    <div class="card-header">
      <h5>Employee Quotation Stats and Order Stats by Employee</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Employee Pie Chart Section -->
        <div class="col-md-6">
          <h5>Employee Quotation Stats</h5>
          <div class="chart-container" style="position: relative; height:65vh; width:100%">
            <canvas id="employeePieChart"></canvas>
          </div>
        </div>

        <!-- Order Pie Chart Section -->
        <div class="col-md-6">
          <h5>Order Stats by Employee</h5>
          <div class="chart-container" style="position: relative; height:65vh; width:100%">
            <canvas id="orderPieChart"></canvas>
          </div>
        </div>
      </div>

    <!-- enquiry bar Chart Section -->
      <div class="row" style="padding-top:25px;">
        <div class="col-md-10">
          <div >
            <h3>Enquiry Status</h3>
            <canvas id="leadStatusChart"></canvas>
          </div>
        </div>
      </div>
        <!-- quotation bar Chart Section -->
      <div class="row" style="padding-top:25px;">
        <div class="col-md-10">
          <div >
            <h3>Quotation Status</h3>
            <canvas id="quotationStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Prepare data for Employee Quotation Stats
  const employeeQuotationStatus = @json($employeeQuotationStatus);

  // Employee Chart Data
  const labels = employeeQuotationStatus.map(stat => stat.employee_name);
  const totalMargins = employeeQuotationStatus.map(stat => stat.total_margin);
  const images = employeeQuotationStatus.map(stat => stat.employee_image);
  const quotationCounts = employeeQuotationStatus.map(stat => stat.quotation_count);

  const ctx = document.getElementById('employeePieChart').getContext('2d');


  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        label: 'Total Margin',
        data: totalMargins,
        backgroundColor: [
          'rgba(255, 99, 132, 1)',   // Fully opaque
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
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
          enabled: false, // Disable default tooltip
          external: function(context) {
            // Tooltip Element
            let tooltipEl = document.getElementById('chartjs-tooltip');

            // Create the tooltip element if it doesn't exist
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

            // Hide tooltip if not visible
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
            <div>Total Margin: ${totalMargin.toFixed(2)} AED</div>
            <div>Quotation Count: ${quotationCount}</div>
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
  // Prepare data for Employee Order Stats
  const employeeOrderStatus = @json($employeeOrderStatus);

  // Employee Order Stats Chart Data
  const employeeNames = employeeOrderStatus.map(stat => stat.user_name);
  const orderCounts = employeeOrderStatus.map(stat => stat.order_count);
  const totalValues = employeeOrderStatus.map(stat => stat.total_amount);
  const imageUrls = employeeOrderStatus.map(stat => stat.employee_image_url);

  // Get the canvas context for the Order Stats Pie Chart
  const ctx = document.getElementById('orderPieChart').getContext('2d');

  // Create the Pie Chart for Order Stats by Employee
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: employeeNames,
      datasets: [{
        label: 'Order Count',
        data: orderCounts,
        backgroundColor: [
          'rgba(255, 99, 132, 1)',   // Fully opaque
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        borderColor: 'rgba(255, 255, 255, 1)',
        imageUrls: imageUrls // Pass image URLs here
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          enabled: false, // Disable default tooltip
          external: function(context) {
            // Tooltip Element
            let tooltipEl = document.getElementById('chart-tooltip');

            // Create the tooltip element if it doesn't exist
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
            const orderCount = orderCounts[index];
            const imageUrl = imageUrls[index];
            const totalValue = totalValues[index];


            tooltipEl.innerHTML = `
            <div style="text-align: center;">
            <img src="${window.location.origin}/storage/${imageUrl}" alt="${employeeName}" style="width: 50px; height: 50px; border-radius: 50%; margin-bottom: 5px;">
            <div><strong>${employeeName}</strong></div>
            <div>Order Count: ${orderCount}</div>
            <div>Order Amount: ${totalValue}</div>
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
var ctx = document.getElementById('leadStatusChart').getContext('2d');
var leadStatusChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: @json($leadStatus->pluck('status_name')),  // X-axis labels (status names)
    datasets: [{
      label: 'Number of Equiries',
      data: @json($leadStatus->pluck('lead_count')),  // Y-axis data (lead counts)
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
var ctx = document.getElementById('quotationStatusChart').getContext('2d');
var quotationStatusChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: @json($quotationStatus->pluck('status_name')),
    datasets: [{
      label: 'Number of Quotations',
      data: @json($quotationStatus->pluck('quotation_count')),
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>

@endsection
