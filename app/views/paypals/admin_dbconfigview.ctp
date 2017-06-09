<style>
dd {
    margin-left: 14em!important;
    margin-top: -2em;
    vertical-align: top;
}
</style>
<div class="configs view">
<h2><?php  __('DbConfig');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $dbconfig['Dbconfig']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('DbUserName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $dbconfig['Dbconfig']['dbusername']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('DbName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $dbconfig['Dbconfig']['dbname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('DbPassword'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $dbconfig['Dbconfig']['dbpassword']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $dbconfig['Dbconfig']['created']; ?>
			&nbsp;
		</dd>
		 
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit DbConfig', true), array('action' => 'dbconfigedit', $dbconfig['Dbconfig']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete DbConfig', true), array('action' => 'dbconfigdelete', $dbconfig['Dbconfig']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $dbconfig['Dbconfig']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Dbconfig', true), array('action' => 'dbconfig')); ?> </li>
		<?php if($dbconfig['Dbconfig']['id']=''){ ?>
		<li><?php echo $this->Html->link(__('New Dbconfig', true), array('action' => 'dbconfigadd')); ?> </li>
		<?php } ?>
	</ul>
</div>
