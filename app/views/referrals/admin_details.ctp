<div class="users index">
	<h2><?php __('List of Users Referred By ');?>&nbsp; <?php echo $referrals['0']['RefferedBy']['first_name'] ." ". $referrals['0']['RefferedBy']['first_name']?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo __('FirstName');?></th>
			<th><?php echo __('LastName');?></th>
			<th><?php echo __('Email');?></th>
			<th><?php echo __('Amount');?></th>
			
	</tr>
	<?php
	$i = 0;
	$total = 0;
	foreach ($referrals as $referral):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		$total += $referral['Referral']['amount'];
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $referral['User']['first_name']; ?>&nbsp;</td>
		<td><?php echo $referral['User']['last_name']; ?>&nbsp;</td>
		<td><?php echo $referral['User']['email']; ?>&nbsp;</td>
		<td>$<?php echo number_format($referral['Referral']['amount'], 2); ?>&nbsp;</td>
		
		
		
	</tr>
	
	<?php endforeach; ?>
	<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>Total</th>
			<th>$<?php echo number_format($total, 2); ?>&nbsp;</th>
			
	</tr>
	</table>
	
</div>
