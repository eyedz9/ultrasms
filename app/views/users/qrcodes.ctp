<?php 
echo $this->Html->script('jquery-1.7.1.js');
echo $this->Html->script('jQvalidations/jquery.validation.functions');
echo $this->Html->script('jQvalidations/jquery.validate');
 ?>
<script>
jQuery(function(){
	jQuery("#qrcode").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter a url"
	});
});			
</script>
<style>
.ValidationErrors{
color:red;
}
</style>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> <?php __('Add URL');?>
			<small> </small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span><?php __('Add URL');?></span>
				</li>
			</ul>
			 <div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                          	<?php
			$navigation = array(
					'Back' => '/users/qrcodeindex',
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
                            </div>
                        </div>
			
		</div>
		<div class="clearfix"></div>
	
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="icon-settings font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> QR Codes</span>
				</div>
			</div>
				<?php echo $this->Session->flash(); ?>
			<div class="portlet-body form">
				<div id="validationMessages" style="display:none"></div>
				<?php echo $this->Form->create('User',array('action'=> 'qrcodes','name'=>'loginForm','id'=>'loginForm'));?>
				<div class="form-body">
					<div class="form-group">
						<label>URL<span class="required_star">*</span></label>
						<?php echo $this->Form->input('Code.qrcode',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'qrcode'))?>
						<label style="padding-top:3px;">Use only URL (Example: www.demo.com)</label>
					</div>
				</div>
				<div class="form-actions">
				<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>                     
    </div>
</div>