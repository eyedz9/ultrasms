<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<?php if($this->Session->check('User')):?>
		<li><?php echo $this->Html->link(__('My Profile', true), array('controller' =>'users', 'action' => 'profile')); ?></li>
		<li><?php echo $this->Html->link(__('Edit Profile', true), array('controller' =>'users', 'action' => 'edit')); ?></li>
		<li><?php echo $this->Html->link(__('Change Password', true), array('controller' =>'users', 'action' => 'change_password')); ?></li>
		<li><?php echo $this->Html->link(__('List Contacts', true), array('controller' => 'contacts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contact', true), array('controller' => 'contacts', 'action' => 'add')); ?> </li>
		
		<li><?php echo $this->Html->link(__('List Referrals', true), array('controller' => 'referrals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('My Affiliate Page', true), array('controller' => 'users', 'action' => 'affiliates')); ?> </li>
		<li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?> </li>
		<?php else:?>
		<li><?php echo $this->Html->link(__('Login', true), array('controller' =>'users', 'action' => 'login'));?></li>
		<li><?php echo $this->Html->link(__('Register', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<?php endif;?>
	</ul>
</div>