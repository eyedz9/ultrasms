<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>

<?php
//echo $this->Html->script('jquery.js');
echo $this->Html->script('charts/highcharts.js');
echo $this->Html->css('dhtmlgoodies_calendar');
echo $this->Html->script('dhtmlgoodies_calendar');

?>
<div class="adminUsers index" style=" float: left;
    width: 179px;">
<h2><?php __('Menu List');?></h2>

<table cellpadding="0" cellspacing="0" style="width:200px;">
<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('SMS Logs','/admin/users/user_messages?all=show')?>
	</td>
</tr>


<tr>
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Top Users','/admin/users/top_users?all=show')?>
	</td>
</tr>
<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php echo $html->link('Non Users','/admin/users/non_users?all=show')?>
	</td>
</tr>
<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php// echo $html->link('Add Packages','/admin/packages/add')?>
	</td>
</tr>

<tr >
	<td  style="text-align:left; padding-left:10px;">
	<?php //echo $html->link('List Users','/admin/users/index')?>
	</td>
</tr>

</table>
</div>

<script>
var chart;
$(document).ready(function() {
chart = new Highcharts.Chart({
      chart: {
         renderTo: 'container_pie',
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: 'Phone Chart'
      },
      tooltip: {
         formatter: function() {		 
            return '<b>'+ this.point.name +'</b>: '+ this.point.y +' sms';
         }
      },
      plotOptions: {
         pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
               enabled: true,
               color:'#000000',
               connectorColor:'#000000',
               formatter: function() {
                  return '<b>'+ this.point.name +'</b>: '+ this.point.y +' sms';
               }
            }
         }
      },
       series: [{
         type: 'pie',
         name: 'Browser share',
         /* data: [
            ['+17378742833',   90.0],
            ['+18774129767',       10.0]
         ] */
         data:<?php echo $number_list?>
		 }]
   });
   
   });
   
 
</script>

<style>


select {
   
    width: 209px;
}
#container_pie {
      float: left;
      width: 95%;
    }
	label {
    display: block;
    font-size: 110%;
    padding-right: 20px;
}

input, img {
    margin-bottom: 0px;
    margin-top: 0;
}
th{
text-align: left;
}
table tr td{
text-align: left;
}

</style>
<div style="float:right; margin-right: 858px;
    margin-top: 38px;">
		<?php
  $todatetop =$this->Session->read('todatetop');
  $fromdatetop =$this->Session->read('fromdatetop');
		echo $this->Form->create('Users');?>	

			
			
				<div id="day1" >
		
				<label> Select Date </label>
				
				<label> From </label>
				<input value ="<?php if(!empty($todatetop)){ echo $todatetop;}?>" type="textbox" class="inputtext" id="UserDate1"  maxlength="10" name="data[date][from]" readonly onclick="displayCalendar(UserDate1,'mm/dd/yyyy',this)" style="width:200px;" /><label style="float:left;">To</label>
					<input type="textbox" value ="<?php if(!empty($fromdatetop)){ echo $fromdatetop;} ?>" class="inputtext" id="UserDate"  maxlength="10" name="data[date][to]" readonly onclick="displayCalendar(UserDate,'mm/dd/yyyy',this)" style="width:200px;" />
			</div>
			
	
		<div class="sep">	
		<?php echo $this->Form->submit('Find', array('class' => 'button',)); ?>
		
		</div>
        
        </div>


		<div style="margin-top: 114px;  width: auto;">

		
		
		
		<table id="tableOne" cellspacing="0" cellpadding="0"style="width:98%; border:1px solid #ccc;" >
					
				
					
						
						<?php
							//pr($calls_data);
						       if(!empty($top_users))
							   {
							   ?>
							   <thead>
						<tr>
							 <th class="tc">S No.</th>
							 <th class="tc">First Name</th>
							<th class="tc">Last Name</th>
							<th class="tc">Email</th>
							<th class="tc">Phone</th>
							<th class="tc">Total SMS</th>
						</tr>
					</thead>
					<tbody>
							  <?php 
		
		//pr($Messege);
		$i = 0;
		foreach($top_users as $top_user) { 
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		?>
		
						     
									
									<tr <?php echo $class;?>> 
										<td class="tc"><?php echo $i;?></td>
										<td class="tc"><?php echo $top_user['p']['first_name'];?></td>
										<td class="tc"><?php echo $top_user['p']['last_name'];?></td>
										<td class="tc"><?php echo $top_user['p']['email'];?></td>
										<td class="tc"><?php echo $top_user['p']['assigned_number'];?></td>
										<td class="tc"><?php echo $top_user['0']['count'];?></td>
														  
									</tr>
									
							<?php
								$i++;
								}
						}else
						{
						?>
						<tr>
						<td colspan="6">
						<center>No Record Found.</center>
						</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>

		
			  </div>
			
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	<div id="container">

		<div id="container_pie"></div>
	</div>
				
				
				
			
