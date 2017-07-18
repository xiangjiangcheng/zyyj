<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
${demo.css}
    </style>
    <script type="text/javascript">
$(function () {
     $.ajax({
        async : true,
        cache : false,
        type : 'get',
        url : 'http://localhost/zyyj//Home/Question/Question_get_status',
        contentType: "application/json",
        data : {
         
        },
        dataType:'json',
        success : function(data, textStatus, jqXHR) {
          
                  $('#container').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '题库状态'
                    },
                   
                    xAxis: {
                        categories: [
                            '困难',
                            '一般',
                            '简单',
                         
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '题目数 (个)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y} 个</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: '题目数',
                        data: [data.difficult, data.normal, data.easy]

                    }]
                }); 
                }  
           });
    
});
    </script>



<div id="container" style="min-width: 310px; height: 450px; width: 800px margin: 0 auto"></div>