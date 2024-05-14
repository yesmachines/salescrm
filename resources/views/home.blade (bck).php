@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pg-header-wth-tab pt-7">
        <div class="d-flex">
            <div class="d-flex flex-wrap justify-content-between flex-1">
                <div class="mb-lg-0 mb-2 me-8">
                    <h1 class="pg-title">Welcome back</h1>
                    <p>Create pages using a variety of features that leverage jampack components</p>
                </div>

            </div>
        </div>

    </div>
    <!-- /Page Header -->
    <!-- Page Body -->
    <div class="hk-pg-body">
        <div class="row">
            <div class="col">
                <div class="card card-border contact-card">
                    <div class="card-header">
                        <h6 class="p-2 text-uppercase"> <span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Leads Overview</h6>
                    </div>
                    <div class="card-body text-center">
                        <div id="leadchartdiv"></div>
                    </div>
                    <div class="card-footer text-muted position-relative">
                        Current Month :&nbsp;<a href="{{route('leads.converted')}}"> {{$currentMonthLeads}} </a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-border contact-card">
                    <div class="card-header">
                        <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Quotations Overview</h6>
                    </div>
                    <div class="card-body text-center">
                        <!-- <div class="row">
                            <div class="col">
                                
                            </div>  <div class="col">
                                <h6 class="text-muted">Expected Closing Quote</h6>
                                <div id="quotechartdivclosing"></div>
                            </div>
                        </div> -->
                        <h6 class="text-muted">Pending Quotes</h6>
                        <div id="quotechartdivsubmitted"></div>
                    </div>
                    <div class="card-footer text-muted position-relative">
                        Pending quotes:&nbsp;<a href="{{route('quotation.listall')}}"> {{$currentMonthQuotes}} </a>&nbsp; | &nbsp;
                        Won:&nbsp;<a href="{{route('quotation.win')}}"> {{$currentMonthWonQuote}} </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <div class="dropdown-divider mt-4 mb-4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Leads Summary</h6>
                <div id="empleadchartdiv"></div>
                <div class="mt-4"><a href="{{route('reports.leads')}}">View All </a></div>

                <!-- <div class="card card-border contact-card">
                    <div class="card-header">
                      
                    </div>
                    <div class="card-body text-center">
                       
                    </div>
                    <div class="card-footer text-muted position-relative">
                      
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="dropdown-divider mt-4 mb-4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Quotation Summary</h6>
                <div id="employeechartdiv"></div>
                <div class="mt-4"><a href="{{route('reports.quotations')}}">View All </a></div>
                <!-- <div class="card card-border contact-card">
                    <div class="card-header">
                        
                    </div>
                    <div class="card-body text-center">
                       
                    </div>
                    <div class="card-footer text-muted position-relative">
                        
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="dropdown-divider mt-4 mb-4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Win Standing</h6>
                <div id="winchartdiv"></div>
                <div class="mt-4"><a href="{{route('reports.winners')}}">View All </a></div>
                <!-- <div class="card card-border contact-card">
                    <div class="card-header">
                       
                    </div>
                    <div class="card-body text-center">
                        
                    </div>
                    <div class="card-footer text-muted position-relative">
                        
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="dropdown-divider mt-4 mb-4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Supplier/Brand Summary</h6>
                <div id="brandchartdiv" style="width: 100%;"></div>
                <div class="mt-4"> <a href="{{route('reports.suppliers')}}">View All </a></div>
                <!-- <div class="card card-border contact-card">
                    <div class="card-header">
                       
                    </div>
                    <div class="card-body text-center">
                       
                    </div>
                    <div class="card-footer text-muted position-relative">
                        
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="dropdown-divider mt-4 mb-4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h6 class="p-2 text-uppercase"><span class="text-danger">{{ \Carbon\Carbon::now()->format('F')  }}</span> - Probability Summary</h6>
                <div id="probabilitydiv" style="width: 100%;"></div>
                <div class="mt-4"> <a href="{{route('reports.probability')}}">View All </a></div>
                <!-- <div class="card card-border contact-card">
                    <div class="card-header">
                       
                    </div>
                    <div class="card-body text-center">
                       
                    </div>
                    <div class="card-footer text-muted position-relative">
                        
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>

