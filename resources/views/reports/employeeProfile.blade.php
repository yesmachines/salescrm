@extends('layouts.default')
@section('title') Reports of Employee Performance @endsection

@section('content')
<!-- Page Body -->
<div class="hk-pg-body">
    <div class="container-xxl">
        <div class="profile-wrap">
            <div class="profile-img-wrap">
                <img class="img-fluid rounded-5" src="{{asset('dist/img/profile-bg.jpg')}}" alt="Image Description">
            </div>
            <div class="profile-intro">
                <div class="card card-flush mw-400p bg-transparent">
                    <div class="card-body">
                        <div class="avatar avatar-xxl avatar-rounded position-relative mb-2">
                            @if($employee->image_url)
                            <img src="{{asset('storage/'. $employee->image_url)}}" width="50%" alt="user" class="avatar-img border border-4 border-white">
                            @else
                            <img src="{{asset('dist/img/avatar12.jpg')}}" alt="user" class="avatar-img border border-4 border-white">
                            @endif
                            <!-- <span class="badge badge-indicator badge-success  badge-indicator-xl position-bottom-end-overflow-1 me-1"></span> -->
                        </div>
                        <h4>{{$employee->user->name}}
                            <i class="bi-check-circle-fill fs-6 text-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Top endorsed"></i>
                        </h4>
                        <p>
                            {{$employee->designation}} / {{$employee->division}}
                        </p>
                        <ul class="list-inline fs-7 mt-2 mb-0">
                            <li class="list-inline-item d-sm-inline-block d-block mb-sm-0 mb-1 me-3">
                                <i class="bi bi-briefcase me-1"></i>
                                <a href="mailto:{{$employee->user->email}}" target="_blank">{{$employee->user->email}}</a>
                            </li>

                            <li class="list-inline-item d-sm-inline-block d-block mb-sm-0 mb-1 me-3">
                                <i class="bi bi-geo-alt me-1"></i>
                                <a href="#">{{$employee->user->last_login_ip}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="dropdown-divider"></div>
                </div>
            </div>
            <div class="row mt-7">
                <div class="col-3">
                    <div class="card card-border mb-0  h-100">
                        <div class="card-header card-header-action">
                            <h6>Leads Summary</h6>
                        </div>
                        <div class="card-body text-center">
                            <div id="radial_chart_2"></div>
                            <div class="d-inline-block mt-4">
                                <div class="mb-4">
                                    <span class="d-block badge-status lh-1">
                                        <span class="badge bg-primary badge-indicator badge-indicator-nobdr d-inline-block"></span>
                                        <span class="badge-label d-inline-block">Inside</span>
                                    </span>
                                    <span class="d-block text-dark fs-5 fw-medium mb-0 mt-1">{{$leads[0]->inside}}</span>
                                </div>
                                <div>
                                    <span class="badge-status lh-1">
                                        <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                                        <span class="badge-label">Outside</span>
                                    </span>
                                    <span class="d-block text-dark fs-5 fw-medium mb-0 mt-1">{{$leads[0]->outside}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">

                    @livewire('sales-probability', ['assigned' => $employee->id])
                </div>
            </div>


            <div class="row mt-7">
                <div class="col">
                    <div class="card card-border mb-0 h-100">
                        <div class="card-header card-header-action">
                            <h6>Search Filters</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form method="GET">
                                        <label class="form-label">Submitted On</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="start_date" placeholder="From" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['start_date'])? $input['start_date']:'' }}">
                                            <input type="text" class="form-control" name="end_date" placeholder="To" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['end_date'])? $input['end_date']:'' }}">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                            <button class="btn btn-secondary" type="button" onclick="window.location.href=''">Reset</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row  mt-7">
                <div class="col">
                    <div class="title-lg title-wth-divider"><span>Enquires Vs Products</span></div>

                    <div id="brandenquiriesdiv" style="width: 100%;"></div>
                    <div class="mb-4" style="float: right;">
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total No. of Enquiries</span>
                        </span>
                        <span class="text-dark fs-5 fw-medium mb-0 ms-2">{{$overallQuotes['total_enquiry']}}</span>
                    </div>
                </div>
            </div>

            <div class="row  mt-7">
                <div class="col">
                    <div class="title-lg title-wth-divider"><span>Sales Value Vs Products</span></div>

                    <div id="brandquotesdiv" style="width: 100%;"></div>
                    <div class="mb-4" style="float: right;">
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total Sales Value</span>
                        </span>
                        <span class="text-dark fs-5 fw-medium mb-0 ms-2">{{$overallQuotes['total_sales']}} AED</span>
                        <span class=" d-block"></span>
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total Gross Margin</span>
                        </span>
                        <span class=" text-dark fs-5 fw-medium mb-0 ms-2">{{$overallQuotes['total_margin']}} AED</span>
                    </div>
                </div>
            </div>



            <div class="row mt-7">
                <div class="col">
                    <div class="card card-border mb-0 h-100">
                        <div class="card-header card-header-action">
                            <h6>Search Filters</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <select class="form-control" name="yearof">
                                        <option value="{{date('Y')}}">{{date('Y')}}</option>
                                        <option value="{{date('Y') + 1}}">{{date('Y') + 1}}</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row  mt-7">
                <div class="col">
                    <div class="title-lg title-wth-divider"><span>Win Performance Vs Monthly</span></div>

                    <div id="winperformancediv" style="width: 100%;"></div>
                    <div class="mb-4" style="float: right;">
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total Sales Value</span>
                        </span>
                        <span class="text-dark fs-5 fw-medium mb-0 ms-2">{{$overallWin['total_sales']}} AED</span>
                        <span class=" d-block"></span>
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total Gross Margin</span>
                        </span>
                        <span class=" text-dark fs-5 fw-medium mb-0 ms-2">{{$overallWin['total_margin']}} AED</span>
                    </div>
                </div>
            </div>

            <div class="row  mt-7">
                <div class="col">
                    <div class="title-lg title-wth-divider"><span>Product Performance Vs Monthly</span></div>
                    <div id="productperformancediv" style="width: 100%;"></div>
                </div>
            </div>

            <div class="row  mt-7">
                <div class="col">
                    <div class="title-lg title-wth-divider"><span>Win Performance Vs Target</span></div>
                    <div id="wintargetediv" style="width: 100%;"></div>
                    <div class="mb-4" style="float: right;">
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">My Target</span>
                        </span>
                        <span class="text-dark fs-5 fw-medium mb-0 ms-2">{{ $target->target_value }} AED</span>
                        <span class=" d-block"></span>
                        <span class="badge-status lh-1">
                            <span class="badge bg-primary-light-2 badge-indicator badge-indicator-nobdr"></span>
                            <span class="badge-label">Total Gross Margin</span>
                        </span>
                        <span class=" text-dark fs-5 fw-medium mb-0 ms-2">{{$overallWin['total_margin']}} AED</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Body -->

