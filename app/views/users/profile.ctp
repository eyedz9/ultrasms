<?php 

$subscriberlist = array();
//$subscriberlist = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
if(!empty($subscribers)){
	foreach($subscribers as $subscriber){
		$month = date("n",strtotime($subscriber['contact_groups']['created']));
		if(isset($subscriberlist[$month])){
			$subscriberlist[$month] = $subscriberlist[$month] +1;   
                                                                    
		}else{
			$subscriberlist[$month] = 1;
		}
	}
}

$resultsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($subscriberlist[$i])){
		$resultsubscriber[$i] = $subscriberlist[$i];
	}else{
		$resultsubscriber[$i] = 0;
	}
}
$subscriber_list = implode(',',$resultsubscriber);
//$subscriber_list = '0,0,2,0,0,0,0,0,0,0,0,0';
//echo $subscriber_list;

//['key1'] => 3,6,586578,
//['key2'] => 3,6,586578,

//keyword monthly
$keywordsubscriberlist = array();
foreach($keywords as $keyword){
	if(!isset($keywordsubscriberlist[$keyword['Group']['keyword']])){
		$keywordsubscriberlist[$keyword['Group']['keyword']]  = array(0,0,0,0,0,0,0,0,0,0,0,0,0);                        
		unset($keywordsubscriberlist[$keyword['Group']['keyword']][0]);
	} 
}
//$keywordsubscriberlists = array();
$key_words = array();
if(!empty($subscribers)){
	foreach($subscribers as $keywordsubscriber){
		$month = date("n",strtotime($keywordsubscriber['contact_groups']['created']));
		$keyword = $keywordsubscriber['groups']['keyword'];
		$keywordsubscriberlist[$keyword][$month] = $keywordsubscriberlist[$keyword][$month] +1;
	}
}
$finalarray  = array();
$keyword_details = '';
foreach($keywordsubscriberlist as $keywordsubscriberlists=>$value){
	$finalarray[] = array(
	'name'=>$keywordsubscriberlists,
	'type'=>'bar',
	'data'=>implode(',',$value)
	);
  
	if($keyword_details !=''){
		$newkey = "'".$keywordsubscriberlists."'";
		$keyword_details= $keyword_details.','.$newkey;
	}else{
		$keyword_details= "'".$keywordsubscriberlists."'";
	}
}

//current year compare 
$currentyearsubscriberlist = array();
if(!empty($subscribers)){
	foreach($subscribers as $subscriber){
		$month = date("n",strtotime($subscriber['contact_groups']['created']));
		if(isset($currentyearsubscriberlist[$month])){
			$currentyearsubscriberlist[$month] = $currentyearsubscriberlist[$month] +1;   
                                                                    
		}else{
			$currentyearsubscriberlist[$month] = 1;
		}
	}
}

$resultcurrentyearsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($currentyearsubscriberlist[$i])){
		$resultcurrentyearsubscriber[$i] = $currentyearsubscriberlist[$i];
	}else{
		$resultcurrentyearsubscriber[$i] = 0;
	}
}
$subscriber_list_currentyear = implode(',',$resultcurrentyearsubscriber);


//prior year compare 
$prioryearsubscriberlist = array();
if(!empty($subscriberslastyear)){
	foreach($subscriberslastyear as $subscriberlastyear){
		$month = date("n",strtotime($subscriberlastyear['contact_groups']['created']));
		if(isset($prioryearsubscriberlist[$month])){
			$prioryearsubscriberlist[$month] = $prioryearsubscriberlist[$month] +1;   
                                                                    
		}else{
			$prioryearsubscriberlist[$month] = 1;
		}
	}
}

$resultprioryearsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($prioryearsubscriberlist[$i])){
		$resultprioryearsubscriber[$i] = $prioryearsubscriberlist[$i];
	}else{
		$resultprioryearsubscriber[$i] = 0;
	}
}
$subscriber_list_prioryear = implode(',',$resultprioryearsubscriber);


//current unsub year compare 
$currentunsubyearsubscriberlist = array();
if(!empty($un_subscribers)){
	foreach($un_subscribers as $un_subscriber){
		$month = date("n",strtotime($un_subscriber['contact_groups']['created']));
		if(isset($currentunsubyearsubscriberlist[$month])){
			$currentunsubyearsubscriberlist[$month] = $currentunsubyearsubscriberlist[$month] +1;   
                                                                    
		}else{
			$currentunsubyearsubscriberlist[$month] = 1;
		}
	}
}

$resultcurrentunsubyearsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($currentunsubyearsubscriberlist[$i])){
		$resultcurrentunsubyearsubscriber[$i] = $currentunsubyearsubscriberlist[$i];
	}else{
		$resultcurrentunsubyearsubscriber[$i] = 0;
	}
}
$subscriber_list_unsubcurrentyear = implode(',',$resultcurrentunsubyearsubscriber);


//prior unsub year compare 
$prioryearunsubsubscriberlist = array();
if(!empty($un_subscriberslastyear)){
	foreach($un_subscriberslastyear as $un_subscriberlastyear){
		$month = date("n",strtotime($un_subscriberlastyear['contact_groups']['created']));
		if(isset($prioryearunsubsubscriberlist[$month])){
			$prioryearunsubsubscriberlist[$month] = $prioryearunsubsubscriberlist[$month] +1;   
                                                                    
		}else{
			$prioryearunsubsubscriberlist[$month] = 1;
		}
	}
}

$resultpriorunsubyearsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($prioryearunsubsubscriberlist[$i])){
		$resultpriorunsubyearsubscriber[$i] = $prioryearunsubsubscriberlist[$i];
	}else{
		$resultpriorunsubyearsubscriber[$i] = 0;
	}
}
$subscriber_list_unsubprioryear = implode(',',$resultpriorunsubyearsubscriber);

$year = date('Y');
$lastyear = date('Y', strtotime('-1 year'));
$year_details = "'".$lastyear."','".$year."'";


//print_r($sourcecounts);
//print_r ($currentyearsubscriberlist);
//echo $keywordcounts['groups']['keyword'];

//carriers monthly
$carrierssubscriberlist = array();
foreach($carriers as $carrier){
	if(!isset($carrierssubscriberlist[$carrier['contacts']['carrier']])){
		$carrierssubscriberlist[$carrier['contacts']['carrier']]  = array(0,0,0,0,0,0,0,0,0,0,0,0,0);                        
		unset($carrierssubscriberlist[$carrier['contacts']['carrier']][0]);
	} 
}


if(!empty($carriers)){
	foreach($carriers as $carriersubscriber){
		$month = date("n",strtotime($carriersubscriber['contact_groups']['created']));
		$carrier = $carriersubscriber['contacts']['carrier'];
		$carrierssubscriberlist[$carrier][$month] = $carrierssubscriberlist[$carrier][$month] +1;
	}
}

$finalarraycarrier  = array();
$carrier_details = '';
foreach($carrierssubscriberlist as $carrierssubscriberlists=>$value){
	$finalarraycarrier[] = array(
	'name'=>$carrierssubscriberlists,
	'type'=>'bar',
	'data'=>implode(',',$value)
	);
	if($carrier_details !=''){
		$newkey = "'".$carrierssubscriberlists."'";
		$carrier_details= $carrier_details.','.$newkey;
	}else{
		$carrier_details= "'".$carrierssubscriberlists."'";
	}
}

//Subs by Source
$sourcesubscriberlist = array();

if(!isset($sourcesubscriberlist["SMS"])){
		$sourcesubscriberlist["SMS"]  = array(0,0,0,0,0,0,0,0,0,0,0,0,0);                        
		unset($sourcesubscriberlist["SMS"][0]);
	} 
if(!isset($sourcesubscriberlist["Web Widget"])){
		$sourcesubscriberlist["Web Widget"]  = array(0,0,0,0,0,0,0,0,0,0,0,0,0);                        
		unset($sourcesubscriberlist["Web Widget"][0]);
	} 
if(!isset($sourcesubscriberlist["Kiosk"])){
		$sourcesubscriberlist["Kiosk"]  = array(0,0,0,0,0,0,0,0,0,0,0,0,0);                        
		unset($sourcesubscriberlist["Kiosk"][0]);
	} 


