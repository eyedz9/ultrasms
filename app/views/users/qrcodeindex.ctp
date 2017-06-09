<style>
.loginbox a.forgetpass {
    background: none repeat scroll 0 0 #3d794e;
    border-radius: 10px 10px 10px 10px;
    color: #FFFFFF;
    float: left;
    font-size: 11px;
    font-weight: normal;
    margin: 0 5px 0 0;
    padding: 2px 10px;
}
</style>

<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> QR Codes
			<small></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>QR Codes</span>
				</li>
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-qrcode font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> QR Codes</span>
				</div>
			</div>
			<div class="portlet-body form">
					<iframe src="<?php echo SITE_URL ?>/qrcdr/" height="650" width="100%" style="margin-left:-5px;margin-top:5px;border:0"></iframe>
			</div>
		</div> 
    </div> 
</div>                         