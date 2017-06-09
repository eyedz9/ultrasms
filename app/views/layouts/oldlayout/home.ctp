<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
		<?php __('UltraSMSScript.com ::'); ?>
		<?php echo $title_for_layout; ?>
</title>
<?
echo $this->Html->css('style');
//echo $this->Html->css('nyroModal');
echo $this->Html->css('cycle');
echo $this->Html->script('jquery');
echo $this->Html->script('jquery.cycle.all');
?>
</head>
	<body>
		<!-- Wrapper begin-->
	<div id="support">Help & Support - <a href="mailto:support@ultrasmsscript.com">Get in-touch!</a></div>
		<div class="wrapper" id="top">
			<!-- Header begin-->
			<div id="header">
				<?php echo $this->Html->link($this->Html->image('logo.png'), '/', array('escape' =>false, 'class' =>'logo'));?>
				<?php
				$homeCss = '';
				$loginCss = '';
				$aboutCss ='';
				if($this->params['controller'] == 'users' && $this->params['action'] == 'home'){
					$homeCss = 'active';
				}
				if($this->params['controller'] == 'users' && $this->params['action'] == 'login'){
					$loginCss = 'active';
				}
				if($this->params['controller'] == 'users' && $this->params['action'] == 'about'){
					$aboutCss = 'active';
				}
				?>
				<!--nav-->
				<ul class="nav">
					<li class="<?php echo $homeCss?>"><?php echo $this->Html->link('Home','/')?></li>
					<li class="<?php echo $aboutCss?>"><?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'))?></li>
					<?php if(!$this->Session->check('User')):?>
					<li class="<?php echo $loginCss?>"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?></li>
					<?php else:?>
					<li><?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?></li>
					<li><?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?></li>
					<?php endif;?>
				</ul>
				<!--nav-->
			</div>
			<!-- Header end-->
        </div>
        <!-- Wrapper end-->
		
        <!-- banner-outter begin-->
        <div class="banner-outter">
			<!--Banner-->
			<div id="bannerarea" class="wrapper">
				<div class="bannertext">
				 Affiliate Scheme: Make money from your membership!<br />
				SPECIAL OFFER: 50 <B>FREE</B> SMS credits upon account activation - Register Now! <br />
				<?php echo $this->Html->link($this->Html->image('give-a-try.png'), array('controller' =>'users', 'action' => 'add'), array('escape' =>false));?>
				</div>
			</div>
			<!--Banner-->
		</div>
        <!-- banner-outter end-->
		
        <!-- Wrapper begin-->
        <div class="wrapper">
			<div id="content">
				<!--Features area-->
				<div class="features-bg">
					<div class="features">
						<ul>
							<li class="icon-1">
								<h6>Instant Access</h6>
								<p>Our system is completely automated. Instant registation, Instant number selection and Instant account credits. Done!</p>
								
							</li>
							<li class="icon-4">
								<h6>Backups & Security</h6>
								<p>We regularly backup our system data as well as keeping up to date on security risks and procedures. You're in good hands.</p>
								
							</li>
							<li class="icon-2">
								<h6>Online Support</h6>
								<p>Customer safisfaction is our top priority. Should you require support, be advised we are available 24/7 to assist you. </p>
								
							</li>
							<li class="icon-5">
								<h6>Voicemail</h6>
								<p>Receive voicemails directly to your account at no extra cost to you. We'll even notify you of any incoming messages!</p>
								
							</li>
							<li class="icon-3">
								<h6>Contacts</h6>
								<p>Manage your contacts in the members area. Collect a directory of personal contacts and keep in-touch with friends.</p>
								
							</li>
							<li class="icon-6">
								<h6>SMS</h6>
								<p>Send and Receive text messages with absolute ease. Send to any number and receive directly to your number.</p>
								
							</li>
							
						</ul>

					</div>
				</div>
				<!--Features area-->
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
                                <p>Questions, concerns or require support? Please <a href="mailto:youremailaddress">Click Here</a> to contact us. We'll get right back to you.  </p>
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
        </div>
        <!--footer area-->
	
	</body>
</html>