if(!empty($subscribers)){
	foreach($subscribers as $sourcesubscriber){
		$month = date("n",strtotime($sourcesubscriber['contact_groups']['created']));
		$source= $sourcesubscriber['contact_groups']['subscribed_by_sms'];
                if ($source == "1"){
		   $sourcesubscriberlist["SMS"][$month] = $sourcesubscriberlist["SMS"][$month] +1;
                }else if ($source == "2") {
                   $sourcesubscriberlist["Web Widget"][$month] = $sourcesubscriberlist["Web Widget"][$month] +1;
                }else if ($source == "3") {
                   $sourcesubscriberlist["Kiosk"][$month] = $sourcesubscriberlist["Kiosk"][$month] +1;
                }
	}
}
$finalarraysource  = array();
$source_details = '';
foreach($sourcesubscriberlist as $sourcesubscriberlists=>$value){
	$finalarraysource[] = array(
	'name'=>$sourcesubscriberlists,
	'type'=>'bar',
	'data'=>implode(',',$value)
	);

       if($source_details !=''){
		$newkey = "'".$sourcesubscriberlists."'";
		$source_details= $source_details.','.$newkey;
	}else{
		$source_details= "'".$sourcesubscriberlists."'";
	}
	
}


//unsubscribes list
$unsubscriberlist = array();
if(!empty($un_subscribers)){
	foreach($un_subscribers as $un_subscriber){
		$month = date("n",strtotime($un_subscriber['contact_groups']['created']));
		if(isset($unsubscriberlist[$month])){
			$unsubscriberlist[$month] = $unsubscriberlist[$month] +1;                        
		}else{
			$unsubscriberlist[$month] = 1;
		}
	}
}
$resultunsubscriber = array();
for($i=1;$i<=12;$i++){
	if(isset($unsubscriberlist[$i])){
		$resultunsubscriber[$i] = $unsubscriberlist[$i];
	}else{
		$resultunsubscriber[$i] = 0;
	}
}
$unsubscriber_list = implode(',',$resultunsubscriber);
//inbox
$inboxlist = array();
if(!empty($inbox)){
	foreach($inbox as $inboxs){
		$day = date("j",strtotime($inboxs['Log']['created']));
		if(isset($inboxlist[$day])){
			$inboxlist[$day] = $inboxlist[$day] +1;                        
		}else{
			$inboxlist[$day] = 1;
		}
	}
}
$inbox_lists = array();
for($i=1;$i<=date("j");$i++){
	if(isset($inboxlist[$i])){
		$recordinbox = $inboxlist[$i];
	}else{
		$recordinbox = 0;
	}
	$inbox_lists[$i] = $recordinbox;
	
}
$inbox_list = 0;
foreach($inbox_lists as $lastest_inbox_list){
	if($inbox_list >= 0){
		$inbox_list=$inbox_list.','.$lastest_inbox_list;
	}else{
		$inbox_list = $lastest_inbox_list;
	}
}
//outbox
$outboxlist = array();
if(!empty($outbox)){
	foreach($outbox as $outboxs){
		$day = date("j",strtotime($outboxs['Log']['created']));
		if(isset($outboxlist[$day])){
			$outboxlist[$day] = $outboxlist[$day] +1;                        
		}else{
			$outboxlist[$day] = 1;
		}
	}
}
$outbox_lists = array();
for($i=1;$i<=date("j");$i++){
	if(isset($outboxlist[$i])){
		$recordoutbox = $outboxlist[$i];
	}else{
		$recordoutbox = 0;
	}
	$outbox_lists[$i] = $recordoutbox;
	
}
$outbox_list = 0;
foreach($outbox_lists as $lastest_outbox_list){
	if($outbox_list >= 0){
		$outbox_list=$outbox_list.','.$lastest_outbox_list;
	}else{
		$outbox_list = $lastest_outbox_list;
	}
}
//vminbox
$vminboxlist = array();
//if(!empty($outbox)){
	foreach($vminbox as $vminboxs){
		$day = date("j",strtotime($vminboxs['Log']['created']));
		if(isset($vminboxlist[$day])){
			$vminboxlist[$day] = $vminboxlist[$day] +1;                        
		}else{
			$vminboxlist[$day] = 1;
		}
	}
//}
$vminbox_lists = array();
for($i=1;$i<=date("j");$i++){
	if(isset($vminboxlist[$i])){
		$recordvminbox = $vminboxlist[$i];
	}else{
		$recordvminbox = 0;
	}
	$vminbox_lists[$i] = $recordvminbox;
}
$vminbox_list = 0;
foreach($vminbox_lists as $lastest_vminbox_list){
	if($vminbox_list >= 0){
		$vminbox_list=$vminbox_list.','.$lastest_vminbox_list;
	}else{
		$vminbox_list = $lastest_vminbox_list;
	}
}
?>

