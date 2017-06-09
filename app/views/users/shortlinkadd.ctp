<script>
            jQuery(function(){
			 jQuery("#Shortlinkurl").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a url"
                }); jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter name"
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
		<h3 class="page-title"> <?php __('Short URLs');?>
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
					<a href="<?php echo SITE_URL;?>/users/shortlinks">Short Links</a>
				</li>
			</ul>
			<!--<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<?php
							$navigation = array(
									'Back' => '/users/shortlinks',
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
			</div>-->
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet mt-element-ribbon light portlet-fit  ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create short url form
</div>
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-link font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="validationMessages" style="display:none"></div>
				<?php echo $this->Form->create('User',array('action'=> 'shortlinkadd','name'=>'loginForm','id'=>'loginForm'));?>
					<div class="form-body">
						<div class="form-group">
							<label>Name<span class="required_star"></span></label>
							<?php echo $this->Form->input('Shortlink.name',array('div'=>false,'label'=>false, 'class' => 'form-control input-medium','id'=>'name'))?>
						</div>
						<div class="form-group">
							<label>URL<span class="required_star"></span></label>
							<?php echo $this->Form->input('Shortlink.url',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'Shortlinkurl'))?>
							<label style="padding-top:3px;">Use only URL (Example: http://www.demo.com)</label>
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