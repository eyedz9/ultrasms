<style>
.ValidationErrors{
color:red;
}
</style>
﻿<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Change Password</h3>
		<div class="page-bar">
		  <ul class="page-breadcrumb">
			<li> <i class="icon-home"></i> <a href="<?php echo SITE_URL; ?>/users/dashboard">Home</a> <i class="fa fa-angle-right"></i> </li>
			<li> <span>Change Password</span> </li>
		  </ul>
		</div>
		<div class="row">
<div class="col-md-12 "> 
				<!-- BEGIN SAMPLE FORM PORTLET-->
<?php  echo $this->Session->flash(); ?>	
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-key font-red-sunglo"></i>
							<span class="caption-subject bold uppercase">Change Password</span>
						</div>
					</div>
					<div class="portlet-body form">
						<?php echo $this->Form->create('User',array('action'=> 'change_password','id'=>'changePasswordForm','role'=>'form'));?>
							<div class="form-body">
								<div class="form-group">
									<label for="exampleInputPassword1">Old Password</label>
									<div class="input">
										<?php echo $this->Form->input('User.old_password',array('type' =>'password','div'=>false,'label'=>false, 'class' => 'form-control'))?>
										<!--<span class="input-group-addon">
											<i class="fa fa-user font-red"></i>
										</span>-->
									</div>
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">New Password</label>
									<div class="input">
										<?php echo $this->Form->input('User.new_password',array('type' =>'password','div'=>false,'label'=>false, 'class' => 'form-control'))?>
										<!--<span class="input-group-addon">
											<i class="fa fa-user font-red"></i>
										</span>-->
									</div>
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">Confirm New Password</label>
									<div class="input">
										<?php echo $this->Form->input('User.confirm_password',array('type' =>'password','div'=>false,'label'=>false, 'class' => 'form-control'))?>
										<!--<span class="input-group-addon">
											<i class="fa fa-user font-red"></i>
										</span>-->
									</div>
								</div>
							</div>
							<div class="form-actions">
								<button class="btn blue" type="submit">Change</button>
								<a href="<?php echo SITE_URL;?>/users/dashboard"><button class="btn default" type="button">Cancel</button></a>
							</div>
						<?php
							echo $this->Form->end();
							echo $this->Validation->rules(array('User'),array('formId'=>'changePasswordForm','validationBlock' =>'ChangePassword'));
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>