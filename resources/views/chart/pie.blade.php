<div id="{{isset($id)?$id:'piechart'}}" style="width: 100%;"></div>

<script>
    var series = @php echo $series; @endphp;
    Highcharts.chart('{{isset($id)?$id:'piechart'}}', {
        credits:{
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        colors: ['#2E3192', '#50B432', 'red', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4','#1000f4'],
        title: {
            text: '{{isset($chart_title)?$chart_title:'Chart Title'}}'
        },
        tooltip: {
            pointFormat: 'Volume : {point.y}<br/>{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'percentage',
            colorByPoint: true,
            data: series
        }]
    });
</script>