<script>
var Dashboard = function() {
    return {

        initJQVMAP: function() {
            if (!jQuery().vectorMap) {
                return;
            }
        },
		initCharts: function() {
            if (!jQuery.plot) {
                return;
            }

            function showChartTooltip(x, y, xValue, yValue) {
                $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 40,
                    border: '0px solid #ccc',
                    padding: '2px 6px',
                    'background-color': '#fff'
                }).appendTo("body").fadeIn(200);
            }

            var data = [];
            var totalPoints = 250;

            // random data generator for plot charts

            function getRandomData() {
                if (data.length > 0) data = data.slice(1);
                // do a random walk
                while (data.length < totalPoints) {
                    var prev = data.length > 0 ? data[data.length - 1] : 50;
                    var y = prev + Math.random() * 10 - 5;
                    if (y < 0) y = 0;
                    if (y > 100) y = 100;
                    data.push(y);
                }
                // zip the generated y values with the x values
                var res = [];
                for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                return res;
            }

            function randValue() {
                return (Math.floor(Math.random() * (1 + 50 - 20))) + 10;
            }

            var subscriber = [<?php echo $subscriber_list;?>];
            var unsubscriber = [<?php echo $unsubscriber_list;?>];
            if ($('#site_statistics').size() != 0) {

                $('#site_statistics_loading').hide();
                $('#site_statistics_content').show();

                var plot_statistics = $.plot($("#site_statistics"), [{
                        data: subscriber,
                        lines: {
                            show: true,
                            fill: true,
                            radius: 5,
                            lineWidth: 3
                        },
						color: '#000',
                        shadowSize: 0
                    },{
                        data: unsubscriber,
                        lines: {
                            show: true,
                            fill: true,
                            radius: 5,
                            lineWidth: 3
                        },
                        color: '#ccc',
                        shadowSize: 0
                    }],

                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 16,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

                var previousPoint = null;
                $("#site_statistics").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1]);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            }
			if ($('#site_activities').size() != 0) {
                //site activities
                var previousPoint2 = null;
                $('#site_activities_loading').hide();
                $('#site_activities_content').show();

                var data1 = [
                    ['DEC', 300],
                    ['JAN', 600],
                    ['FEB', 1100],
                    ['MAR', 1200],
                    ['APR', 860],
                    ['MAY', 1200],
                    ['JUN', 1450],
                    ['JUL', 1800],
                    ['AUG', 1200],
                    ['SEP', 600]
                ];
				var data2 = [
                    ['DEC', 601],
                    ['JAN', 234],
                    ['FEB', 1132],
                    ['MAR', 1200],
                    ['APR', 860],
                    ['MAY', 20],
                    ['JUN', 1450],
                    ['JUL', 90],
                    ['AUG', 1200],
                    ['SEP', 1320]
                ];
				var data3 = [
                    ['DEC', 182],
                    ['JAN', 791],
                    ['FEB', 30],
                    ['MAR', 434],
                    ['APR', 860],
                    ['MAY', 20],
                    ['JUN', 10],
                    ['JUL', 30],
                    ['AUG', 1200],
                    ['SEP', 182]
                ];


                var plot_statistics = $.plot($("#site_activities"),

                    [{
                        data:data1,
						lines: {
                            show: true,
                            fill: true,
                            radius: 5,
                            lineWidth: 3
                        },
                        color: ['#BAD9F5'],
						shadowSize: 0
                    },{
                        data:data2,
                        lines: {
                            show: true,
                            fill: true,
                            radius: 5,
                            lineWidth: 3
                        },
                        color: ['#BAD9F5'],
						shadowSize: 0
                    },{
                        data:data3,
                        lines: {
                            show: true,
                            fill: true,
                            radius: 5,
                            lineWidth: 3
                        },
                        color: ['#BAD9F5'],
						shadowSize: 0
                    }],

                    {

                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        yaxis: {
                            ticks: 5,
                            tickDecimals: 0,
                            tickColor: "#eee",
                            font: {
                                lineHeight: 14,
                                style: "normal",
                                variant: "small-caps",
                                color: "#6F7B8A"
                            }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

                $("#site_activities").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + 'M$');
                        }
                    }
                });

                $('#site_activities').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });
            }
        },
		initEasyPieCharts: function() {
            if (!jQuery().easyPieChart) {
                return;
            }

            $('.easy-pie-chart .number.transactions').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getBrandColor('yellow')
            });

            $('.easy-pie-chart .number.visits').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getBrandColor('green')
            });

            $('.easy-pie-chart .number.bounce').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getBrandColor('red')
            });

            $('.easy-pie-chart-reload').click(function() {
                $('.easy-pie-chart .number').each(function() {
                    var newValue = Math.floor(100 * Math.random());
                    $(this).data('easyPieChart').update(newValue);
                    $('span', this).text(newValue);
                });
            });
        },
		initSparklineCharts: function() {
            if (!jQuery().sparkline) {
                return;
            }
            $("#sparkline_sms_sent").sparkline([<?php echo $outbox_list;?>], {
                type: 'line',
                lineColor: '#f08000',
                fillColor: '#c0d0f0',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#35aa47',
                negBarColor: '#e02222'
            });

            $("#sparkline_sms_received").sparkline([<?php echo $inbox_list;?>], {
                type: 'line',
                lineColor: '#007f00',
                fillColor: '#e2ffc6',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#ffb848',
                negBarColor: '#e02222'
            });
	    
            $("#sparkline_voice_sent").sparkline([<?php echo $vminbox_list;?>], {
                type: 'line',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#32c5d2',
                negBarColor: '#e02222'
            });
	    
            $("#sparkline_weekly").sparkline([<?php echo $inbox_list;?>], {
                type: 'bar',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#35aa47',
                negBarColor: '#e02222'
            });

            $("#sparkline_monthly").sparkline([<?php echo $outbox_list;?>], {
                type: 'bar',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#ffb848',
                negBarColor: '#e02222'
            });
	    
            $("#sparkline_overall").sparkline([<?php echo $vminbox_list;?>], {
                type: 'bar',
                width: '100',
                barWidth: 5,
                height: '55',
                barColor: '#32c5d2',
                negBarColor: '#e02222'
            });
        },
       
       init: function() {
            this.initJQVMAP();
            this.initCharts();
			this.initEasyPieCharts();
			this.initSparklineCharts();
        }
    };

}();
jQuery(document).ready(function() {
    Dashboard.init(); // init metronic core componets
	// ECHARTS
    require.config({
        paths: {
            echarts: '../assets/global/plugins/echarts/'
        }
    });
    // DEMOS
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/pie',
            'echarts/chart/gauge'

          
        ],
        function(ec) {
var macarons2 = {

color: [
              '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
              '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
          ],

          title: {
              itemGap: 8,
              textStyle: {
                  fontWeight: 'normal',
                  color: '#408829'
              }
          },

          dataRange: {
              color: ['#1f610a', '#97b58d']
          },

          toolbox: {
              color: ['#408829', '#408829', '#408829', '#408829']
          },

          tooltip: {
              backgroundColor: 'rgba(0,0,0,0.5)',
              axisPointer: {
                  type: 'line',
                  lineStyle: {
                      color: '#408829',
                      type: 'dashed'
                  },
                  crossStyle: {
                      color: '#408829'
                  },
                  shadowStyle: {
                      color: 'rgba(200,200,200,0.3)'
                  }
              }
          },

          dataZoom: {
              dataBackgroundColor: '#eee',
              fillerColor: 'rgba(64,136,41,0.2)',
              handleColor: '#408829'
          },
          grid: {
              borderWidth: 0
          },

          categoryAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },

          valueAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitArea: {
                  show: true,
                  areaStyle: {
                      color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },
          timeline: {
              lineStyle: {
                  color: '#408829'
              },
              controlStyle: {
                  normal: {color: '#408829'},
                  emphasis: {color: '#408829'}
              }
          },

          k: {
              itemStyle: {
                  normal: {
                      color: '#68a54a',
                      color0: '#a9cba2',
                      lineStyle: {
                          width: 1,
                          color: '#408829',
                          color0: '#86b379'
                      }
                  }
              }
          },
          map: {
              itemStyle: {
                  normal: {
                      areaStyle: {
                          color: '#ddd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  },
                  emphasis: {
                      areaStyle: {
                          color: '#99d2dd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  }
              }
          },
          force: {
              itemStyle: {
                  normal: {
                      linkStyle: {
                          strokeColor: '#408829'
                      }
                  }
              }
          },
          chord: {
              padding: 4,
              itemStyle: {
                  normal: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  },
                  emphasis: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  }
              }
          },
          gauge: {
              startAngle: 225,
              endAngle: -45,
              axisLine: {
                  show: true,
                  lineStyle: {
                      color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                      width: 8
                  }
              },
              axisTick: {
                  splitNumber: 10,
                  length: 12,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              axisLabel: {
                  textStyle: {
                      color: 'auto'
                  }
              },
              splitLine: {
                  length: 18,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              pointer: {
                  length: '90%',
                  color: 'auto'
              },
              title: {
                  textStyle: {
                      color: '#333'
                  }
              },
              detail: {
                  textStyle: {
                      color: 'auto'
                  }
              }
          },
          textStyle: {
              fontFamily: 'Arial, Verdana, sans-serif'
          }
      };


    var roma = {
    color: ['#E01F54','#b8d2c7','#f5e8c8','#001852','#c6b38e',
            '#a4d8c2','#f3d999','#d3758f','#dcc392','#2e4783',
            '#82b6e9','#ff6347','#a092f1','#0a915d','#eaf889',
            '#6699FF','#ff6666','#3cb371','#d5b158','#38b6b6'],
    dataRange: {
        color:['#e01f54','#e7dbc3'],
        textStyle: {
            color: '#333'
        }
    },
    k: {
        itemStyle: {
            normal: {
                color: '#e01f54',          
                color0: '#001852',      
                lineStyle: {
                    width: 1,
                    color: '#f5e8c8',
                    color0: '#b8d2c7'   
                }
            }
        }
    },
    pie: {
        itemStyle: {
            normal: {
                
                borderColor: '#fff',
                borderWidth: 1,
                label: {
                    show: true,
                    position: 'outer',
                  textStyle: {color: '#1b1b1b'},
                  lineStyle: {color: '#1b1b1b'}
                    
                },
                labelLine: {
                    show: true,
                    length: 20,
                    lineStyle: {
                        
                        width: 1,
                        type: 'solid'
                    }
                }
            },
            emphasis: {
                
                borderColor: 'rgba(0,0,0,0)',
                borderWidth: 1,
                label: {
                    show: false
                },
                labelLine: {
                    show: false,
                    length: 20,
                    lineStyle: {
                        width: 1,
                        type: 'solid'
                    }
                }
            }
        }
    },
    
    map: {
        itemStyle: {
            normal: {
                borderColor: '#fff',
                borderWidth: 1,
                areaStyle: {
                    color: '#ccc'
                },
                label: {
                    show: false,
                    textStyle: {
                        color: 'rgba(139,69,19,1)'
                    }
                }
            },
            emphasis: {                 
                
                borderColor: 'rgba(0,0,0,0)',
                borderWidth: 1,
                areaStyle: {
                    color: '#f3d999'
                },
                label: {
                    show: false,
                    textStyle: {
                        color: 'rgba(139,69,19,1)'
                    }
                }
            }
        }
    },
    
    force : {
        itemStyle: {
            normal: {
                label: {
                    show: false
                },
                nodeStyle : {
                    brushType : 'both',
                    strokeColor : '#5182ab'
                },
                linkStyle : {
                    strokeColor : '#5182ab'
                }
            },
            emphasis: {
                label: {
                    show: false
                },
                nodeStyle : {},
                linkStyle : {}
            }
        }
    },

    gauge : {
        axisLine: {            
            show: true,        
            lineStyle: {       
                color: [[0.2, '#E01F54'],[0.8, '#b8d2c7'],[1, '#001852']], 
                width: 8
            }
        },
        axisTick: {            
            splitNumber: 10,   
            length :12,       
            lineStyle: {       
                color: 'auto'
            }
        },
        axisLabel: {           
            textStyle: {       
                color: 'auto'
            }
        },
        splitLine: {           
            length : 18,         
            lineStyle: {       
                color: 'auto'
            }
        },
        pointer : {
            length : '90%',
            color : 'auto'
        },
        title : {
            textStyle: {       
                color: '#333'
            }
        },
        detail : {
            textStyle: {       
                color: 'auto'
            }
        }
    }
};


            //--- BAR ---
            var myChart = ec.init(document.getElementById('echarts_bar'));

            myChart.setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Subscribers', 'Un-Subscribers']
                },
               
                toolbox: {
                    show: true,
                    orient : 'vertical',
                    feature: {
                        mark: {
                            show: false
                        },
                        dataView: {
                            show: true,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [{
                    name: 'Subscribers',
                    type: 'bar',
					<?php if($subscriber_list !=''){?>
						data: [<?php echo $subscriber_list; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>
                   
                }, {
                    name: 'Un-Subscribers',
                    type: 'bar',
					<?php if($unsubscriber_list !=''){?>
						data: [<?php echo $unsubscriber_list; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>

                }]
            });

            var myChartYearCompare = ec.init(document.getElementById('echarts_bar_year'),roma);

            myChartYearCompare.setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [<?php echo $year_details;?>]
                },
               
                toolbox: {
                    show: true,
                    orient : 'vertical',
                    feature: {
                        mark: {
                            show: false
                        },
                        dataView: {
                            show: true,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [{
                    name: '<?php echo $lastyear; ?>',
                    type: 'bar',
					<?php if($subscriber_list_prioryear !=''){?>
						data: [<?php echo $subscriber_list_prioryear; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>
                   
                }, {
                    name: '<?php echo $year; ?>',
                    type: 'bar',
					<?php if($subscriber_list_currentyear !=''){?>
						data: [<?php echo $subscriber_list_currentyear; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>

                }]
            });

            var myChartUnsubYearCompare = ec.init(document.getElementById('echarts_bar_unsubyear'),roma);

            myChartUnsubYearCompare.setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [<?php echo $year_details;?>]
                },
               
                toolbox: {
                    show: true,
                    orient : 'vertical',
                    feature: {
                        mark: {
                            show: false
                        },
                        dataView: {
                            show: true,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [{
                    name: '<?php echo $lastyear; ?>',
                    type: 'bar',
					<?php if($subscriber_list_unsubprioryear !=''){?>
						data: [<?php echo $subscriber_list_unsubprioryear; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>
                   
                }, {
                    name: '<?php echo $year; ?>',
                    type: 'bar',
					<?php if($subscriber_list_unsubcurrentyear !=''){?>
						data: [<?php echo $subscriber_list_unsubcurrentyear; ?>],
					<?php }else{?>
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
					<?php }?>

                }]
            });
	    
            var myChartMonth = ec.init(document.getElementById('echarts_bar_monthly'));
            myChartMonth.setOption({
                tooltip: {
                    trigger: 'axis'
                },
				legend: {
                    data: [<?php echo $keyword_details;?>]
                },
               toolbox: {
                    show: true,
                    orient : 'vertical',
                    feature: {
                        mark: {
                            show: false
                        },
                        dataView: {
                            show: true,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [
				<?php if(!empty($finalarray)){?>
				<?php foreach($finalarray as $finalarr){?>
				{
                    name: '<?php echo $finalarr['name'];?>',
                    type: '<?php echo $finalarr['type'];?>',
                    data: [<?php echo $finalarr['data'];?>]
                },
				<?php } ?>
				<?php }else{ ?>
					{
						name: 'No Data',
						type: 'bar',
						data: [0,0,0,0,0,0,0,0,0,0,0,0]
					},
				<?php } ?>
				]
            });



      var myChartPieArea = ec.init(document.getElementById('echarts_pie_area'));
      
      myChartPieArea.setOption({
        title: {
          text: 'All Years',
           textStyle: {
                  fontWeight: 'normal',
                  color: '#b7b7b7'
              }
        },
        tooltip: {
          trigger: 'item',
          formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
          x: 'center',
          y: 'bottom',
          data: [<?php echo $keyword_details;?>]
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: false,
              type: ['pie'],
              option: {
                funnel: {
                  x: '25%',
                  width: '50%',
                  funnelAlign: 'center'
                }
              }
            },
           dataView: {
             show: true,
             readOnly: false
           },
            restore: {
              show: true,
              title: "Restore"
            },
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        series: [{
          name: 'Keyword',
          type: 'pie',
          width: '40%',
          radius: ['35%', '55%'],
          center: ['50%', 170],
          itemStyle: {
            normal: {
              label: {
                show: true
              },
              labelLine: {
                show: true
              }
            },
            emphasis: {
              label: {
                show: true,
                position: 'center',
                textStyle: {
                  fontSize: '14',
                  fontWeight: 'normal'
                }
              }
            }
          },
          data: [
          <?php if(!empty($keywordcounts)){?>
          <?php foreach($keywordcounts as $keywordcount){?>
          {
               value: <?php echo $keywordcount['0']['count'];?>,
               name: '<?php echo $keywordcount['groups']['keyword'];?>'
          },
          <?php } ?>
          <?php }else{ ?>
          {
               value: 0,
               name: ''
          },
          <?php } ?>

         ]
        }]
      });


var myChartPieCarrier = ec.init(document.getElementById('echarts_pie_carrier'),roma);
      
      myChartPieCarrier.setOption({
        title: {
          text: 'All Years',
          textStyle: {     
                fontWeight: 'normal',  
                color: '#b7b7b7'
            }
        },
        tooltip: {
          trigger: 'item',
          formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
          x: 'center',
          y: 'bottom',
          data: [<?php echo $carrier_details;?>]
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: false,
              type: ['pie'],
option: {
                funnel: {
                  x: '25%',
                  width: '50%',
                  funnelAlign: 'center',
                  max: 1548
                }
              }
            },
           dataView: {
             show: true,
             readOnly: false
           },
            restore: {
              show: true,
              title: "Restore"
            },
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        series: [{
          name: 'Carrier',
          type: 'pie',
          radius: '55%',
          center: ['50%', 170],
          roseType: 'area',
          x: '50%',
          max: 80,
          sort: 'ascending',
          data: [
          <?php if(!empty($carriers)){?>
          <?php foreach($carriers as $carriercount){?>
          {
               value: <?php echo $carriercount['0']['count'];?>,
               name: '<?php echo $carriercount['contacts']['carrier'];?>'
          },
          <?php } ?>
          <?php }else{ ?>
          {
               value: 0,
               name: ''
          },
          <?php } ?>

         ]
        }]
      });

var dataStyle = {
        normal: {
          label: {
            show: false
          },
          labelLine: {
            show: false
          }
        }
      };

      var placeHolderStyle = {
        normal: {
          color: 'rgba(0,0,0,0)',
          label: {
            show: false
          },
          labelLine: {
            show: false
          }
        },
        emphasis: {
          color: 'rgba(0,0,0,0)'
        }
      };

var myChartPieSource = ec.init(document.getElementById('echarts_pie_source'),macarons2);
      
      myChartPieSource.setOption({
       title: {
          text: 'All Years',
          textStyle: {   
                fontWeight: 'normal',    
                color: '#b7b7b7'
            }
        },
        tooltip: {
          trigger: 'item',
          formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
          x: 'center',
          y: 'bottom',
          data: [<?php echo $source_details;?>]
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: false,
              type: ['pie'],
              option: {
                funnel: {
                  x: '25%',
                  width: '50%',
                  funnelAlign: 'center',
                  max: 1548
                }
              }
            },
           dataView: {
             show: true,
             readOnly: false
           },
            restore: {
              show: true,
              title: "Restore"
            },
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        series: [{
          name: 'Source',
          type: 'pie',
          radius: [25, 110],
          center: ['50%', 170],
          roseType: 'area',
          x: '50%',
          max: 40,
          sort: 'ascending',
          data: [
          <?php if(!empty($sourcecounts)){?>
          <?php foreach($sourcecounts as $sourcecount){?>
          {
               value: <?php echo $sourcecount['0']['count'];?>,
               <?php if ($sourcecount['contact_groups']['source'] == '1') {?>
               name: 'SMS'
               <?php }else if ($sourcecount['contact_groups']['source'] == '2') {?>
               name: 'Web Widget'
               <?php }else if ($sourcecount['contact_groups']['source'] == '3') {?>
               name: 'Kiosk'
               <?php } ?>
          },
          <?php } ?>
          <?php }else{ ?>
          {
               value: 0,
               name: ''
          },
          <?php } ?>

         ]
        }]
      });

var myChartGauge = ec.init(document.getElementById('echart_guage'));

      myChartGauge.setOption({
        tooltip: {
          formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
          show: true,
          feature: {
            restore: {
              show: false,
              title: "Restore"
            },
           dataView: {
             show: false,
             readOnly: false
           },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        series: [{
          name: 'Rating',
          type: 'gauge',
          center: ['50%', 90],
          startAngle: 140,
          endAngle: -140,
          min: 0,
          max: 100,
          precision: 0,
          splitNumber: 10,
          axisLine: {
            show: true,
            lineStyle: {
              color: [
                [0.2, 'lightgreen'],
                [0.4, 'orange'],
                [0.8, 'skyblue'],
                [1, '#ff4500']
              ],
              width: 30
            }
          },
          axisTick: {
            show: true,
            splitNumber: 5,
            length: 8,
            lineStyle: {
              color: '#eee',
              width: 1,
              type: 'solid'
            }
          },
          axisLabel: {
            show: false,
            formatter: function(v) {
              switch (v + '') {
                case '10':
                  return '10';
                case '30':
                  return '30';
                case '60':
                  return '60';
                case '90':
                  return '90';
                default:
                  return '';
              }
            },
            textStyle: {
              color: '#333'
            }
          },
          splitLine: {
            show: true,
            length: 30,
            lineStyle: {
              color: '#eee',
              width: 2,
              type: 'solid'
            }
          },
          pointer: {
            length: '50%',
            width: 8,
            color: 'auto'
          },
          title: {
            show: true,
            offsetCenter: ['-65%', -10],
            textStyle: {
              color: '#333',
              fontSize: 15
            }
          },
          detail: {
            show: true,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 0,
            borderColor: '#ccc',
            width: 80,
            height: 4,
            offsetCenter: ['-65%', 7],
            formatter: '{value}%',
            textStyle: {
              color: 'auto',
              fontSize: 16
            }
          },
          data: [{
            value: <?php echo ROUND($percentage,1);?>,
            name: 'Rating'
          }]
        }]
      });

var myChart7Gauge = ec.init(document.getElementById('echart7_guage'));

      myChart7Gauge.setOption({
        tooltip: {
          formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
          show: true,
          feature: {
            restore: {
              show: false,
              title: "Restore"
            },
           dataView: {
             show: false,
             readOnly: false
           },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        series: [{
          name: 'Rating',
          type: 'gauge',
          center: ['50%', 90],
          startAngle: 140,
          endAngle: -140,
          min: 0,
          max: 100,
          precision: 0,
          splitNumber: 10,
          axisLine: {
            show: true,
            lineStyle: {
              color: [
                [0.2, 'lightgreen'],
                [0.4, 'orange'],
                [0.8, 'skyblue'],
                [1, '#ff4500']
              ],
              width: 30
            }
          },
          axisTick: {
            show: true,
            splitNumber: 5,
            length: 8,
            lineStyle: {
              color: '#eee',
              width: 1,
              type: 'solid'
            }
          },
          axisLabel: {
            show: false,
            formatter: function(v) {
              switch (v + '') {
                case '10':
                  return '10';
                case '30':
                  return '30';
                case '60':
                  return '60';
                case '90':
                  return '90';
                default:
                  return '';
              }
            },
            textStyle: {
              color: '#333'
            }
          },
          splitLine: {
            show: true,
            length: 30,
            lineStyle: {
              color: '#eee',
              width: 2,
              type: 'solid'
            }
          },
          pointer: {
            length: '50%',
            width: 8,
            color: 'auto'
          },
          title: {
            show: true,
            offsetCenter: ['-65%', -10],
            textStyle: {
              color: '#333',
              fontSize: 15
            }
          },
          detail: {
            show: true,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 0,
            borderColor: '#ccc',
            width: 80,
            height: 4,
            offsetCenter: ['-65%', 7],
            formatter: '{value}%',
            textStyle: {
              color: 'auto',
              fontSize: 16
            }
          },
          data: [{
            value: <?php echo ROUND($weeklypercentage,1);?>,
            name: 'Rating'
          }]
        }]
      });

var myChartMonthGauge = ec.init(document.getElementById('echartmonth_guage'));

      myChartMonthGauge.setOption({
        tooltip: {
          formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
          show: true,
          feature: {
            restore: {
              show: false,
              title: "Restore"
            },
           dataView: {
             show: false,
             readOnly: false
           },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        series: [{
          name: 'Rating',
          type: 'gauge',
          center: ['50%', 90],
          startAngle: 140,
          endAngle: -140,
          min: 0,
          max: 100,
          precision: 0,
          splitNumber: 10,
          axisLine: {
            show: true,
            lineStyle: {
              color: [
                [0.2, 'lightgreen'],
                [0.4, 'orange'],
                [0.8, 'skyblue'],
                [1, '#ff4500']
              ],
              width: 30
            }
          },
          axisTick: {
            show: true,
            splitNumber: 5,
            length: 8,
            lineStyle: {
              color: '#eee',
              width: 1,
              type: 'solid'
            }
          },
          axisLabel: {
            show: false,
            formatter: function(v) {
              switch (v + '') {
                case '10':
                  return '10';
                case '30':
                  return '30';
                case '60':
                  return '60';
                case '90':
                  return '90';
                default:
                  return '';
              }
            },
            textStyle: {
              color: '#333'
            }
          },
          splitLine: {
            show: true,
            length: 30,
            lineStyle: {
              color: '#eee',
              width: 2,
              type: 'solid'
            }
          },
          pointer: {
            length: '50%',
            width: 8,
            color: 'auto'
          },
          title: {
            show: true,
            offsetCenter: ['-65%', -10],
            textStyle: {
              color: '#333',
              fontSize: 15
            }
          },
          detail: {
            show: true,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 0,
            borderColor: '#ccc',
            width: 80,
            height: 4,
            offsetCenter: ['-65%', 7],
            formatter: '{value}%',
            textStyle: {
              color: 'auto',
              fontSize: 16
            }
          },
          data: [{
            value: <?php echo ROUND($monthlypercentage,1);?>,
            name: 'Rating'
          }]
        }]
      });

var myChartYearGauge = ec.init(document.getElementById('echartyear_guage'));

      myChartYearGauge.setOption({
        tooltip: {
          formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
          show: true,
          feature: {
            restore: {
              show: false,
              title: "Restore"
            },
           dataView: {
             show: false,
             readOnly: false
           },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        series: [{
          name: 'Rating',
          type: 'gauge',
          center: ['50%', 90],
          startAngle: 140,
          endAngle: -140,
          min: 0,
          max: 100,
          precision: 0,
          splitNumber: 10,
          axisLine: {
            show: true,
            lineStyle: {
              color: [
                [0.2, 'lightgreen'],
                [0.4, 'orange'],
                [0.8, 'skyblue'],
                [1, '#ff4500']
              ],
              width: 30
            }
          },
          axisTick: {
            show: true,
            splitNumber: 5,
            length: 8,
            lineStyle: {
              color: '#eee',
              width: 1,
              type: 'solid'
            }
          },
          axisLabel: {
            show: false,
            formatter: function(v) {
              switch (v + '') {
                case '10':
                  return '10';
                case '30':
                  return '30';
                case '60':
                  return '60';
                case '90':
                  return '90';
                default:
                  return '';
              }
            },
            textStyle: {
              color: '#333'
            }
          },
          splitLine: {
            show: true,
            length: 30,
            lineStyle: {
              color: '#eee',
              width: 2,
              type: 'solid'
            }
          },
          pointer: {
            length: '50%',
            width: 8,
            color: 'auto'
          },
          title: {
            show: true,
            offsetCenter: ['-65%', -10],
            textStyle: {
              color: '#333',
              fontSize: 15
            }
          },
          detail: {
            show: true,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 0,
            borderColor: '#ccc',
            width: 80,
            height: 4,
            offsetCenter: ['-65%', 7],
            formatter: '{value}%',
            textStyle: {
              color: 'auto',
              fontSize: 16
            }
          },
          data: [{
            value: <?php echo ROUND($yearlypercentage,1);?>,
            name: 'Rating'
          }]
        }]
      });

            
            window.onresize = function() {
                   myChartMonth.resize();
                   myChart.resize();
                   myChartPieArea.resize();
                   myChartPieCarrier.resize();
                   myChartPieSource.resize();
                   myChartGauge.resize();
                   myChart7Gauge.resize();
                   myChartMonthGauge.resize();
                   myChartYearGauge.resize();
                   myChartYearCompare.resize();
                   myChartUnsubYearCompare.resize();
            };
        }
    );



 

});




</script>

<div class="page-content-wrapper">
	<div class="page-content" >              
		


<h3 class="page-title"> Dashboard
			<small>dashboard & statistics</small>
		</h3>
                        
<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/profile">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Profile</span>
					</li>
				</ul>                
			</div>
			 <?php echo $this->Session->flash(); ?>
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat blue">
							<div class="visual">
								<i class="fa fa-phone"></i>
							</div>
							<div class="details">
								<div class="number">
									<?php echo $loggedUser['User']['assigned_number']?>
								</div>
								<div class="desc"> Primary Number </div>
							</div>
							<?php 
                                $pay_activation_fee=PAY_ACTIVATION_FEES;
                                if($loggedUser['User']['assigned_number']>0){ ?>
									<?php if($loggedUser['User']['number_limit_count'] < $loggedUser['User']['number_limit']){ ?>
										<?php if(API_TYPE==1){?>
											<a class="more nyroModal" href="<?php echo SITE_URL;?>/nexmos/searchcountry"> <b>Get New Number</b>
												<i class="m-icon-swapright m-icon-white"></i>
											</a>
										<?php }else if(API_TYPE==3){?>
											<a class="more nyroModal" href="<?php echo SITE_URL;?>/plivos/searchcountry"> <b>Get New Number</b>
												<i class="m-icon-swapright m-icon-white"></i>
											</a>
										<?php }else if(API_TYPE==0){?>
											<a class="more nyroModal" href="<?php echo SITE_URL;?>/twilios/searchcountry"> <b>Get New Number</b>
												<i class="m-icon-swapright m-icon-white"></i>
											</a>
										<?php }else if(API_TYPE==2){?>
												<span class="more" >Short Code</span>
										<?php } ?>
                                    <?php }else if($loggedUser['User']['number_limit_count'] == $loggedUser['User']['number_limit'] && CHARGE_FOR_ADDITIONAL_NUMBERS == 1){ ?>
											<a class="more nyroModal" href="<?php echo SITE_URL;?>/users/purchasenumber"> <b>Get New Number</b>
												<i class="m-icon-swapright m-icon-white"></i>
											</a>
                                    <?php } else { ?>
                                            <span class="more" >Primary Number</span>
									<?php  } ?>
                                <?php } else if($pay_activation_fee==2){ ?>
									<?php if(API_TYPE==1){?>
										<a class="more nyroModal" href="<?php echo SITE_URL;?>/nexmos/searchcountry"> <b>Get New Number</b>
											<i class="m-icon-swapright m-icon-white"></i>
										</a>
									<?php }else if(API_TYPE==0){?>
										<a class="more nyroModal" href="<?php echo SITE_URL;?>/twilios/searchcountry"> <b>Get New Number</b>
											<i class="m-icon-swapright m-icon-white"></i>
										</a>
									<?php }else if(API_TYPE==3){?>
										<a class="more nyroModal" href="<?php echo SITE_URL;?>/plivos/searchcountry"> <b>Get New Number</b>
											<i class="m-icon-swapright m-icon-white"></i>
										</a>
                                    <?php }else if(API_TYPE==2){ ?>
                                            <span class="more" >Short Code</span>
									<?php } ?>
								<?php  } ?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat red">
							<div class="visual">
								<i class="fa fa-comments"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $loggedUser['User']['sms_balance']?>"><?php echo $loggedUser['User']['sms_balance']?></span> </div>
								<div class="desc"> SMS Credits </div>
							</div>
							<span class="more" >SMS Credits
								<!--<i class="m-icon-swapright m-icon-white"></i>-->
							</span>
                                                        
							
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat green">
							<div class="visual">
								<i class="fa fa-bullhorn"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $loggedUser['User']['voice_balance']?>"><?php echo $loggedUser['User']['voice_balance']?></span>
								</div>
								<div class="desc"> Voice Credits </div>
							</div>
							<span class="more" >Voice Credits
								<!--<i class="m-icon-swapright m-icon-white"></i>-->
							</span>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat purple">
							<div class="visual">
								<i class="fa fa-globe"></i>
							</div>
							<div class="details">
								<div class="number"> 
									<?php echo $loggedUser['User']['timezone'];?></div>
								<div class="desc">  Timezone </div>
							</div>
							<a class="more" href="<?php echo SITE_URL;?>/users/edit">Timezone
								<i class="m-icon-swapright m-icon-white"></i>
							</a>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
<!--***** Gauges ******-->

<div class="row">
<div class="col-md-12" >

<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-speedometer font-yellow"></i>
								<span class="caption-subject font-yellow bold uppercase">Success Rating</span>
								<span class="caption-helper"> <a href="javascript:;" data-container="body" data-trigger="hover" data-content="Percentage of people who have subscribed vs unsubscribed in the given timeframes below. If 10 people subscribed and 1 unsubscribed, you will have a 90% success rating" data-original-title="Success Rating" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a></span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="" class="remove"> </a>
                                    </div>
						
</div>

<div class="portlet-body">
							<div class="row">
								<div class="col-md-3">
									<div class="easy-pie-chart" style="background: #fdfdfd none repeat scroll 0 0;border: 1px solid #ededed;">
										<div id="echart7_guage" style="height:170px;"></div>
								        
										<a class="title" href="#null">Past 7 Days</a>
                                                                        </div>

								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								<div class="col-md-3">
									<div class="easy-pie-chart" style="background: #fdfdfd none repeat scroll 0 0;border: 1px solid #ededed;">
										<div id="echartmonth_guage" style="height:170px;"></div>
								        
										<a class="title" href="#null">Current Month</a>
                                                                        </div>
								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								<div class="col-md-3">
									<div class="easy-pie-chart" style="background: #fdfdfd none repeat scroll 0 0;border: 1px solid #ededed;">
										<div id="echartyear_guage" style="height:170px;"></div>
								        
										<a class="title" href="#null">Current Year</a>
                                                                        </div>
								</div>

                                                                <div class="col-md-3">
									<div class="easy-pie-chart" style="background: #fdfdfd none repeat scroll 0 0;border: 1px solid #ededed;">
										<div id="echart_guage" style="height:170px;"></div>
								        
										<a class="title" href="#null">Overall</a>
                                                                        </div>
								</div>
							</div>
						</div>
</div>
                           

</div>


</div>


<!--**** END Gauges ******-->

<!--***** PIE CHARTS ******-->
<div class="row">
			<div class="col-md-4">

<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="icon-pie-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase">subscribers</span>
										<span class="caption-helper">by keyword</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>

                                    </div>
								</div>
								<div class="portlet-body">
                                                                <div id="echarts_pie_area" style="height:400px;"></div>
								</div>
							</div>


                           </div>

<div class="col-md-4" >

<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="icon-pie-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase">subscribers</span>
										<span class="caption-helper">by source</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>

                                    </div>
								</div>
								<div class="portlet-body">
                                                                <div id="echarts_pie_source" style="height:400px;"></div>
								</div>
							</div>

                           </div>

<div class="col-md-4" >

<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="icon-pie-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase">subscribers</span>
										<span class="caption-helper">by carrier</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>

                                    </div>
								</div>
								<div class="portlet-body">
                                                                <div id="echarts_pie_carrier" style="height:400px;"></div>
								</div>
							</div>
                           </div>


</div>
<!--****************************-->


	<div id="sortable_portlets" class="row ui-sortable">
			<div class="col-md-6 column sortable" >
							<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase"> subs/unsubs </span>
										<span class="caption-helper">Monthly</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                    </div>
								</div>
								<div class="portlet-body">
									<div id="echarts_bar" style="height: 400px"></div>
								</div>
							</div>

<!--******COMPARE SUBS YEARS*****-->
<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase">subs</span>
										<span class="caption-helper">Current Year vs Prior Year</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                    </div>
								</div>
								<div class="portlet-body">
									<div id="echarts_bar_year" style="height: 400px"></div>
								</div>
							</div>


<!--*****************-->

<!--<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-purple-plum"></i>
										<span class="caption-subject font-purple-plum bold uppercase"> subs (by source) </span>
										<span class="caption-helper">Monthly </span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                    </div>
								</div>
								<div class="portlet-body">
									<div id="echarts_bar_source" style="height: 400px"></div>
								</div>
							</div>-->

                                         <!--<div class="portlet portlet-sortable light">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<i class="icon-target font-yellow"></i>
								<span class="caption-subject font-yellow bold uppercase">Success Rating</span>
								<span class="caption-helper"> <a href="javascript:;" data-container="body" data-trigger="hover" data-content="Percentage of people who have subscribed vs unsubscribed in the given timeframes below. If 10 people subscribed and 1 unsubscribed, you will have a 90% success rating" data-original-title="Success Rating" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a></span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-4">
									<div class="easy-pie-chart">
										<div class="number transactions" data-percent="<?php echo abs(ROUND($weeklypercentage,1));?>">
											<span><?php echo abs(ROUND($weeklypercentage,1));?></span>%<canvas height="75" width="75"></canvas></div>
										<a class="title" href="#null">Past 7 Days</a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								<div class="col-md-4">
									<div class="easy-pie-chart">
										<div class="number visits" data-percent="<?php echo abs(ROUND($monthlypercentage,1));?>">
											<span><?php echo abs(ROUND($monthlypercentage,1));?></span>%<canvas height="75" width="75"></canvas> </div>
										<a class="title" href="#null">Current Month</a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								<div class="col-md-4">
									<div class="easy-pie-chart">
										<div class="number bounce" data-percent="<?php echo ROUND($percentage,1);?>">
											<span><?php echo ROUND($percentage,1);?></span>%<canvas height="75" width="75"></canvas></div>
										<a class="title" href="#null">Overall</a>
									</div>
								</div>
							</div>
						</div>
					</div>-->

                                       <div class="portlet portlet-sortable light ">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<!--<i class="fa fa-phone font-blue"></i>-->
								<span class="caption-subject font-blue bold uppercase">Secondary Numbers Assigned </span>
							</div>
								<div class="tools">
									<a href="" class="collapse"> </a>
									<a href="" class="remove"> </a>
                                </div>
						</div>
							<div class="portlet-body">
									<div class="col-md-12 ">
										<div class="portlet box blue">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-phone"></i></div>
												<div class="tools">
													<a href="javascript:;" class="expand"></a>
												</div>
											</div>
											<div class="portlet-body portlet-collapsed">
												<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Number</th>
																<th>SMS</th>
																<th>MMS</th>
																<th>Voice</th>
															</tr>
														</thead>
														<!--<tbody>	-->
															<?php 
															$i = 0;
															foreach($numbers as $number){
																$class = null;
																					
																if ($i++ % 2 == 0) {
																	$class = ' class="altrow"';
																}
															
															?>														
															<tr> 
																<td><?php echo $number['UserNumber']['number'] ?></td>
																<td>
																	<?php if($number['UserNumber']['sms']==1){ ?>
																		<i class="fa fa-check"></i>
																	<?php } ?>
																</td>
																<td>
																	<?php if($number['UserNumber']['mms']==1){ ?>
																		<i class="fa fa-check"></i>
																	<?php } ?>
																</td>
																<td>
																	<?php if($number['UserNumber']['voice']==1){ ?>
																		<i class="fa fa-check"></i>
																	<?php } ?>
																</td>
															</tr>
															<?php } ?>
														<!--</tbody>-->
													</table>
												</div>
											</div>
										</div>
									</div>
								<div class="scroller-footer">
									<div class="btn-arrow-link pull-right">
										<?php if(API_TYPE==0){?>										
										<a href="<?php echo SITE_URL;?>/users/viewallnumber_twillio">See All Records</a>
										<i class="icon-arrow-right"></i>
										<?php }else if(API_TYPE==1){?>
											<a href="<?php echo SITE_URL;?>/users/viewallnumber_nexmo">See All Records</a>
											<i class="icon-arrow-right"></i>
										<?php }else if(API_TYPE==3){?>
											<a href="<?php echo SITE_URL;?>/users/viewallnumber_plivo">See All Records</a>
											<i class="icon-arrow-right"></i>
										<?php } ?>
									</div>
								</div>
						</div>
</div>

<div class="portlet portlet-sortable light ">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<i class="fa fa-cart-arrow-down font-red-sunglo"></i>
								<span class="caption-subject font-red-sunglo bold uppercase">Current Credit Package</span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped">                                      
								<tbody>
									<tr>
										<td><b>Credit package</b></td>
										<td>
										<?php if(!empty($packages)){ ?>
											(<?php echo ucfirst($packages['MonthlyPackage']['package_name']); ?>)
<?php if ($loggedUser['User']['monthly_stripe_subscription_id'] !=''){  ?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Cancel', true), array('controller'=>'users','action' => 'cancel_monthly_subscription'), array('class' => 'btn red btn-outline btn-sm'),sprintf(__('Are you sure you want to cancel your subscription?',true))) ;?> 
<?php } ?>	

											<?php }else{?>
											(None)
											<?php } ?>
										</td>                                               
									</tr>	
									
									  <tr>
										<td><b>Next renewal date</b> </td>
										<td><?php if(!empty($packages)){
											$date=strtotime($loggedUser['User']['next_renewal_dates']); 
											list($year, $month, $day) = explode('-', $loggedUser['User']['next_renewal_dates']); 
											if (checkdate($month,$day,$year)){?>
												
											(<?php echo $datereplace=date('Y-m-d',$date);?>)
											<?php } else { ?>
											<?php echo "(None)"; }?>
																
											<?php 	}else{ ?>
											(None)
											<?php } ?>
										</td>
									</tr>
								</tbody>
							</table>
							<?php 
							 $payment=PAYMENT_GATEWAY;
							if($payment=='1'){?>
							<h2><?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalpayment'),array('escape' =>false));?></h2>
							<?php }else if($payment=='2'){ ?>
							<h2><?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'stripepayment'),array('escape' =>false))?></h2>
							<?php }else if($payment=='3'){ ?>
							
							<h3> 
							<?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'stripepayment'),array('escape' =>false))?> - or -
							<?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalpayment'),array('escape' =>false));?>
							</h3>
							<?php } ?>
						</div>




</div>
<div class="portlet portlet-sortable-empty"> </div>
</div>
<!--******END COLUMN*****-->

<div class="col-md-6 column sortable" >

<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase"> subs (by Keyword)</span>
										<span class="caption-helper">Monthly</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>

                                    </div>
								</div>
								<div class="portlet-body">
                                                                <div id="echarts_bar_monthly" style="height:400px;"></div>
								</div>
							</div>

<!--******COMPARE UNSUBS YEARS*****-->
<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-blue-hoki"></i>
										<span class="caption-subject font-blue-hoki bold uppercase">unsubs</span>
										<span class="caption-helper">Current Year vs Prior Year</span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                    </div>
								</div>
								<div class="portlet-body">
									<div id="echarts_bar_unsubyear" style="height: 400px"></div>
								</div>
							</div>


<!--*****************-->



<!--<div class="portlet portlet-sortable light">
								<div class="portlet-title ui-sortable-handle">
									<div class="caption">
										<i class="icon-bar-chart font-grey-gallery"></i>
										<span class="caption-subject font-grey-gallery bold uppercase"> subs (by carrier) </span>
										<span class="caption-helper">Monthly </span>
									</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                    </div>
								</div>
								<div class="portlet-body">
									<div id="echarts_bar_carrier" style="height: 400px"></div>
								</div>
							</div>-->


<div class="portlet portlet-sortable light " id="my-portlet">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<i class="icon-bubbles  font-purple"></i>
								<span class="caption-subject font-purple bold uppercase">Messages</span>
								<span class="caption-helper"> # of messages</span>
							</div>
                                   <div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <!--<a href="<?php echo SITE_URL;?>/logs/index" title="Logs" class="config"></a>-->
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_sms_sent"></div>
										<a class="title" href="<?php echo SITE_URL;?>/logs/index/smsoutbox"> SMS Sent
										<i class="icon-arrow-right"></i>
										</a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								<div class="col-md-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_sms_received"></div>
										<a class="title" href="<?php echo SITE_URL;?>/logs/index/smsinbox">SMS Received
										<i class="icon-arrow-right"></i>
										</a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm"> </div>
								
                                                                <?php if (API_TYPE !=2) { ?>
                                                                <div class="col-md-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_voice_sent"></div>
										<a class="title" href="<?php echo SITE_URL;?>/logs/index/broadcast">Voice Sent
										<i class="icon-arrow-right"></i>
										</a>
									</div>
								</div>
                                                                <?php } ?> 
							</div>
						</div>
					</div>
		

					<div class="portlet portlet-sortable light tasks-widget ">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<!--<i class="fa fa-paypal font-green-haze hide"></i>-->
								<span class="caption-subject font-green bold uppercase">Past Receipts</span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">  
								 <div class="col-md-12">
									<div class="portlet box green">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-money"></i> </div>
											<div class="tools">
												<a href="javascript:;" class="expand"> </a>
											</div>
										</div>
										<div class="portlet-body portlet-collapsed">
											<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Amount</th>
															<th>Txn ID</th>
															<th>Payment</th>
<th>Package</th>
															<th>Receipt Date</th>
														</tr>
													</thead>
													<!--<tbody>	-->
														<?php 
															$i = 0;
															foreach($invoicedetils as $invoicedetil){
																$class = null;
																					
																if ($i++ % 2 == 0) {
																	$class = ' class="altrow"';
																}
														?>													
														<tr> 
														<td>
														<?php 
														$currencycode=PAYMENT_CURRENCY_CODE;
														if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
														<?php echo '$'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='JPY'){ ?>
														<?php echo '¥'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='BRL'){ ?>
														<?php echo 'R$'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='PHP'){ ?>
														<?php echo '₱'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='ILS'){ ?>
														<?php echo '₪'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='EUR'){ ?>
														<?php echo '€'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='GBP'){ ?>
														<?php echo '£'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
														<?php echo 'kr'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } else if($currencycode=='CHF'){ ?>
														<?php echo 'CHF'.$invoicedetil['Invoice']['amount'] ?></td>
														<?php } ?>
														<td><?php echo $invoicedetil['Invoice']['txnid'] ?></td>
														<td>
														<?php 
														   if($invoicedetil['Invoice']['type']==0){
															echo "PayPal";
														   }else{
															echo "Credit Card";
														   }
														?>
														</td>
<td class="tc"><?php echo $invoicedetil['Invoice']['package_name'] ?></td>
														<td class="tc"><?php echo $invoicedetil['Invoice']['created'] ?></td>
														</tr>
														<?php } ?>
													<!--</tbody>-->
												</table>
											</div>
										</div>
									</div>
								</div>
							<div class="task-footer">
								<div class="btn-arrow-link pull-right">
									<a href="<?php echo SITE_URL;?>/users/viewallreceipt">See All Records</a>
									<i class="icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</div>
				
					
			
<?php if(CHARGE_FOR_ADDITIONAL_NUMBERS == 0){?>

				   <div class="portlet portlet-sortable light ">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<i class="fa fa-sitemap font-red-sunglo"></i>
								<span class="caption-subject font-red-sunglo bold uppercase">referred users</span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="<?php echo SITE_URL;?>/referrals/index" title="Referrals" class="config"></a>
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Referred Users(Activated/Paid)</th>
											<th>Overall Credited Commissions</th>
											<th>Unpaid Commissions</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $statistic['referredUser'];?></td>                     
											<td><?php 
											   $currencycode=PAYMENT_CURRENCY_CODE;
											   if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
													$<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='JPY'){ ?>
													¥<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='EUR'){ ?>
													€<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='GBP'){ ?>
													£<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
													kr<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='CHF'){ ?>
													CHF<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='BRL'){ ?>
													R$<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='PHP'){ ?>
													₱<?php echo $statistic['overAllCredit'];?></b>
													<?php } else if($currencycode=='ILS'){ ?>
													₪<?php echo $statistic['overAllCredit'];?></b>
													<?php }?>
												</td>                                                
												<td>
												<?php if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
													$<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='JPY'){ ?>
													¥<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='EUR'){ ?>
													€<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='GBP'){ ?>
													£<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
													kr<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='CHF'){ ?>
													CHF<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='BRL'){ ?>
													R$<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='PHP'){ ?>
													₱<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
													<?php } else if($currencycode=='ILS'){ ?>
													₪<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
												<?php }?>
												</td>
										</tr>											
									</tbody>
								</table>
							</div>
						</div>
					</div>
<?php } else {?>


					<div class="portlet portlet-sortable light ">
						<div class="portlet-title ui-sortable-handle">
							<div class="caption">
								<i class="fa fa-cart-arrow-down font-red-sunglo"></i>
								<span class="caption-subject font-red-sunglo bold uppercase">Current Numbers Package</span>
							</div>
<div class="tools">
                                        <a href="" class="collapse"> </a>
                                        <a href="" class="remove"> </a>
                                    </div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped">                                      
								<tbody>
									<tr>
										<td><b>Numbers package</b></td>
										<td>
										<?php if(!empty($numberpackages)){ ?>
											(<?php echo ucfirst($numberpackages['MonthlyNumberPackage']['package_name']); ?>)
<?php if ($loggedUser['User']['monthly_number_subscription_id'] !=''){  ?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Cancel', true), array('controller'=>'users','action' => 'cancel_monthly_numbers_subscription'), array('class' => 'btn red btn-outline btn-sm'),sprintf(__('Are you sure you want to cancel your numbers subscription?',true))) ;?> 
<?php } ?>	

											<?php }else{?>
											(None)
											<?php } ?>
										</td>                                               
									</tr>	
									
									  <tr>
										<td><b>Next renewal date</b> </td>
										<td><?php if(!empty($numberpackages)){
											$date=strtotime($loggedUser['User']['number_next_renewal_dates']); 
											list($year, $month, $day) = explode('-', $loggedUser['User']['number_next_renewal_dates']); 
											if (checkdate($month,$day,$year)){?>
												
											(<?php echo $datereplace=date('Y-m-d',$date);?>)
											<?php } else { ?>
											<?php echo "(None)"; }?>
																
											<?php 	}else{ ?>
											(None)
											<?php } ?>
										</td>
									</tr>
								</tbody>
							</table>
							<?php 
if($loggedUser['User']['number_limit_count'] == $loggedUser['User']['number_limit']){
							 $payment=PAYMENT_GATEWAY;
							if($payment=='1'){?>
							<h2><?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalnumbers'),array('escape' =>false));?></h2>
							<?php }else if($payment=='2'){ ?>
							<h2><?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'stripenumbers'),array('escape' =>false))?></h2>
							<?php }else if($payment=='3'){ ?>
							
							<h3> 
							<?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'stripenumbers'),array('escape' =>false))?> - or -
							<?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalnumbers'),array('escape' =>false));?>
							</h3>
							<?php }} ?>
						</div>
					</div>
<div class="portlet portlet-sortable-empty"> </div>
				</div>


<?php } ?>
				</div>

			</div>  
</div>
              
		


</div>					



  </div>  
<script src="<?php echo SITE_URL; ?>/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_URL; ?>/assets/pages/scripts/portlet-draggable.min.js" type="text/javascript"></script>       