<div id="container" style="width:100%;"></div>
<script>
    var categories = @php echo $columnConfig["categories"]; @endphp;
    var series_current = @php echo $columnConfig["series_current"]; @endphp;
    var series_prevous = @php echo $columnConfig["series_prevous"]; @endphp;
    $(function () {
        var myChart = Highcharts.chart('container', {
            credits:{
                enabled: false
            },
            colors: ['#2E3192','#910000'],
            chart: {
                type: '{{$columnConfig['type']}}',
                height:'{{(isset($columnConfig["height"])?$columnConfig["height"]:400)}}'
            },
            title: {
                text: '{{$columnConfig['chart_title']}}'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: '{{$columnConfig['yAxis_title']}}'
                }
            },
            series: [{
                name: '{{(isset($columnConfig["sname-1"])?$columnConfig["sname-1"]:'Name-1')}}',
                data: series_current
            }, {
                name: '{{(isset($columnConfig["sname-2"])?$columnConfig["sname-2"]:'Name-2')}}',
                data: series_prevous
            }]
        });
    });
</script>



