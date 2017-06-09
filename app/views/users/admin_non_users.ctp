<style>
td.actions {
    text-align: left;
    white-space: nowrap;
}

</style>
<?php
//echo $this->Html->script('jquery.js');
//echo $this->Html->script('charts/highcharts.js');
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


<tr >
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
<style>


select {
   
    width: 209px;
}
#container_graph {
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
#calendarDiv{


left: 261px;}
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

  $todatenon =$this->Session->read('todatenon');
  $fromdatenon =$this->Session->read('fromdatenon');
  
		echo $this->Form->create('Users');?>	

			
			
				<div id="day1" >
		
				<label> Select Date </label>
				
				<label> From </label>
				<input value ="<?php if(!empty($todatenon)){ echo $todatenon;}?>" type="textbox" class="inputtext" id="UserDate1"  maxlength="10" name="data[date][from]" readonly onclick="displayCalendar(UserDate1,'mm/dd/yyyy',this)" style="width:200px;" /><label style="float:left;">To</label>
					<input value ="<?php if(!empty($fromdatenon)){ echo $fromdatenon;}?>" type="textbox" class="inputtext" id="UserDate"  maxlength="10" name="data[date][to]" readonly onclick="displayCalendar(UserDate,'mm/dd/yyyy',this)" style="width:200px;" />
			</div>
		
		<div class="sep">	
		<?php echo $this->Form->submit('Find', array('class' => 'button',)); ?>
		
		</div>
        
        </div>
	
		<?php if(count($non_users)>0) { ?>
		<div class="box" style="width:98%; margin:0px auto;float: left;">
		
		<table id="tableOne" cellspacing="0" cellpadding="0"style="width:100%; border:1px solid #ccc;" >
		<thead>
		<tr>
		<th class="tc">Username</th>
		<!--th class="tc">Credits</th-->
		<th class="tc">Email</th>
                <th class="tc">Personal Phone</th>
			 <th class="tc">Virtual #</th>
			<th class="tc">Created</th>
                         <th class="tc">Actions</th>
				</tr>
				
				</thead>
				
		<?php 
		
		//pr($Messege);
		$i = 0;
		foreach($non_users as $non_user) { 
		
		
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		?>
			 <tbody>		
	<tr class="first">
	<tr <?php echo $class;?>> 
			  <td class="tc"><?php echo $non_user['users']['username']; ?></td>
			  <!--td class="tc"><?php //echo $Messeges['Log']['credit_used'] ?></td-->
			  <td class="tc"><?php echo $non_user['users']['email'] ?></td>
                          <td class="tc"><?php echo $non_user['users']['phone'] ?></td>
			  <td class="tc"><?php echo $non_user['users']['assigned_number'] ?></td>
			  <td class="tc"><?php echo $non_user['users']['created'] ?></td>

                          <td class="tc">
                          <?php if(API_TYPE !=2){
			     if($non_user['users']['assigned_number'] > 0){                          
                                 echo $this->Html->link(__('View All Numbers', true), array('controller' =>'users', 'action' => 'allnumbers',$non_user['users']['id']), array('class' => 'forgetpass nyroModal'));
} }?>
			  </td>
			  </tr>
			  <?php } ?>
			  
			  </tbody>
</table>

			<?php //echo $strPagination; ?>  
			  </div>
			
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<div id="container">
					<div id="container_graph">
						<p></p>
					</div>
					<!--div id="container_pie"></div-->
				</div>
				<?php } ?>
			
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
 
/*setTimeout(
function(){
	callFirst();
},
5000
);*/
</script>


