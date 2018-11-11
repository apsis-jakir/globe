<div id="linechart" style="width: 100%;"></div>
<script>
    var line_series_current = @php echo $lineConfig["lineSeriesCurrent"]; @endphp;
    var line_series_prevous = @php echo $lineConfig["lineSeriesPrev"]; @endphp;
    Highcharts.chart('linechart', {
        credits:{
            enabled: false
        },
        colors: ['#2E3192','#910000'],
        chart: {
            type: '{{$lineConfig['type']}}',
            height:'{{(isset($lineConfig["height"])?$lineConfig["height"]:400)}}'
        },
        title: {
            text: '{{$lineConfig['chart_title']}}'
        },
//        subtitle: {
//            text: 'Source: WorldClimate.com'
//        },
        xAxis: {
            categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'Octber', 'November', 'December']
        },
        yAxis: {
            title: {
                text: '{{$lineConfig['yAxis_title']}}'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: false
                },
                enableMouseTracking: true
            }
        },
        series: [{
            name: '{{(isset($columnConfig["sname-1"])?$columnConfig["sname-1"]:'Name-1')}}',
            data: line_series_current
        }, {
            name: '{{(isset($columnConfig["sname-2"])?$columnConfig["sname-2"]:'Name-2')}}',
            data: line_series_prevous
        }]
    });
</script>



