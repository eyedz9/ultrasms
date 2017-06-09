	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> <?php __('Add Log'); ?></h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li><span><?php __('Add Log'); ?>  </span></li>
				</ul> 
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
						<li><?php echo $this->Html->link(__('List Logs', true), array('action' => 'index'));?></li>
						<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
						<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>						
						</ul>
					</div>
				</div>	
			</div>
			<?php  echo $this->Session->flash(); ?>		
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="icon-settings font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"> <?php __('Add Log'); ?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<?php echo $this->Form->create('Log');?>
					<div class="form-body">
						<div class="form-group">
							<label>User<span for="exampleInputPassword1"></span></label>
							<?php echo $this->Form->input('Log.user_id',array('div'=>false,'label'=>false, 'class' => 'form-control'))?>
						</div>
						<div class="form-group">										
							<div class="input-icon">
								<?php  echo $this->Form->input('Log.phone_number',array('div'=>false,'class'=>'form-control')); ?>
							</div>
						</div>
						<div class="form-group">										
							<?php  echo $this->Form->input('Log.text_message',array('div'=>false,'class'=>'form-control')); ?>
						</div>
						<div class="form-group">									
							<div class="input-icon">
								<?php  echo $this->Form->input('Log.voice_url',array('div'=>false,'class'=>'form-control')); ?>
							</div>
						</div>  
						<div class="form-group">
							<div class="input-icon">
								<?php  echo $this->Form->input('Log.route',array('div'=>false,'class'=>'form-control')); ?>
							</div>
						</div>											
					</div>
					<div class="form-actions">                                      
						<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn blue'));?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
