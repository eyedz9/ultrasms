<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
		<?php __('UltraSMSScript.com ::'); ?>
		<?php echo $title_for_layout; ?>
</title>
<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('style');
	echo $this->Html->css('nyroModal');
	echo $this->Html->script('jquery');
	echo $this->Html->script('validation');
	echo $this->Html->script('jQvalidations/jquery.validation.functions');
	echo $this->Html->script('jQvalidations/jquery.validate');

	echo $this->Html->script('jquery.nyroModal.custom');
	echo $this->Html->script('jquery.cycle.all');
	echo $this->Html->css('cycle');
	echo $this->Html->script('twiliofunctions');

	echo $scripts_for_layout;
	?>
</head>
	<body id="inner">
	<div id="support">Help & Support - <a href="mailto:support@smssupportservices.com">Get in-touch!</a></div>
		<!-- Wrapper begin-->
		<div class="wrapper" id="top">
			<!-- Header begin-->
			<div id="header">
				<?php echo $this->Html->link($this->Html->image('logo.png'), '/', array('escape' =>false, 'class' =>'logo'));?>
				<?php
				$homeCss = '';
				$loginCss = '';
				$aboutCss ='';
				$myaccountCss = '';
				if($this->params['controller'] == 'users' && $this->params['action'] == 'home'){
					$homeCss = 'active';
				}
				if($this->params['controller'] == 'users' && $this->params['action'] == 'login'){
					$loginCss = 'active';
				}
				if($this->params['controller'] == 'users' && $this->params['action'] == 'about'){
					$aboutCss = 'active';
				}
				else{
					$myaccountCss = 'active';
				}
				?>
				<!--nav-->
				<ul class="nav">
					<li class="<?php echo $homeCss?>"><?php echo $this->Html->link('Home','/')?></li>
					<li class="<?php echo $aboutCss?>"><?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'))?></li>
					<?php if(!$this->Session->check('User')):?>
					<li class="<?php echo $loginCss?>"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?></li>
					<?php else:?>
					<li  class="<?php echo $myaccountCss?>"><?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?></li>
					<li><?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?></li>
					<?php endif;?>
				</ul>
				<!--nav-->
			</div>
			<!-- Header end-->
        </div>
        <!-- Wrapper end-->
		
		
        <!-- Wrapper begin-->
        <div class="wrapper">
			<div id="content">
				<!--left column-->
                <div class="leftcolumn">
				<?php echo $this->Session->flash(); ?>
				<?php //echo $this->Session->flash('email'); ?>
				<?php echo $content_for_layout; ?>
				</div>
				<!--left column-->
				<!-- right column-->
                <div class="rightcolumn"><?php echo $this->element('right_part')?></div>
				<div class="clear"></div>
				<!-- right column-->
				<!--Testimonials area-->
				<div class="testimonials">
					<?php echo $this->element('testimonial_part')?>
				</div>
				<!--Testimonials area-->
			</div>
		</div>
		<!-- Wrapper end-->
		
        <!--footer area-->
        <div id="footer">
         <!-- Wrapper begin-->
            <div class="wrapper">
                            <div class="areaone">
                                <h6>Contact Us</h6>
                                <p>Questions, concerns or require support? Please <a href="mailto:youremailaddress">Click Here</a> to contact us. We'll get right back to you. </p>
                            </div>
                            <div class="areatwo">
                                <h6>Privacy</h6>
                                <p>Your personal information, message data and associated correspondence is completely confidential, secure and private. We do not share customer information with third parties.</p>

                            </div>
                            <div class="areathree">
                                <h6>connect with us</h6>
                               <div class="social">
                                    <a href="#" class="facebook">Facebook</a>
                                    <a href="#" class="twitter">twitter</a>
                                    <a href="#" class="linkedin">linkedin</a>
                                </div>
                                </div>
                            <div class="clear"></div>
                            <div class="bottomarea">
                                 <div class="alignleft"><a href="<?php echo $this->Html->url('/')?>">Home</a>  |  <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'about'))?>">About</a>  </div>
                                <div class="alignright">&copy; 2012 UltraSMSScript.com :: All Rights Reserved</div>
                            </div>
            </div>
            <!-- Wrapper end-->
			<?php echo $this->element('sql_dump')?>
        </div>
        <!--footer area-->
	</body>
</html>

<script type="text/javascript">
$().ready(function() {
	$('a.nyroModal').nyroModal();
	
});
</script>