<!-- Amcharts Maps JS -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="{{asset('vendors/@amcharts/amcharts4/themes/animated.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/kelly.js"></script>
<script>
    /*Contact Init*/
    $(function() {
        const date = new Date();
        const currentmonth = date.toLocaleString('default', {
            month: 'short'
        });
        /**************** LEADS  *********************/
        var leads = JSON.parse('{!! $leads !!}');
        // Create chart instance
        var lchart = am4core.create("leadchartdiv", am4charts.PieChart);
        // Add data

        lchart.data = leads;

        // Add and configure Series
        var lpieSeries = lchart.series.push(new am4charts.PieSeries());
        lpieSeries.dataFields.value = "count";
        lpieSeries.dataFields.category = "status";

        /**************** QUOTATION - PART 1 *********************/
        var quotations = JSON.parse('{!! $quotations !!}');
        // Set theme
        am4core.useTheme(am4themes_animated);

        // Create chart instance
        var qschart = am4core.create("quotechartdivsubmitted", am4charts.PieChart);
        // Add data

        qschart.data = quotations;

        // Add and configure Series
        var qspieSeries = qschart.series.push(new am4charts.PieSeries());
        qspieSeries.dataFields.value = "submitted";
        qspieSeries.dataFields.category = "status";

        // Create chart instance
        // var chart = am4core.create("quotechartdivsubmitted", am4charts.PieChart);

        // // Let's cut a hole in our Pie chart the size of 40% the radius
        // chart.innerRadius = am4core.percent(40);

        // // Add data
        // chart.data = quotations;

        // // Add and configure Series
        // var pieSeries = chart.series.push(new am4charts.PieSeries());
        // pieSeries.dataFields.value = "submitted";
        // pieSeries.dataFields.category = "status";
        // pieSeries.slices.template.stroke = am4core.color("#fff");
        // pieSeries.slices.template.strokeWidth = 2;
        // pieSeries.slices.template.strokeOpacity = 1;

        // // Disabling labels and ticks on inner circle
        // pieSeries.labels.template.disabled = true;
        // pieSeries.ticks.template.disabled = true;

        // // Disable sliding out of slices
        // pieSeries.slices.template.states.getKey("hover").properties.shiftRadius = 0;
        // pieSeries.slices.template.states.getKey("hover").properties.scale = 0.9;

        // Add second series
        // var pieSeries2 = chart.series.push(new am4charts.PieSeries());
        // pieSeries2.dataFields.value = "closing";
        // pieSeries2.dataFields.category = "status";
        // pieSeries2.slices.template.stroke = am4core.color("#fff");
        // pieSeries2.slices.template.strokeWidth = 2;
        // pieSeries2.slices.template.strokeOpacity = 1;
        // pieSeries2.slices.template.states.getKey("hover").properties.shiftRadius = 0;
        // pieSeries2.slices.template.states.getKey("hover").properties.scale = 1.1;

        /**************** QUOTATION - PART 2 *********************/
        // Create chart instance
        var qcchart = am4core.create("quotechartdivclosing", am4charts.PieChart);
        // Add data

        qcchart.data = quotations;

        // Add and configure Series
        var qcpieSeries = qcchart.series.push(new am4charts.PieSeries());
        qcpieSeries.dataFields.value = "closing";
        qcpieSeries.dataFields.category = "status";
        /*******************************
         * Employee SALES Summary
         * **********************************************/

        var employeeSummary = JSON.parse('{!! $employeeSummary !!}');

        // Apply chart themes
        am4core.useTheme(am4themes_animated);
        am4core.useTheme(am4themes_kelly);

        // Create chart instance
        var chart = am4core.create("employeechartdiv", am4charts.XYChart);

        chart.marginRight = 400;

        // Add data
        chart.data = employeeSummary;

        //console.log('chart', employeeSummary);

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "employee";
        categoryAxis.title.text = "Current Month (" + currentmonth + ") - Submission of Quotations";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 20;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Total Amount";

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "salesvalue";
        series.dataFields.categoryX = "employee";
        series.name = "Sales Value";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.stacked = true;

        var series2 = chart.series.push(new am4charts.ColumnSeries());
        series2.dataFields.valueY = "margin";
        series2.dataFields.categoryX = "employee";
        series2.name = "Gross Margin";
        series2.tooltipText = "{name}: [bold]{valueY}[/]";
        series2.stacked = true;

        var series3 = chart.series.push(new am4charts.ColumnSeries());
        series3.dataFields.valueY = "quotecount";
        series3.dataFields.categoryX = "employee";
        series3.name = "No of Quotations";
        series3.tooltipText = "{name}: [bold]{valueY}[/]";
        series3.stacked = true;


        // Add cursor
        chart.cursor = new am4charts.XYCursor();

        /*********************************************
         * Employee LEADS Summary
         */
        var employeeLeadSummary = JSON.parse('{!! $employeeLeadSummary !!}');

        // Apply chart themes
        am4core.useTheme(am4themes_animated);
        am4core.useTheme(am4themes_kelly);

        // Create chart instance
        var chart = am4core.create("empleadchartdiv", am4charts.XYChart);

        // Add data
        chart.data = employeeLeadSummary;

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "employee";
        categoryAxis.title.text = "Current Month (" + currentmonth + ") - Employee Leads";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 20;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Total Count";

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "leadcount";
        series.dataFields.categoryX = "employee";
        series.name = "Total Leads";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        // This has no effect
        // series.stacked = true;

        var series2 = chart.series.push(new am4charts.ColumnSeries());
        series2.dataFields.valueY = "proceed";
        series2.dataFields.categoryX = "employee";
        series2.name = "Proceed";
        series2.tooltipText = "{name}: [bold]{valueY}[/]";
        // Do not try to stack on top of previous series
        // series2.stacked = true;

        var series3 = chart.series.push(new am4charts.ColumnSeries());
        series3.dataFields.valueY = "bullshit";
        series3.dataFields.categoryX = "employee";
        series3.name = "Bullshit";
        series3.tooltipText = "{name}: [bold]{valueY}[/]";
        series3.stacked = true;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();

        // Add legend
        chart.legend = new am4charts.Legend();

        /*******************************
         *  Win Standing 
         * *************************************** */

        var winStanding = JSON.parse('{!! $winStanding !!}');
        am4core.useTheme(am4themes_animated);
        // Create chart instance
        var chart = am4core.create("winchartdiv", am4charts.XYChart);
        // Add data
        chart.data = winStanding;

        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "employee";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.labels.template.disabled = true;

        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;

        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueX = "value";
        series.dataFields.categoryY = "employee";

        var valueLabel = series.bullets.push(new am4charts.LabelBullet());
        valueLabel.label.text = "{value} ({quotecount})";
        valueLabel.label.fontSize = 12;
        valueLabel.label.horizontalCenter = "left";
        valueLabel.label.dx = 10;
        valueLabel.label.truncate = false;
        valueLabel.label.hideOversized = false;

        var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
        categoryLabel.label.text = "{employee}";
        categoryLabel.label.fontSize = 15;
        categoryLabel.label.horizontalCenter = "right";
        categoryLabel.label.dx = -10;
        categoryLabel.label.fill = am4core.color("#fff");

        chart.maskBullets = false;
        chart.paddingRight = 30;
    });
