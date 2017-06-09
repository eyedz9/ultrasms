<style>
dd {
    margin-left: 14em!important;
    margin-top: -2em;
    vertical-align: top;
}

</style>
<div class="configs view">
<h2><?php  __('Paypal');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypal['Paypal']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Paypal Api Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypal['Paypal']['paypal_api_username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Paypal Api Password'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypal['Paypal']['paypal_api_password']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Paypal Api Signature'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypal['Paypal']['paypal_api_signature']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paypal['Paypal']['created']; ?>
			&nbsp;
		</dd>
		 
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Paypal', true), array('action' => 'edit', $paypal['Paypal']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Paypal', true), array('action' => 'delete', $paypal['Paypal']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paypal['Paypal']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Paypal', true), array('action' => 'index')); ?> </li>
		<?php if($paypal['Paypal']['id']=''){ ?>
		<li><?php echo $this->Html->link(__('New Paypal', true), array('action' => 'add')); ?> </li>
		<?php } ?>
	</ul>
</div>
