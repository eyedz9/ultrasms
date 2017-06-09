<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>

<?php
//echo $this->Html->script('jquery.js');
echo $this->Html->script('charts/highcharts.js');
echo $this->Html->css('dhtmlgoodies_calendar');
echo $this->Html->script('dhtmlgoodies_calendar');

$graphTitle = 'Blank';
	//echo $number_list.'fff';
	
	if($graph_typ==1)
	{
		for($i=1;$i<=24;$i++)
		{
			$cat[]=$i;
			
		}
		$graphTitle='Graph of SMS logs sent by user';
	}else{
		for($i=1;$i<=31;$i++)
		{
			$cat[]=$i;
			
		}
		$graphTitle='Graph of SMS usage into each number for a month';
		}
$cat_List = json_encode($cat);	

?>
<div class="adminUsers index" style=" float: left;
    width: 179px;">
<h2><?php __('Menu');?></h2>

<table cellpadding="0" cellspacing="0" style="width:200px;background: none repeat scroll 0 0 #fbfbfb;border: 1px solid #f1f1f1;border-radius: 0;box-shadow: 0 0 rgba(0, 0, 0, 0.1), 0 0 0 1px #ffffff inset; margin-bottom: 20px; padding: 10px;">
<tr >
	<td  style="text-align:left; padding-left:10px;border-top: 1px solid #ededed;">
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

.pagination {
    background: url("img/pagination.gif") repeat-x scroll 0 0 #FFFFFF;
    border-top: 1px solid #999999;
    color: #333333 !important;
    text-align: center;
}
.pagination ul {
    padding: 12px 10px 6px;
    position: relative;
    top: -1px;
	list-style:none;
}
.pagination ul li {
    display: inline;
}
</style>


		<div style="float:right; margin-right: 858px;
		
		
    margin-top: 38px;">
		<?php

  $usr_id =$this->Session->read('user_session_id');
  $date =$this->Session->read('date');


		echo $this->Form->create('Users',array('controller'=>'users','action'=>'user_messages'));?>	

			
			
				<div id="day1" >
		
				<label> Select Date </label><input type="textbox" class="inputtext" id="UserDate"  maxlength="10" name="data[User][date]" value="<?php if($date!='') { echo $date; } ?>" readonly onclick="displayCalendar(UserDate,'mm/dd/yyyy',this)" style="width:200px;" />
			</div>
			
			
		
		<label>Select Username <span class="required_star">*</span></label>
		
<select id="KeywordId"  style="width:200px; "  name="data[User][id]" >
                    

 <option value="0" selected> Select Username</option>
   <?php
                       foreach( $users as $user){
					   $ids=array();
					   $ids[]=$user['User']['id'];
                       if($user['User']['id']!='')
                       {
                       
                       ?>
					  
   <option <?php if(in_array($usr_id, $ids)){?>selected <?php }?>value="<?php echo $user['User']['id']; ?>"><?php echo ucwords($user['User']['username']); ?></option>
   <?php }} ?>
                       
</select>


		
				<?php 
				
				 //$users[0]='Please select';
				//echo $usr_id =$this->Session->read('user_id');
				
	//	echo $this->Form->input('User.id', array('div'=>false,'label'=>false, 'class' => 'inputtext','options'=>$users,'default'=> 0, if (in_array($usr_id, $users){ 'selected'=>'selected' });?>
	<br/>
		<div class="sep">	
		<?php echo $this->Form->submit('Find', array('class' => 'button',)); ?>
		
		</div>
        
        </div>
	
		
		
		<?php if(count($Messege)>0) { ?>
		
		<!--<div class="box" style="width:80%; margin:0px auto;float: inherit;">-->
		
		<table id="tableOne" cellspacing="0" cellpadding="0"style="width:98%; border:1px solid #ccc;" >
		<thead>
		<tr>
		<th class="tc">Group Name</th>
		<!--th class="tc">Credits</th-->
		<th class="tc">Message</th>
			 <th class="tc">Phone</th>
			<th class="tc">Created</th>
				</tr>
				
				</thead>
				
		<?php 
		
		//pr($Messege);
		$i = 0;
		foreach($Messege as $Messeges) { 
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		?>
			 <tbody>		
	<tr class="first">
	<tr <?php echo $class;?>> 
			  <td class="tc"><?php if(isset($Messeges['Group']['group_name'])){
			  echo $Messeges['Group']['group_name'];} ?></td>
			  <!--td class="tc"><?php //echo $Messeges['Log']['credit_used'] ?></td-->
			  <td class="tc"><?php echo $Messeges['Log']['text_message'] ?></td>
			  <td class="tc"><?php echo $Messeges['Log']['phone_number'] ?></td>
			  <td class="tc"><?php echo $Messeges['Log']['created'] ?></td>
			  </tr>
			  <?php } ?>
			  
			  </tbody>
</table>

			<?php //echo $strPagination; ?>  
			 <!-- </div>-->
		<div class="pagination">
 <ul>
  <li><?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?></li>
  | <li><?php echo $this->Paginator->numbers();?></li>
 |
  <li><?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?></li>
  </ul>
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