</script>

<script>
    /*******************************
     * Brand SALES Summary
     * **********************************************/
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);

        if (chart) {
            chart.dispose();
        }
        // Themes end
        var brandSummary = JSON.parse('{!! $brandSummary !!}');
        // Create chart instance
        var chart = am4core.create("brandchartdiv", am4charts.XYChart);

        // Add data
        // chart.data = [{
        //     "year": 2005,
        //     "income": 23.5,
        //     "expenses": 18.1
        // }, {
        //     "year": 2006,
        //     "income": 26.2,
        //     "expenses": 22.8
        // }, {
        //     "year": 2007,
        //     "income": 30.1,
        //     "expenses": 23.9
        // }, {
        //     "year": 2008,
        //     "income": 29.5,
        //     "expenses": 25.1
        // }, {
        //     "year": 2009,
        //     "income": 24.6,
        //     "expenses": 25
        // }];
        chart.data = brandSummary;

        // Create axes
        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "brand";
        categoryAxis.numberFormatter.numberFormat = "#";
        categoryAxis.renderer.inversed = true;
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.cellStartLocation = 0.1;
        categoryAxis.renderer.cellEndLocation = 0.9;
        categoryAxis.renderer.minGridDistance = 25;

        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.opposite = true;

        // Create series
        function createSeries(field, name) {
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueX = field;
            series.dataFields.categoryY = "brand";
            series.name = name;
            series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
            series.columns.template.height = am4core.percent(100);
            series.sequencedInterpolation = true;

            var valueLabel = series.bullets.push(new am4charts.LabelBullet());
            valueLabel.label.text = "{valueX}";
            valueLabel.label.horizontalCenter = "left";
            valueLabel.label.dx = 10;
            valueLabel.label.hideOversized = false;
            valueLabel.label.truncate = false;

            var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
            categoryLabel.label.text = "{name}";
            categoryLabel.label.horizontalCenter = "right";
            categoryLabel.label.dx = -10;
            categoryLabel.label.fill = am4core.color("#fff");
            categoryLabel.label.hideOversized = false;
            categoryLabel.label.truncate = false;

        }
        createSeries("salesvalue", "Sales");
        // createSeries("quotecount", "Quotes");

        /**
         * ========================================================
         * Enabling responsive features
         * ========================================================
         */

        chart.responsive.useDefault = false;
        chart.responsive.enabled = true;

        // Set cell size in pixels
        var cellSize = 30;
        chart.events.on("datavalidated", function(ev) {

            // Get objects of interest
            var chart = ev.target;
            var categoryAxis = chart.yAxes.getIndex(0);

            // Calculate how we need to adjust chart height
            var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

            // get current chart height
            var targetHeight = chart.pixelHeight + adjustHeight;

            // Set it on chart's container
            chart.svgContainer.htmlElement.style.height = targetHeight + "px";
        });


    }); // end am4core.ready()


    /************************************************
     * Winning Probability
     *************************************************/


    var probabilitySummary = JSON.parse('{!! $probabilitySummary !!}');

    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("probabilitydiv", am4charts.XYChart);

        // Add data
        chart.data = probabilitySummary;

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "winning_probability";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;

        categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
            if (target.dataItem && target.dataItem.index & 2 == 2) {
                return dy + 25;
            }
            return dy;
        });

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "value";
        series.dataFields.categoryX = "winning_probability";
        series.columns.template.propertyFields.fill = "#ff0000";
        series.columns.template.propertyFields.stroke = "#000000";

        series.name = "Value (AED)";
        series.columns.template.tooltipText = "{categoryX}% \n[bold]{valueY}[/] AED";
        series.columns.template.fillOpacity = .8;
        series.columns.template.hoverOnFocus = true;

        //REM: Making the columns togglable.
        series.columns.template.togglable = true;

        //REM: Setting the properties of the "hover" state
        var tHoverState = series.columns.template.states.create("hover");
        tHoverState.properties.strokeWidth = 1;
        tHoverState.properties.fill = "#8bc34a";

        var columnTemplate = series.columns.template;

        columnTemplate.strokeWidth = 2;
        columnTemplate.strokeOpacity = 1;


    });
</script>
@endsection