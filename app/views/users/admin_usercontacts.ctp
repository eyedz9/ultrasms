
<!-- login box-->


<!--<div class="loginbox">-->
<div class="loginner" style="margin-top: 15px;width:700px">

	<div style="text-align:right;float:right; padding-bottom: 10px">
	<!--<a class="nyroModal" href="<?php echo SITE_URL;?>/contacts/add" title="Add Contact"><img src="<?php echo SITE_URL;?>/img/add_contact.png"></a>-->
	<?php if(!empty($contacts)){?>
	<a href="<?php echo SITE_URL;?>/contacts/export" title="Export Contacts"><img src="<?php echo SITE_URL;?>/img/export_excel.png"></a>
	<?php }?>
	</td></tr>
	</div>
	<?php if(empty($contacts)){?>
	<div style="font-weight: bold; font-size: 15px;text-align: center;">No contacts found for this user.</div>

	<?php  }else{ ?>
	<table cellpadding="0" cellspacing="0" width="100%">
	
	
	<tr>
	
	
	<th>Name</th>
		<?php if($users['User']['capture_email_name']==0){ ?>
		<th>Email</th>
		<?php } ?>
		<?php if($users['User']['birthday_wishes']==0){ ?>
		<th>Birthday</th>
 	        <?php } ?>
		
		
		<th>Number</th>
			<th>Group</th>
			<th>Sub</th>
			<th>Source</th>
	
	
	<th class="actions" style="text-align: center">Action</th>
	
	
	</tr>


	
	
	<?php 
	$i = 0;
	
	
	
	foreach ($contacts as $contact):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>


	
	
	<tr<?php echo $class;?>>
		

		
		<td style="text-align: left;"><?php echo $contact['Contact']['name']; ?>&nbsp;</td>
		<?php if($users['User']['capture_email_name']==0){ ?>
		<td style="text-align: left;"><?php echo $contact['Contact']['email']; ?>&nbsp;</td>
		<?php } ?>
		<?php if($users['User']['birthday_wishes']==0){ ?>
		<td style="text-align: left;"><?php echo $contact['Contact']['birthday']; ?>&nbsp;</td>
		<?php } ?>
		<td style="text-align: left;">
		<?php echo $contact['Contact']['phone_number']; ?>&nbsp;</td>
		<td style="text-align: left;"><?php echo $contact['Group']['group_name']; ?>&nbsp;</td>
		
		<?php if($contact['ContactGroup']['un_subscribers']==0){ ?>
		<td style="text-align: left;">
		Yes
		</td>
		<?php }else{ ?>
		<td style="text-align: left;">
	No
		</td>
		
		<?php } ?>
		
		<?php if($contact['ContactGroup']['subscribed_by_sms']==0){ ?>
		<td style="text-align: left;">Import</td>
		<?php }else if($contact['ContactGroup']['subscribed_by_sms']==1) { ?>
		<td style="text-align: left;">SMS</td>
		<?php }else { ?>
		<td style="text-align: left;">Widget</td>
		<?php } ?>
		
				
		<td class="tc" style="text-align: center;">
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'contactdelete', $contact['Contact']['id']), null, sprintf(__('Are you sure you want to delete this contact?',true))); ?>
		<!--<a href="<?php echo SITE_URL;?>/contacts/delete/<?php echo $contact['Contact']['id'];?>/1" title="Delete Contact">Delete</a>-->

		</td>

		
	</tr>
	
	
	

<?php endforeach; ?>

	
	</table>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array('class'=>'nyroModal'), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers(array('class'=>'nyroModal'));?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array('class'=>'nyroModal'), null, array('class' => 'disabled'));?>
	</div>
	<?php } ?>
</div>
</html>
<!--</div>-->
<!-- login box-->