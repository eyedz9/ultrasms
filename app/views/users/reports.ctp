<script>
 function confirmfrmSubmit(){ 
	var a=document.getElementById('UserDate').value;	
	var b=document.getElementById('UserDateend').value;	
	if(a==''){
	alert("Please select start date");
	return false;		
	}
	if(b==''){
	alert("Please select end date");
	return false;		
	}
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>
<?php
echo $this->Html->script('charts/highcharts.js');
echo $this->Html->css('dhtmlgoodies_calendar');
echo $this->Html->script('dhtmlgoodies_calendarnew.js');
   $graphTitle = 'Blank';
		for($i=1;$i<=31;$i++)
		{
			$cat[]=$i;	
		}
		$graphTitle='# Of SMS(IN/Out) For Each Day Of The Month';
$cat_List = json_encode($cat);	
?>
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> <?php __('Reports');?>
			<small></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span><?php __('Reports');?></span>
				</li>
			</ul>
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</button>
	<ul class="dropdown-menu pull-right" role="menu">				
<li>
    <a href="<?php echo SITE_URL;?>/users/reports" title="SMS Logs"><i class="fa fa-file-text-o"></i> SMS Logs</a>
</li>
<li>
    <a href="<?php echo SITE_URL;?>/users/subscribers" title="Subscribers"><i class="fa fa-user"></i> Subscribers</a>
</li>
<li>
    <a href="<?php echo SITE_URL;?>/users/unsubscribers" title="Un-subscribers"><i class="fa fa-user-times"></i> Un-subscribers</a>
</li>
<li>
    <a href="<?php echo SITE_URL;?>/users/keywords" title="Keywords"><i class="fa fa-key"></i> Keywords</a>
</li>

</ul>
<!--<ul class="dropdown-menu pull-right" role="menu">
						<?php $navigation = array(
							'SMS Logs' => '/users/reports',
							'Subscribers' => '/users/subscribers',
							'Un-subscribers' => '/users/unsubscribers',
							'Keywords' => '/users/keywords',
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
					</ul>-->
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
				<?php echo $this->Session->flash(); ?>
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-bar-chart font-red-sunglo"></i>
					<span class="caption-subject bold uppercase">  <?php __('Reports');?></span>
				</div>
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
			</div>
			<div class="portlet-body form">
				<?php echo $this->Form->create('User',array('action'=> 'reports','method'=>'post','onSubmit' => 'return confirmfrmSubmit();'));?>	
					<div class="form-body">
						<div class="form-group">
							<label> Select Date </label><input type="textbox" class="form-control input-medium" id="UserDate"  maxlength="10" name="data[User][start]" readonly onclick="displayCalendar(UserDate,'mm/dd/yyyy',this)" style="width:100px;" />
						</div>
						<div class="form-group">
							<label>To date</label>
							<input type="textbox" class="form-control input-medium" id="UserDateend"  maxlength="10" name="data[User][end]" readonly onclick="displayCalendar(UserDateend,'mm/dd/yyyy',this)" style="width:100px;" />
						</div>
					</div>
					<div class="form-actions">
						<?php echo $this->Form->submit('Search', array('class' => 'btn blue','div'=>false)); ?>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>  
				<p style="color:red"><?php if($start){ ?>
				
				SMS logs from <?php echo $start; ?>&nbsp; to &nbsp; <?php echo $end; ?>
				
				<?php } ?>	</p>	
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-file-text-o"></i>SMS Logs </div>
				<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
					<table class="table table-striped table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th>Group Name</th>
								<th>Message</th>
								<?php if(API_TYPE==0){?>
								<th>Images</th>
								<?php } ?>
								<th>In/Out</th>
								<th>Phone</th>
								<th>Created</th>
							</tr>
								<?php 
								$i = 0;
								foreach($Messege as $Messeges) { 
								$class = null;
								if ($i++ % 2 == 0) {
								$class = ' class="altrow"';
								}
								?>
						</thead>
						<!--<tbody>-->
							<tr <?php echo $class;?>> 
								<td><?php if(isset($Messeges['groups']['group_name'])){
								echo $Messeges['groups']['group_name'];} ?></td>
								<td><div ><?php echo $Messeges['logs']['text_message'] ?></div></td>
								<?php if(API_TYPE==0){?>
								<td>
									<?php if(!empty($Messeges['logs']['image_url'])){
									$check=strpos($Messeges['logs']['image_url'],":");
									if($check!=''){
									$comma=strpos($Messeges['logs']['image_url'],",");
									if($comma!=''){
									$image_arr=explode(",",$Messeges['logs']['image_url']);
									foreach($image_arr as $value){	
									?>
									<img src="<?php echo $value; ?>" height="50px" width="50px" />
									<?php
									}
									}else{ ?>
									<img src="<?php echo $Messeges['logs']['image_url'] ?>" height="50px" width="50px" />
									<?php
									}
									}	?>	
								</td>
								<?php }} ?>
								<?php if($Messeges['logs']['route']=='inbox'){ ?>
								<td>In</td>
								<?php   }else{ ?>
								<td>Out</td>
								<?php   } ?>
								<td><?php echo $Messeges['logs']['phone_number'] ?></td>
								<td><?php echo $Messeges['logs']['created'] ?></td>
							</tr>
							<?php } ?>
						<!--</tbody>-->
					</table>
					<?php echo $strPagination; ?> 
				</div>
			</div>
		</div>								
		<div id="container">
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
					subtitle:{
						text: ''
					},
					xAxis:{
						//categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
						categories: <?php echo $cat_List;?>
					},
					yAxis: {
						min: 0,
						title: {
							text: 'No of sms usage'
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
						this.x +': '+ this.y +' sms';
						}
					},
					plotOptions: {
						column: {
						pointPadding: 0.2,
						borderWidth: 0
						}
					},
					series: [{
						name: 'SMS Usage',
						data:  <?php echo $caller_list;?>
					}]
				});	
			}
				 window.onload=callFirst();
                                 $(window).resize();
		</script>
	</div>
</div>         