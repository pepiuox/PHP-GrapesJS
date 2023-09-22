<!-- jQuery -->
<script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo SITE_PATH; ?>assets/plugins/adminlte/js/adminlte.min.js"></script>

<script>
    $(document).ready(function (){
    $("a").find('.active').each(function () {
    $(this).parent().closest('.has-treeview').addClass("menu-open");
    $('.has-treeview').children('a').first().addClass("active");
    $('.has-treeview').find('a').each(function () {
    $(this).addClass("active");
    });
    });
    });</script>
<script>
    $(document).ready(function(){
    $(".nav-tabs a").click(function(){
    $(this).tab('show');
    });
    });</script>
    <?php if ($fname === 'slider') { ?>
    <!-- Ion Slider -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <!-- Bootstrap slider -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.js"></script>
    <script>
    $(function () {
    /* BOOTSTRAP SLIDER */
    $('.slider').bootstrapSlider();
    /* ION SLIDER */
    $('#range_1').ionRangeSlider({
    min: 0,
            max: 5000,
            from: 1000,
            to: 4000,
            type: 'double',
            step: 1,
            prefix: '$',
            prettify: false,
            hasGrid: true
    });
    $('#range_2').ionRangeSlider();
    $('#range_5').ionRangeSlider({
    min: 0,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: ' mm',
            prettify: false,
            hasGrid: true
    });
    $('#range_6').ionRangeSlider({
    min: - 50,
            max: 50,
            from: 0,
            type: 'single',
            step: 1,
            postfix: '°',
            prettify: false,
            hasGrid: true
    });
    $('#range_4').ionRangeSlider({
    type: 'single',
            step: 100,
            postfix: ' light years',
            from: 55000,
            hideMinMax: true,
            hideFromTo: false
    });
    $('#range_3').ionRangeSlider({
    type: 'double',
            postfix: ' miles',
            step: 10000,
            from: 25000000,
            to: 35000000,
            onChange: function (obj) {
            var t = ''
                    for (var prop in obj) {
            t += prop + ': ' + obj[prop] + '\r\n'
            }
            $('#result').php(t)
            },
            onLoad: function (obj) {
            //
            }
    });
    });
    </script>
<?php } if ($fname === 'ribbons') { ?>

    <!-- Ion Slider -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <!-- Bootstrap slider -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.js"></script>
    <script>
    $(function () {
    /* BOOTSTRAP SLIDER */
    $('.slider').bootstrapSlider();
    /* ION SLIDER */
    $('#range_1').ionRangeSlider({
    min: 0,
            max: 5000,
            from: 1000,
            to: 4000,
            type: 'double',
            step: 1,
            prefix: '$',
            prettify: false,
            hasGrid: true
    });
    $('#range_2').ionRangeSlider();
    $('#range_5').ionRangeSlider({
    min: 0,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: ' mm',
            prettify: false,
            hasGrid: true
    });
    $('#range_6').ionRangeSlider({
    min: - 50,
            max: 50,
            from: 0,
            type: 'single',
            step: 1,
            postfix: '°',
            prettify: false,
            hasGrid: true
    });
    $('#range_4').ionRangeSlider({
    type: 'single',
            step: 100,
            postfix: ' light years',
            from: 55000,
            hideMinMax: true,
            hideFromTo: false
    });
    $('#range_3').ionRangeSlider({
    type: 'double',
            postfix: ' miles',
            step: 10000,
            from: 25000000,
            to: 35000000,
            onChange: function (obj) {
            var t = ''
                    for (var prop in obj) {
            t += prop + ': ' + obj[prop] + '\r\n'
            }
            $('#result').php(t)
            },
            onLoad: function (obj) {
            //
            }
    });
    });</script>
<?php } if ($fname === 'moddals') { ?>
    <!-- SweetAlert2 -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/toastr/toastr.min.js"></script>   
    <script>
    $(function () {
    var Toast = Swal.mixin({
    toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
    });
    $('.swalDefaultSuccess').click(function () {
    Toast.fire({
    icon: 'success',
            title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.swalDefaultInfo').click(function () {
    Toast.fire({
    icon: 'info',
            title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.swalDefaultError').click(function () {
    Toast.fire({
    icon: 'error',
            title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.swalDefaultWarning').click(function () {
    Toast.fire({
    icon: 'warning',
            title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.swalDefaultQuestion').click(function () {
    Toast.fire({
    icon: 'question',
            title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastrDefaultSuccess').click(function () {
    toastr.success("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.")
    });
    $('.toastrDefaultInfo').click(function () {
    toastr.info("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.")
    });
    $('.toastrDefaultError').click(function () {
    toastr.error("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.")
    });
    $('.toastrDefaultWarning').click(function () {
    toastr.warning("Lorem ipsum dolor sit amet, consetetur sadipscing elitr.")
    });
    $('.toastsDefaultDefault').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultTopLeft').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            position: 'topLeft',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultBottomRight').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            position: 'bottomRight',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultBottomLeft').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            position: 'bottomLeft',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultAutohide').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            autohide: true,
            delay: 750,
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultNotFixed').click(function () {
    $(document).Toasts('create', {
    title: 'Toast Title',
            fixed: false,
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultFull').click(function () {
    $(document).Toasts('create', {
    body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
            title: 'Toast Title',
            subtitle: 'Subtitle',
            icon: 'fas fa-envelope fa-lg',
    });
    });
    $('.toastsDefaultFullImage').click(function () {
    $(document).Toasts('create', {
    body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
            title: 'Toast Title',
            subtitle: 'Subtitle',
            image: '../../dist/img/user3-128x128.jpg',
            imageAlt: 'User Picture',
    });
    });
    $('.toastsDefaultSuccess').click(function () {
    $(document).Toasts('create', {
    class: 'bg-success',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultInfo').click(function () {
    $(document).Toasts('create', {
    class: 'bg-info',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultWarning').click(function () {
    $(document).Toasts('create', {
    class: 'bg-warning',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultDanger').click(function () {
    $(document).Toasts('create', {
    class: 'bg-danger',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    $('.toastsDefaultMaroon').click(function () {
    $(document).Toasts('create', {
    class: 'bg-maroon',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
    });
    });
    });</script>   

<?php } if ($fname === 'inline') { ?>
    <!-- jQuery Knob -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- page script -->
    <script>
    $(function () {
    /* jQueryKnob */

    $('.knob').knob({
    /*change : function (value) {
     //console.log("change : " + value);
     },
     release : function (value) {
     console.log("release : " + value);
     },
     cancel : function () {
     console.log("cancel : " + this.value);
     },*/
    draw: function () {

    // "tron" case
    if (this.$.data('skin') == 'tron') {

    var a = this.angle(this.cv)  // Angle
            ,
            sa = this.startAngle          // Previous start angle
            ,
            sat = this.startAngle         // Start angle
            ,
            ea                            // Previous end angle
            ,
            eat = sat + a                 // End angle
            ,
            r = true

            this.g.lineWidth = this.lineWidth

            this.o.cursor
            && (sat = eat - 0.3)
            && (eat = eat + 0.3)

            if (this.o.displayPrevious) {
    ea = this.startAngle + this.angle(this.value)
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3)
            this.g.beginPath();
    this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke();
    }

    this.g.beginPath();
    this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
            this.g.stroke();
    this.g.lineWidth = 2
            this.g.beginPath();
    this.g.strokeStyle = this.o.fgColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
            this.g.stroke();
    return false
    }
    }
    });
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    $('.sparkline').each(function () {
    var $this = $(this)
            $this.sparkline('html', $this.data())
    });
    /* SPARKLINE DOCUMENTATION EXAMPLES https://omnipotent.net/jquery.sparkline/#s-about */
    drawDocSparklines();
    drawMouseSpeedDemo();
    });
    function drawDocSparklines() {

    // Bar + line composite charts
    $('#compositebar').sparkline('html', {
    type: 'bar',
            barColor: '#aaf'
    });
    $('#compositebar').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
    {
    composite: true,
            fillColor: false,
            lineColor: 'red'
    });
    // Line charts taking their values from the tag
    $('.sparkline-1').sparkline();
    // Larger line charts for the docs
    $('.largeline').sparkline('html',
    {
    type: 'line',
            height: '2.5em',
            width: '4em'
    });
    // Customized line chart
    $('#linecustom').sparkline('html',
    {
    height: '1.5em',
            width: '8em',
            lineColor: '#f00',
            fillColor: '#ffa',
            minSpotColor: false,
            maxSpotColor: false,
            spotColor: '#77f',
            spotRadius: 3
    });
    // Bar charts using inline values
    $('.sparkbar').sparkline('html', {type: 'bar'});
    $('.barformat').sparkline([1, 3, 5, 3, 8], {
    type: 'bar',
            tooltipFormat: '{{value:levels}} - {{value}}',
            tooltipValueLookups: {
            levels: $.range_map({
            ':2': 'Low',
                    '3:6': 'Medium',
                    '7:': 'High'
            });
            }
    });
    // Tri-state charts using inline values
    $('.sparktristate').sparkline('html', {type: 'tristate'});
    $('.sparktristatecols').sparkline('html',
    {
    type: 'tristate',
            colorMap: {
            '-2': '#fa7',
                    '2': '#44f'
            }
    });
    // Composite line charts, the second using values supplied via javascript
    $('#compositeline').sparkline('html', {
    fillColor: false,
            changeRangeMin: 0,
            chartRangeMax: 10
    });
    $('#compositeline').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
    {
    composite: true,
            fillColor: false,
            lineColor: 'red',
            changeRangeMin: 0,
            chartRangeMax: 10
    });
    // Line charts with normal range marker
    $('#normalline').sparkline('html',
    {
    fillColor: false,
            normalRangeMin: - 1,
            normalRangeMax: 8
    });
    $('#normalExample').sparkline('html',
    {
    fillColor: false,
            normalRangeMin: 80,
            normalRangeMax: 95,
            normalRangeColor: '#4f4'
    });
    // Discrete charts
    $('.discrete1').sparkline('html',
    {
    type: 'discrete',
            lineColor: 'blue',
            xwidth: 18
    });
    $('#discrete2').sparkline('html',
    {
    type: 'discrete',
            lineColor: 'blue',
            thresholdColor: 'red',
            thresholdValue: 4
    });
    // Bullet charts
    $('.sparkbullet').sparkline('html', {type: 'bullet'});
    // Pie charts
    $('.sparkpie').sparkline('html', {
    type: 'pie',
            height: '1.0em'
    });
    // Box plots
    $('.sparkboxplot').sparkline('html', {type: 'box'});
    $('.sparkboxplotraw').sparkline([1, 3, 5, 8, 10, 15, 18],
    {
    type: 'box',
            raw: true,
            showOutliers: true,
            target: 6
    });
    // Box plot with specific field order
    $('.boxfieldorder').sparkline('html', {
    type: 'box',
            tooltipFormatFieldlist: ['med', 'lq', 'uq'],
            tooltipFormatFieldlistKey: 'field'
    });
    // click event demo sparkline
    $('.clickdemo').sparkline();
    $('.clickdemo').bind('sparklineClick', function (ev) {
    var sparkline = ev.sparklines[0],
            region = sparkline.getCurrentRegionFields();
    value = region.y
            alert('Clicked on x=' + region.x + ' y=' + region.y)
    });
    // mouseover event demo sparkline
    $('.mouseoverdemo').sparkline();
    $('.mouseoverdemo').bind('sparklineRegionChange', function (ev) {
    var sparkline = ev.sparklines[0],
            region = sparkline.getCurrentRegionFields();
    value = region.y
            $('.mouseoverregion').text('x=' + region.x + ' y=' + region.y)
    }).bind('mouseleave', function () {
    $('.mouseoverregion').text('')
    });
    }

    /**
     ** Draw the little mouse speed animated graph
     ** This just attaches a handler to the mousemove event to see
     ** (roughly) how far the mouse has moved
     ** and then updates the display a couple of times a second via
     ** setTimeout();
     **/
    function drawMouseSpeedDemo() {
    var mrefreshinterval = 500 // update display every 500ms
            var lastmousex = - 1
            var lastmousey = - 1
            var lastmousetime
            var mousetravel = 0
            var mpoints = []
            var mpoints_max = 30
            $('html').mousemove(function (e) {
    var mousex = e.pageX
            var mousey = e.pageY
            if (lastmousex > - 1) {
    mousetravel += Math.max(Math.abs(mousex - lastmousex), Math.abs(mousey - lastmousey))
    }
    lastmousex = mousex
            lastmousey = mousey
    });
    var mdraw = function () {
    var md = new Date();
    var timenow = md.getTime();
    if (lastmousetime && lastmousetime != timenow) {
    var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000)
            mpoints.push(pps)
            if (mpoints.length > mpoints_max) {
    mpoints.splice(0, 1)
    }
    mousetravel = 0
            $('#mousespeed').sparkline(mpoints, {
    width: mpoints.length * 2,
            tooltipSuffix: ' pixels per second'
    });
    }
    lastmousetime = timenow
            setTimeout(mdraw, mrefreshinterval)
    }
    // We could use setInterval instead, but I prefer to do it this way
    setTimeout(mdraw, mrefreshinterval);
    }
    </script>
<?php } if ($fname === 'flot') { ?>
    <!-- jQuery UI -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- FLOT CHARTS -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/flot/jquery.flot.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/flot-old/jquery.flot.resize.min.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/flot-old/jquery.flot.pie.min.js"></script>
    <!-- Page script -->
    <script>
    $(function () {
    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [],
            totalPoints = 100

            function getRandomData() {

            if (data.length > 0) {
            data = data.slice(1)
            }

            // Do a random walk
            while (data.length < totalPoints) {

            var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random(); * 10 - 5

                    if (y < 0) {
            y = 0
            } else if (y > 100) {
            y = 100
            }

            data.push(y)
            }

            // Zip the generated y values with the x values
            var res = []
                    for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]])
            }

            return res
            }

    var interactive_plot = $.plot('#interactive', [
    {
    data: getRandomData(),
    }
    ],
    {
    grid: {
    borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor: '#f3f3f3'
    },
            series: {
            color: '#3c8dbc',
                    lines: {
                    lineWidth: 2,
                            show: true,
                            fill: true,
                    },
            },
            yaxis: {
            min: 0,
                    max: 100,
                    show: true
            },
            xaxis: {
            show: true
            }
    }
    )

            var updateInterval = 500 //Fetch data ever x milliseconds
            var realtime = 'on' //If == to on then fetch data every x seconds. else stop fetching
            function update() {

            interactive_plot.setData([getRandomData()])

                    // Since the axes don't change, we don't need to call plot.setupGrid();
                    interactive_plot.draw();
            if (realtime === 'on') {
            setTimeout(update, updateInterval)
            }
            }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === 'on') {
    update();
    }
    //REALTIME TOGGLE
    $('#realtime .btn').click(function () {
    if ($(this).data('toggle') === 'on') {
    realtime = 'on'
    } else {
    realtime = 'off'
    }
    update();
    });
    /*
     * END INTERACTIVE CHART
     */


    /*
     * LINE CHART
     * ----------
     */
    //LINE randomly generated data

    var sin = [],
            cos = []
            for (var i = 0; i < 14; i += 0.5) {
    sin.push([i, Math.sin(i)])
            cos.push([i, Math.cos(i)])
    }
    var line_data1 = {
    data: sin,
            color: '#3c8dbc'
    }
    var line_data2 = {
    data: cos,
            color: '#00c0ef'
    }
    $.plot('#line-chart', [line_data1, line_data2], {
    grid: {
    hoverable: true,
            borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor: '#f3f3f3'
    },
            series: {
            shadowSize: 0,
                    lines: {
                    show: true
                    },
                    points: {
                    show: true
                    }
            },
            lines: {
            fill: false,
                    color: ['#3c8dbc', '#f56954']
            },
            yaxis: {
            show: true
            },
            xaxis: {
            show: true
            }
    });
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
    position: 'absolute',
            display: 'none',
            opacity: 0.8
    }).appendTo('body')
            $('#line-chart').bind('plothover', function (event, pos, item) {

    if (item) {
    var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2)

            $('#line-chart-tooltip').php(item.series.label + ' of ' + x + ' = ' + y)
            .css({
            top: item.pageY + 5,
                    left: item.pageX + 5
            });
    .fadeIn(200)
    } else {
    $('#line-chart-tooltip').hide();
    }

    });
    /* END LINE CHART */

    /*
     * FULL WIDTH STATIC AREA CHART
     * -----------------
     */
    var areaData = [[2, 88.0], [3, 93.3], [4, 102.0], [5, 108.5], [6, 115.7], [7, 115.6],
    [8, 124.6], [9, 130.3], [10, 134.3], [11, 141.4], [12, 146.5], [13, 151.7], [14, 159.9],
    [15, 165.4], [16, 167.8], [17, 168.7], [18, 169.5], [19, 168.0]]
            $.plot('#area-chart', [areaData], {
            grid: {
            borderWidth: 0
            },
                    series: {
                    shadowSize: 0, // Drawing is faster without shadows
                            color: '#00c0ef',
                            lines: {
                            fill: true //Converts the line chart to area chart
                            },
                    },
                    yaxis: {
                    show: false
                    },
                    xaxis: {
                    show: false
                    }
            });
    /* END AREA CHART */

    /*
     * BAR CHART
     * ---------
     */

    var bar_data = {
    data: [[1, 10], [2, 8], [3, 4], [4, 13], [5, 17], [6, 9]],
            bars: {show: true}
    }
    $.plot('#bar-chart', [bar_data], {
    grid: {
    borderWidth: 1,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
    },
            series: {
            bars: {
            show: true, barWidth: 0.5, align: 'center',
            },
            },
            colors: ['#3c8dbc'],
            xaxis: {
            ticks: [[1, 'January'], [2, 'February'], [3, 'March'], [4, 'April'], [5, 'May'], [6, 'June']]
            }
    });
    /* END BAR CHART */

    /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
    {
    label: 'Series2',
            data: 30,
            color: '#3c8dbc'
    },
    {
    label: 'Series3',
            data: 20,
            color: '#0073b7'
    },
    {
    label: 'Series4',
            data: 50,
            color: '#00c0ef'
    }
    ]
            $.plot('#donut-chart', donutData, {
            series: {
            pie: {
            show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                    show: true,
                            radius: 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1
                    }

            }
            },
                    legend: {
                    show: false
                    }
            });
    /*
     * END DONUT CHART
     */

    });
    /*
     * Custom Label formatter
     * ----------------------
     */
    function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
            + label
            + '<br>'
            + Math.round(series.percent) + '%</div>'
    }
    </script>
<?php } if ($fname === 'chartjs') { ?>
    <!-- ChartJS -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/chart.js/Chart.min.js"></script>        
    <!-- page script -->
    <script>
    $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get(); method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

            var areaChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [
                    {
                    label: 'Digital Goods',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                    label: 'Electronics',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: [65, 59, 80, 81, 56, 55, 40]
                    },
                    ]
            }

    var areaChartOptions = {
    maintainAspectRatio: false,
            responsive: true,
            legend: {
            display: false
            },
            scales: {
            xAxes: [{
            gridLines: {
            display: false,
            }
            }],
                    yAxes: [{
                    gridLines: {
                    display: false,
                    }
                    }]
            }
    }

    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas, {
    type: 'line',
            data: areaChartData,
            options: areaChartOptions
    });
    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = $.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
                    data: lineChartData,
                    options: lineChartOptions
            });
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get(); method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
    var donutData = {
    labels: [
            'Chrome',
            'IE',
            'FireFox',
            'Safari',
            'Opera',
            'Navigator'
    ],
            datasets: [
            {
            data: [700, 500, 400, 600, 300, 100],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
            }
            ]
    };
    var donutOptions = {
    maintainAspectRatio: false,
            responsive: true
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
    type: 'doughnut',
            data: donutData,
            options: donutOptions
    });
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get(); method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieData = donutData;
    var pieOptions = {
    maintainAspectRatio: false,
            responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
    type: 'pie',
            data: pieData,
            options: pieOptions
    });
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barChartData = $.extend(true, {}, areaChartData);
    var temp0 = areaChartData.datasets[0];
    var temp1 = areaChartData.datasets[1];
    barChartData.datasets[0] = temp1;
    barChartData.datasets[1] = temp0;
    var barChartOptions = {
    responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
    };
    var barChart = new Chart(barChartCanvas, {
    type: 'bar',
            data: barChartData,
            options: barChartOptions
    });
    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
    var stackedBarChartData = $.extend(true, {}, barChartData);
    var stackedBarChartOptions = {
    responsive: true,
            maintainAspectRatio: false,
            scales: {
            xAxes: [{
            stacked: true
            }],
                    yAxes: [{
                    stacked: true
                    }]
            }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
    type: 'bar',
            data: stackedBarChartData,
            options: stackedBarChartOptions
    });
    });</script>
