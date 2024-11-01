/*Contact Init*/
"use strict";
$(function () {
    // Create chart instance
    var chart = am4core.create("leadchartdiv", am4charts.PieChart);

    // Add data
    chart.data = $('#leads_cnt').html();

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "count";
    pieSeries.dataFields.category = "leads";

});