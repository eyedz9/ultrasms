<style>
td.actions {
    text-align: left;
    white-space: nowrap;
}

</style>
<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List User' => '/admin/users/index',
					  	'Add User' => '/admin/users/add',
						
					   					   
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
</ul>
<?php echo $this->Form->create('users',array('action'=> 'index'));?>
<div class="users index" style="width: 100%">

<fieldset style="margin-top:0px; padding: 20px 0 0 10px"><legend>Search Parameters</legend>
	 <table style="background-color: #fff;border-bottom: 0px solid #ccc;border-left: 0px solid #ccc;border-top: 0px solid #ccc;border-right: 0px solid #ccc;clear: both; color: #333; "><tr><td style="border-right: 0px solid #ccc; font-size: 14px; font-weight: bold;width:90px">
	 	Search By</td><td style="border-right: 0px solid #ccc;width:100px">
			


<?php
	$Option=array('6'=>'Select parameter','1'=>'User Name','2'=>'First Name','3'=>'Last Name','4'=>'Email Address','5'=>'Phone Number','7'=>'IP Address');
	
    echo $this->Form->input('Users.phone', array(
    'style'=>'width:250px;',
    'label'=>false,
    'default'=>6,
    
    'type'=>'select',
   
    
    
    'options' => $Option
	
	
	));
?>
			</td>
			
	</td>
	
	<td style="border-right: 0px solid #ccc;">
			


<!--<?php
	$Option3=array('0'=>'Twilio','1'=>'Nexmo','2'=>'Slooce','3'=>'Plivo','4'=>'Select Api Type');
    echo $this->Form->input('Users.api_type', array(
    'style'=>'width:250px;',
    'label'=>false,
    'default'=>4,
    
    'type'=>'select',
   
    'options' => $Option3
	
	
	));
?>-->
			</td>	
			

		
		<td style="border-right: 0px solid #ccc; font-size: 14px; font-weight: bold; width:90px">
		Search For</td><td style="border-right: 0px solid #ccc; width:100px">
<?php echo $this->Form->input('users.name', array('style'=>'width:250px;','label'=>false,'div'=>false,'id'=>'namephone')); ?></td>
	<td style="border-right: 0px solid #ccc;"><div class="submit" style="margin-top:-3px"><?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'inputbutton'));?></div> 	
			
</td>
</tr>
</table>
</fieldset>

			


	<h2 style="padding-top: 0px"><?php __('Users');?></h2>
	<table cellpadding="0" cellspacing="0" style="width: 3000px">
	<tr>
	                <th><?php echo __('IP Address');?></th>
			<th><?php echo __('Username');?></th>
			<th><?php echo __('FirstName');?></th>
			<th><?php echo __('LastName');?></th>
			<th><?php echo __('Email');?></th>
			<th><?php echo __('Personal Phone #');?></th>
			<th><?php echo __('Company Name');?></th>
			<th><?php echo __('Paypal Email');?></th>
			<th><?php echo __('Timezone');?></th>
                        <th><?php echo __('Country');?></th>
<th><?php echo __('Plan Name');?></th>
			<th><?php echo __('Next Renewal Date');?></th>
			<th><?php echo __('Sms Balance');?></th>
			<?php if(API_TYPE !=2){ ?>
			<th><?php echo __('Voice Balance');?></th>
			<th><?php echo __('Virtual Phone #');?></th>
			<?php }else{?>	
			<th><?php echo __('Short Code');?></th>
			<?php }?>
			<th><?php echo __('Number Limit');?></th>
			
			<th><?php echo __('Created On');?></th>
			<th><?php echo __('Status');?></th>
			<th class="actions"><?php __('Actions');?></th>
			
	</tr>
	<?php
	$i = 0;
	foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}

	?>
	<tr<?php echo $class;?>>
                <td><?php echo $user['User']['IP_address']; ?>&nbsp;</td>
		<td><?php echo $user['User']['username']; ?>&nbsp;</td>
		<td><?php echo $user['User']['first_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['last_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['email']; ?>&nbsp;</td>
		<td><?php echo $user['User']['phone']; ?>&nbsp;</td>
		<td><?php echo $user['User']['company_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['paypal_email']; ?>&nbsp;</td>
		<td><?php echo $user['User']['timezone']; ?>&nbsp;</td>
                <td><?php echo $user['User']['user_country']; ?>&nbsp;</td>
<td><?php echo $user['MonthlyPackage']['package_name'];?>&nbsp;</td>
		 
		 <td><?php 
		if($user['User']['next_renewal_dates']>0){
		
		echo $user['User']['next_renewal_dates'];
		}
		
		 ?>&nbsp;</td>
			<td><?php echo $user['User']['sms_balance']; ?>&nbsp;</td>
			<?php if(API_TYPE !=2){ ?>
			<td><?php echo $user['User']['voice_balance']; ?>&nbsp;</td>
			<?php }?>
		<td><?php echo $user['User']['assigned_number']; ?>&nbsp; <?php 
		//if($user['User']['assigned_number']!=0){ echo $this->Html->link(__('Release Number', true), array('action' => 'release_number', $user['User']['id']));  }?></td>
		
		

		<td><?php echo $user['User']['number_limit']; ?>&nbsp;</td>
		<td><?php echo $user['User']['created']; ?>&nbsp;</td>
		<td><?php echo $user['User']['active'] == 1?"Active":"Inactive"; ?>&nbsp;</td>
		<td class="actions">


			<?php 
			if ($user['User']['active'] == 0) {
				echo $this->Html->link(__('Resend Email', true), array('action' => 'resend_email', $user['User']['id'])); 
			}
			?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $user['User']['id'],base64_encode($user['User']['password']))); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', base64_encode($user['User']['id']),base64_encode($user['User']['password'])), null, sprintf(__('Are you sure you want to delete? It is recommended that you first release all numbers associated to this account before deleting the user.', true))); ?>

			<?php echo $this->Html->link(__('View Contacts', true), array('controller' =>'users', 'action' => 'usercontacts',base64_encode($user['User']['id']),base64_encode($user['User']['password'])), array('class' => 'forgetpass nyroModal'));?>		
			<?php 
			if(API_TYPE !=2){
			if($user['User']['assigned_number'] > 0){
			
			echo $this->Html->link(__('View All Numbers', true), array('controller' =>'users', 'action' => 'allnumbers',base64_encode($user['User']['id']),base64_encode($user['User']['password'])), array('class' => 'forgetpass nyroModal'));
			
			
			//  echo $this->Html->link(__('Release Number', true), array('action' => 'number_release', $user['User']['id']), null, sprintf(__('Are you sure you want to Release all the numbers of this user?', true))); 
				
				
				} }?>
			<!--<a href="<?php echo SITE_URL;?>/users/autologin/<?php echo $user['User']['id'];?>" target="_blank" class="forgetpass" onclick="return confirm('Reminder: You must log-out of other user accounts you are currently logged into before logging into this one. Are you logged out of all other accounts?');">Login</a>-->
<a href="<?php echo SITE_URL;?>/users/autologin/<?php echo base64_encode($user['User']['id']);?>/<?php echo base64_encode($user['User']['password']);?>" target="_blank" class="forgetpass" onclick="return confirm('Reminder: You must log-out of other user accounts you are currently logged into before logging into this one. Are you logged out of all other accounts?');">Login</a>

<?php echo $this->Html->link(__('Permissions', true), array('action' => 'userpermissions', base64_encode($user['User']['id']),0,base64_encode($user['User']['password'])),array('class' => 'forgetpass nyroModal')); ?>
			
	
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