<?php } if ($fname === 'jsgrid') { ?>
    <!-- jsGrid -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jsgrid/demos/db.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jsgrid/jsgrid.min.js"></script>
    <!-- page script -->
    <script>
    $(function () {
    $("#jsGrid1").jsGrid({
    height: "100%",
            width: "100%",
            sorting: true,
            paging: true,
            data: db.clients,
            fields: [
            {name: "Name", type: "text", width: 150},
            {name: "Age", type: "number", width: 50},
            {name: "Address", type: "text", width: 200},
            {name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name"},
            {name: "Married", type: "checkbox", title: "Is Married"}
            ]
    });
    });</script>
<?php } if ($fname === 'data') { ?>
    <!-- DataTables -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <!-- page script -->
    <script>
    $(function () {
    $("#example1").DataTable({
    "responsive": true,
            "autoWidth": false,
    });
    $('#example2').DataTable({
    "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
    });
    });</script>
<?php }if ($fname === 'mailbox') { ?>
    <!-- Page Script -->
    <script>
        $(function () {
        //Enable check and uncheck all functionality
        $('.checkbox-toggle').click(function () {
        var clicks = $(this).data('clicks')
                if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
                $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
                $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
        });
        //Handle starring for font awesome
        $('.mailbox-star').click(function (e) {
        e.preventDefault();
        //detect type
        var $this = $(this).find('a > i')
                var fa = $this.hasClass('fa')

                //Switch states
                if (fa) {
        $this.toggleClass('fa-star')
                $this.toggleClass('fa-star-o')
        }
        });
        });</script>
<?php } if ($fname === 'advanced') { ?>
    <!-- Select2 -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/moment/moment.min.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

    <!-- Page script -->
    <script>
        $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        });
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
        //Money Euro
        $('[data-mask]').inputmask();
        //Date range picker
        $('#reservationdate').datetimepicker({
        format: 'L'
        });
        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
        timePicker: true,
                timePickerIncrement: 30,
                locale: {
                format: 'MM/DD/YYYY hh:mm A'
                }
        });
        //Date range as a button
        $('#daterange-btn').daterangepicker(
        {
        ranges: {
        'Today': [moment(), moment()],
                'Yesterday': [moment(); .subtract(1, 'days'), moment(); .subtract(1, 'days')],
                'Last 7 Days': [moment(); .subtract(6, 'days'), moment()],
                'Last 30 Days': [moment(); .subtract(29, 'days'), moment()],
                'This Month': [moment(); .startOf('month'), moment(); .endOf('month')],
                'Last Month': [moment(); .subtract(1, 'month').startOf('month'), moment(); .subtract(1, 'month').endOf('month')]
        },
                startDate: moment(); .subtract(29, 'days'),
                endDate: moment();
        },
                function (start, end) {
                $('#reportrange span').php(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
        );
        //Timepicker
        $('#timepicker').datetimepicker({
        format: 'LT'
        });
        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox();
        //Colorpicker
        $('.my-colorpicker1').colorpicker();
        //color picker with addon
        $('.my-colorpicker2').colorpicker();
        $('.my-colorpicker2').on('colorpickerChange', function (event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString(); );
        });
        $("input[data-bootstrap-switch]").each(function () {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        });</script>
<?php } if ($fname === 'editors') { ?>
    <!-- Summernote -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function () {
        // Summernote
        $('.textarea').summernote();
        });</script>
<?php } if ($fname === 'general') { ?>
    <!-- bs-custom-file-input -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>       
    <script>
        $(function () {
        bsCustomFileInput.init();
        });</script>
<?php } if ($fname === 'validation') { ?>

    <!-- jquery-validation -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
    <script>
        $(function () {
        $.validator.setDefaults({
        submitHandler: function () {
        alert("Form successful submitted!");
        }
        });
        $('#quickForm').validate({
        rules: {
        email: {
        required: true,
                email: true
        },
                password: {
                required: true,
                        minlength: 5
                },
                terms: {
                required: true
                },
        },
                messages: {
                email: {
                required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                },
                        password: {
                        required: "Please provide a password",
                                minlength: "Your password must be at least 5 characters long"
                        },
                        terms: "Please accept our terms"
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
                }
        });
        });</script>
<?php } if ($fname === 'fixed-sidebar') { ?>
    <!-- overlayScrollbars -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<?php } if ($fname === 'compose') { ?>
    <!-- Summernote -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- Page Script -->
    <script>
        $(function () {
        //Add text editor
        $('#compose-textarea').summernote();
        });
    </script>
<?php } ?>
