<div class="users index">
	<h2><?php __('Current Referrals ');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo __('FirstName');?></th>
			<th><?php echo __('LastName');?></th>
			<th><?php echo __('Email');?></th>
			<th><?php echo __('Paypal Email');?></th>
			<th><?php echo __('Total Amount');?></th>
			<th><?php echo __('Status');?></th>
			<th><?php echo __('Action');?></th>
			
	</tr>
	<?php
	$i = 0;
	foreach ($referrals as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $user['RefferedBy']['first_name']; ?>&nbsp;</td>
		<td><?php echo $user['RefferedBy']['last_name']; ?>&nbsp;</td>
		<td><?php echo $user['RefferedBy']['email']; ?>&nbsp;</td>
		<td><?php echo $user['RefferedBy']['paypal_email']; ?>&nbsp;</td>
		<td>$<?php echo number_format($user['RefferedBy']['totalAmt'], 2); ?>&nbsp;</td>
		<td><?php echo $user['Referral']['paid_status']== 0?"Unpaid" : "Paid"; ?>&nbsp;</td>
		
		<td class="actions">
			<?php echo $this->Html->link(__('View Details', true), array('action' => 'details', $user['Referral']['referred_by']), array('class' => 'nyroModal')); ?>
			<?php
			if($user['Referral']['paid_status'] == 0){
				echo $this->Html->link(__('Mark as Paid', true), array('action' => 'mark', $user['Referral']['referred_by'],1), null, sprintf(__('Are you sure mark as Paid', true)));
			}else{
			echo $this->Html->link(__('Mark as UnPaid', true), array('action' => 'mark', $user['Referral']['referred_by'],0), null, sprintf(__('Are you sure to mark as Unpaid', true)));
			}
			?>
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