</div>
</div>
<!-- Amcharts Maps JS -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="{{asset('vendors/@amcharts/amcharts4/themes/animated.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/kelly.js"></script>

<!-- Apex JS -->
<script src="{{asset('vendors/apexcharts/dist/apexcharts.min.js')}}"></script>
<script>
    /*Contact Init*/
    $(function() {
        const date = new Date();
        const currentmonth = date.toLocaleString('default', {
            month: 'short'
        });
        /**************** LEADS  *********************/
        var leads = JSON.parse('{!! $leads !!}');
        /*Multiple Chart*/
        var options2 = {
            series: [leads[0].outside, leads[0].inside],
            stroke: {
                lineCap: 'round'
            },
            chart: {
                height: 255,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: "55%",
                    },
                    dataLabels: {
                        showOn: "always",
                        name: {
                            show: false,
                        },
                        value: {
                            fontSize: "1.75rem",
                            show: true,
                            fontWeight: '500'
                        },
                        total: {
                            show: true,
                            formatter: function() {
                                return [(leads[0].leadcount)];
                            }
                        }
                    }
                }
            },
            colors: ['#007D88', '#25cba1'],
            labels: ['Subscriptions', 'Food'],
        };

        var chart2 = new ApexCharts(document.querySelector("#radial_chart_2"), options2);
        chart2.render();

        /********************************** 
         * Brand Vs Enquiry Count 
         * *********************************/

        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);

            if (chart) {
                chart.dispose();
            }
            // Themes end
            var brandSummary = JSON.parse('{!! $brandenquiries !!}');
            // Create chart instance
            var chart = am4core.create("brandenquiriesdiv", am4charts.XYChart);

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
            createSeries("quotecount", "Enquiries");

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

        /****************************************************************************
         * Quotation Value Vs Brand
         *************************************************************************/
        var brandSales = JSON.parse('{!! $brandenquiries !!}');
        // Apply chart themes
        am4core.useTheme(am4themes_animated);
        am4core.useTheme(am4themes_kelly);

        // Create chart instance
        var chart = am4core.create("brandquotesdiv", am4charts.XYChart);

        chart.marginRight = 400;

        // Add data
        chart.data = brandSales;

        //console.log('chart', chart);

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "brand";
        categoryAxis.title.text = "Overall Quotations of Product";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 20;


        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Sales (AED)";

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "salesvalue";
        series.dataFields.categoryX = "brand";
        series.name = "Sales Value";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.stacked = true;

        var series2 = chart.series.push(new am4charts.ColumnSeries());
        series2.dataFields.valueY = "margin";
        series2.dataFields.categoryX = "brand";
        series2.name = "Gross Margin";
        series2.tooltipText = "{name}: [bold]{valueY}[/]";
        series2.stacked = true;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();



        /************************************************
         * Sale Performance Vs Month
         **************************************************/
        var salesPerformace = JSON.parse('{!! $salesPerformace !!}');

        // Apply chart themes
        am4core.useTheme(am4themes_animated);
        am4core.useTheme(am4themes_kelly);

        // Create chart instance
        var chart = am4core.create("winperformancediv", am4charts.XYChart);

        chart.marginRight = 400;

        // Add data
        chart.data = salesPerformace;

        //console.log('chart', chart);

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "month_name";
        categoryAxis.title.text = "Monthly Performance of " + new Date().getFullYear();
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 20;


        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Sales Value (AED)";

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "quotecount";
        series.dataFields.categoryX = "month_name";
        series.name = "No. of Win";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.stacked = true;

        var series2 = chart.series.push(new am4charts.ColumnSeries());
        series2.dataFields.valueY = "salesvalue";
        series2.dataFields.categoryX = "month_name";
        series2.name = "Sales Value";
        series2.tooltipText = "{name}: [bold]{valueY}[/]";
        series2.stacked = true;

        var series3 = chart.series.push(new am4charts.ColumnSeries());
        series3.dataFields.valueY = "margin";
        series3.dataFields.categoryX = "month_name";
        series3.name = "Gross Margin";
        series3.tooltipText = "{name}: [bold]{valueY}[/]";
        series3.stacked = true;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();

        /************************************************
         * Brand Performance Vs Month
         **************************************************/
        var productPerformace = JSON.parse('{!! $productMonth !!}');

        // Apply chart themes
        am4core.useTheme(am4themes_animated);
        am4core.useTheme(am4themes_kelly);

        // Create chart instance
        var chart = am4core.create("productperformancediv", am4charts.XYChart);

        chart.marginRight = 400;

        // Add data
        chart.data = productPerformace;
        //console.log('chart', chart);

        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "month_name";
        categoryAxis.title.text = "Monthly Performance of " + new Date().getFullYear();
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 20;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Enquiries";

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "quotecount";
        series.dataFields.categoryX = "month_name";
        series.name = "No. of Quotations";
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.stacked = true;

        // Add cursor
        chart.cursor = new am4charts.XYCursor();

        /********************************************************
         *  Win Vs Monthly Vs Target 
         *********************************************************/
        var salesPerformace2 = JSON.parse('{!! $salesPerformace !!}');
        var employee_target = JSON.parse('{!! $target !!}');

        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("wintargetediv", am4charts.XYChart);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

            chart.data = salesPerformace2;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.dataFields.category = "month_name";
            categoryAxis.renderer.minGridDistance = 40;
            categoryAxis.fontSize = 12;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.max = 100000;
            valueAxis.strictMinMax = true;
            valueAxis.renderer.minGridDistance = 30;
            // axis break
            var axisBreak = valueAxis.axisBreaks.create();
            axisBreak.startValue = employee_target.target_value;
            axisBreak.endValue = (employee_target.target_value + 10000);
            //axisBreak.breakSize = 0.005;

            // fixed axis break
            var d = (axisBreak.endValue - axisBreak.startValue) / (valueAxis.max - valueAxis.min);
            let x = 0.05 * (1 - d) / d;
            axisBreak.breakSize = 0.05 * (1 - d) / d; // 0.05 means that the break will take 5% of the total value axis height

            // make break expand on hover
            var hoverState = axisBreak.states.create("hover");
            hoverState.properties.breakSize = 1;
            hoverState.properties.opacity = 0.1;
            hoverState.transitionDuration = 1500;

            axisBreak.defaultState.transitionDuration = 1000;


            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryX = "month_name";
            series.dataFields.valueY = "margin";
            series.columns.template.tooltipText = "Margin : {valueY.value}";
            series.columns.template.tooltipY = 0;
            series.columns.template.strokeOpacity = 0;

            // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
            series.columns.template.adapter.add("fill", function(fill, target) {
                return chart.colors.getIndex(target.dataItem.index);
            });

        }); // end am4core.ready()

    });
</script>
@endsection