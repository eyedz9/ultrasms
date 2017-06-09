	<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>


<?php

 echo $this->Html->script('charts/highcharts.js');



   //$graphTitle = 'Blank';
	//echo $number_list.'fff';
		for($i=0;$i<=4;$i++)
		{
		if($i == 0){
						$j = 'A';
						}
						if($i == 1){
							$j = 'B';
						}
						if($i == 2){
							$j = 'C';
						}
						if($i == 3){
							$j = 'D';
						}
		
		
		
		
		
			$cat[]=$j;
			
		}
		$graphTitle=$questions;
		
$cat_List = json_encode($cat);	
	


?>
	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"><?php __('View Polls');?></h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
								<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li><span><?php __('View Polls');?> </span></li>
					</ul>  
					<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
								<i class="fa fa-angle-down"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li>
									<?php
										$navigation = array(
										'Back' => '/polls/question_list',
										);				
										$matchingLinks = array();
										foreach ($navigation as $link) {
										if (preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base)))) {
										$matchingLinks[strlen($link)] = $link;
										}
										}
										krsort($matchingLinks);
										$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
										$out = array();
										foreach ($navigation as $title => $link) {
										$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
										}
										echo join("\n", $out);
									?>
								</li>							
							</ul>
						</div>
					</div>				
				</div>			
				<div class="clearfix"></div>
				  <?php echo $this->Session->flash(); ?>	
				  <div class="m-heading-1 border-white m-bordered">								  	
					
						<div id="container_graph">
							<p></p>
						</div>
						
				</div>
			<script type="text/javascript">
var chart;
var chart_graph;
 



function callFirst(){

  chart_graph = new Highcharts.Chart({
      chart: {
         renderTo: 'container_graph',
         defaultSeriesType: 'column'
      },
      title: {
         text: '<?php echo $graphTitle;?>'
      },
      subtitle: {
         text: ''
      },
      xAxis: {
         //categories: "'A', 'B', 'C', 'D'",
		 categories: <?php echo $cat_List;?>
         
      },
      yAxis: {
         min: 0,
         title: {
            text: 'No of Vote'
         }
      },
      legend: {
         layout: 'vertical',
         backgroundColor: '#FFFFFF',
         align: 'left',
         verticalAlign: 'top',
         x: 100,
         y: 70,
         floating: true,
         shadow: true
      },
      tooltip: {
         formatter: function() {
            return ''+
               this.x +': '+ this.y +' Vote';
         }
      },
      plotOptions: {
         column: {
            pointPadding: 0.2,
            
            borderWidth: 0
         }
      },
           series: [{
         name: ' Answer',
		data:  <?php echo $caller_list;?>
     
   
      }]
   });	
	
	
	
      
   
  
	
	
 }
 
 window.onload=callFirst();
$(window).resize();
 
/*setTimeout(
function(){
	callFirst();
},
5000
);*/
</script>				