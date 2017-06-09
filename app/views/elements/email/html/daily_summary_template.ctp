<p>Hello <?php echo $username; ?>,</p>

<p>
Here is the daily summary of your new subscribers.
</p><br/>

<?php 
$i = 1;
foreach($emailalerts as $emailalert) { ?>

<p>
<?php

if(!isset($group[$emailalert['Group']['id']])){
 

?>
<b>Group Name:</b>   <font color=green><b><?php echo $emailalert['Group']['group_name'];?></b></font><br/>
<b>Keyword:</b>          <font color=green><b><?php echo $emailalert['Group']['keyword'];?></b></font><br/>
<b># of Subscribers:</b>  <font color=green><b><?php echo $Subscribercounts[$emailalert['ContactGroup']['group_id']]; ?></b></font><br/><br/>
<?php }else{
$i++;
} ?>
<!--<div style="width: 500px;">-->
<table style="width: 500px">
<?php if(!isset($group[$emailalert['Group']['id']])){ ?>
		<thead>
		<tr>
		<th style="text-align: left">Mobile Number</th>
		<th style="text-align: left">Subscribed Date</th>
		</tr>
		</thead>
		<!--<div style="float: left;font-weight:bold;">Mobile Number</div>
		<div style="text-align: left;font-weight:bold;">Subscribed Date</div>-->
		
				<?php } ?>
				
			<tbody><tr><td style="text-align: left"><?php 
			  
			  echo $emailalert['Contact']['phone_number'] ?></td>
			  <td style="text-align: left"><?php echo $emailalert['ContactGroup']['created'] ?></td></tr></tbody>
			  <!--<div style="float: left;"><?php 
			  
			  echo $emailalert['Contact']['phone_number'] ?></div>
			  <div style="text-align:left;"><?php echo $emailalert['ContactGroup']['created'] ?></div>
			
		</div>-->
			</table>
				<?php if($Subscribercounts[$emailalert['Group']['id']] == $i){ 
				$i = 1;
				?>
				<hr/>
				
				<?php } ?>
				
				</p>

 <?php 

 $group[$emailalert['Group']['id']] = $emailalert['Group']['group_name'];
 } ?>

<p>
Go to <?php echo SITE_URL ?> to see all the details. 
</p>
<p>
Regards and happy marketing!
</p>


 