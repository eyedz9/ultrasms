<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Thank You!
			<small></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<!--<i class="fa fa-angle-right"></i>-->
				</li>
				<!--<li>
					<span>Account Updated</span>
				</li>-->
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet light">
			<div class="portlet-body">
				<p>
				<font style="font-size: 18px">Thanks for your purchase! Your account was updated successfully. Please visit your <?php echo $this->Html->link('Dashboard', array('controller' =>'users', 'action' => 'profile'));?>
				</font>
				</p>
				<br/>
							<!--<font style="color: red"><b>NOTE: it may take a little time for the credits to be reflected in your account. This is due to the Paypal IPN process which sometimes can be delayed a bit.</b></font>-->

<div class="note note-warning"><b>NOTE:</b> It may take a little time for your account to be updated. This is due to the Paypal IPN process which sometimes can be delayed a bit.</div>
			</div>
		</div>
	</div>
</div>