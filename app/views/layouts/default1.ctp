<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<title>
		<?php echo SITENAME ?> - 
		<?php echo $title_for_layout; ?>
</title>
<?php
	echo $this->Html->meta('icon');

		
	echo $this->Html->css('bootstrapwp');
	echo $this->Html->css('homestyle');
        echo $this->Html->css('style');
    	
	?>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<?php
	 
	echo $scripts_for_layout;
	?>
<style>
#flashMessage{
font-size:16px;
 font-weight: bold;


}
h3 {
    font-size: 21px;
    font-weight: bold;
    line-height: 150%;
    margin-top: 25px;
    color: inherit;
}
h1 {
color: inherit;
}
footer {
font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
}
form {
    margin: 0 0 18px;
}


</style>
<meta property="og:site_name" content="<?php echo SITENAME ?>" /> 
<meta property="og:title" content="Deliver your brand to the palm of your customers hand" />
<meta property="og:description" content="Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online." /> 

</head>
<script>
function openhelpdesk() {
window.open('<?php echo SITE_URL ?>/helpdesk',
                '_blank',
                'toolbar=0,status=0,scrollbars=1,width=826,height=636');

            return false;
        }

</script>
	<body id="inner">

	
     <div class="navbar navbar-fixed-top" style="position:relative">

      <div class="navbar-inner">
        <div class="container">
			<div class="topbar">
			
				<!--<i class="micon-user-add"></i>&nbsp;<a href="">Add us / Follow Us / Like Us</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
				<!--<span style="background: url(<?php echo SITE_URL ?>/app/webroot/img/support2.gif) no-repeat scroll left center transparent; padding: 0 10px 0 25px"><a onclick="openhelpdesk()" style="cursor: pointer">Customer Support</a></span>-->
				<span><i class="fa fa-life-ring" style="color:#c0cddc;font-size:16px"></i><a onclick="openhelpdesk()" style="cursor: pointer"> Helpdesk</a></span>&nbsp;
				<!--<span style="background: url(<?php echo SITE_URL ?>/app/webroot/img/contact2.gif)  no-repeat scroll left 3px transparent; padding: 0 5px 0 27px"><a href="mailto:<?php echo SUPPORT_EMAIL ?>"><?php echo SUPPORT_EMAIL ?></a></span>-->
				<span><i class="fa fa-envelope" style="color:#c0cddc;font-size:16px"></i><a href="mailto:<?php echo SUPPORT_EMAIL ?>"> <?php echo SUPPORT_EMAIL ?></a></span>&nbsp;
				<?php if(!$this->Session->check('User')):?>
					<!--<span style="background: url(<?php echo SITE_URL ?>/app/webroot/img/login2.gif) no-repeat scroll left center transparent; padding: 0 0 0 22px"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?></span>-->
					<span><i class="fa fa-sign-in" style="color:#c0cddc;font-size:16px"></i><a href="<?php echo SITE_URL ?>/users/login" style="cursor: pointer"> Login</a></span>
					<?php else:?>
					<!--<?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?>&nbsp;&nbsp;&nbsp;&nbsp; -->
					<!--<span style="background: url(<?php echo SITE_URL ?>/app/webroot/img/login2.gif) no-repeat scroll left center transparent; padding: 0 0 0 22px"><?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?></span>-->
					<span><i class="fa fa-sign-out" style="color:#c0cddc;font-size:16px"></i><a href="<?php echo SITE_URL ?>/users/logout" style="cursor: pointer"> Logout</a></span>
					<?php endif;?>
				
			</div>          
			
	
			
			  
        
           <!--<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>-->
          <?php echo $this->Html->link($this->Html->image(LOGO), '/', array('escape' =>false, 'class' =>'brand'));?>
			<div class="nav-collapse pull-right">
				<ul id="main-menu-right">
			
					<li id="menu-item-745" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-745">
					<?php if(!$this->Session->check('User')):
				echo $this->Html->link('Sign Up Free',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'btn btn-primary btn-large'));

				else:
				echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'), array('escape' =>false,'class'=>'btn btn-primary btn-large'));
endif;?>	

					</li>
							
				</ul>
			</div> 
                  <div class="topbar"><ul id="main-menu" class="nav pull-right"><li id="menu-item-924" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-924"><?php echo $this->Html->link('Home','/',array('style'=>'padding-top:12px'))?></li>
<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('Features',array('controller' => 'pages', 'action' =>'features'),array('style'=>'padding-top:12px'))?></li>
<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('Industries',array('controller' => 'pages', 'action' =>'industries'),array('style'=>'padding-top:12px'))?></li>
<!--<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'),array('style'=>'padding-top:12px'))?></li>-->
<li id="menu-item-748" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-748">
<?php if(!$this->Session->check('User')):{
echo $this->Html->link('Pricing',array('controller' => 'pages', 'action' =>'pricingplans'), array('escape' =>false,'style'=>'padding-top:12px')); 
}else:
  $payment=PAYMENT_GATEWAY;
  if($payment=='1' || $payment=='3'){
     echo $this->Html->link('Pricing', array('controller' =>'users', 'action' =>'paypalpayment'), array('escape' =>false,'style'=>'padding-top:12px')); 
 }else if($payment=='2'){
     echo $this->Html->link('Pricing', array('controller' =>'users', 'action' =>'checkoutpayment'), array('escape' =>false,'style'=>'padding-top:12px'));
 } endif;?>

</ul></div>      
       </div>
      </div>
    </div>

        
        
        <div class="wrapper">

			<div id="content">
			</div>
			
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
				<!--Testimonials area-->
				<br/><br/><br/><br/>
			</div>
		</div>
		<!-- Wrapper end-->
		
        <!--footer area-->
<footer>
      <h1 class="center" style="font-size: 24px;line-height: 36px;display:block;">You can be assured your personal information and any SMS messages/voicemails are completely safe with us and will <strong>NEVER</strong> be shared with third parties.</h1><br />
      
      <br />
      <hr>
      <div class="container">
		<div class="row-fluid">
			<div class="span6">
				<h3 class="text-left">Navigate</h3>
				<div class="row-fluid">
					<div class="span6">
							<ul>
								<li style="line-height: 25px;"><a style="color: #0088cc;text-decoration: none;" href="<?php echo $this->Html->url('/')?>">Home</a></li>
								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'about'))?>">About</a></li>
								<li style="line-height: 25px;"><a onclick="openhelpdesk()" style="cursor: pointer">Help &amp; Support</a></li>								
		
		
				
								<li style="line-height: 25px;"><?php if(!$this->Session->check('User')):{
echo $this->Html->link('Pricing &amp; Signup',array('controller' => 'pages', 'action' =>'pricingplans'), array('escape' =>false)); 
}else:
  $payment=PAYMENT_GATEWAY;
  if($payment=='1' || $payment=='3'){
     echo $this->Html->link('Pricing &amp; Signup', array('controller' =>'users', 'action' =>'paypalpayment'), array('escape' =>false)); 
 }else if($payment=='2'){
     echo $this->Html->link('Pricing &amp; Signup', array('controller' =>'users', 'action' =>'checkoutpayment'), array('escape' =>false));
 } endif;?>
								
								<li style="line-height: 25px;"><a href="mailto:<?php echo SUPPORT_EMAIL ?>">Contact Us</a></li>				
							</ul>
					</div>
					<div class="span6">
							<ul>

								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'terms_conditions'))?>">Terms &amp Conditions</a></li>
								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'privacy_policy'))?>">Privacy Policy</a></li>								
								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'faq'))?>">FAQ</a></li>
								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'antispampolicy'))?>">Anti-Spam Policy</a></li>
								
							</ul>
					</div>								
				</div>
			</div>

			<div class="span6">
				<h3 class="text-left">Subscribe to Our Email List</h3>
				<p>For the latest tips, offers, and news. We promise not to spam!</p><br />
				<form action="" method="post" id="subForm">
					<div class="input-append">
						<input size="16" type="text" name="email" id="email" placeholder="Enter Your Email" style="width:200px;height:30px"><input class="btn" type="submit" value="Subscribe Me!" style="width:150px;height:30px">
              		</div>
					
				</form>
				<a href="http://twitter.com/home?status=Deliver+your+brand+to+the+palm+of+your+customers+hand+<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Twitter About <?php echo SITENAME ?>" src="<?php echo SITE_URL ?>/app/webroot/img/twitterfooter.png" /></a>&nbsp;&nbsp;
				<a href="http://www.facebook.com/sharer.php?u=<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Facebook About <?php echo SITENAME ?>" src="<?php echo SITE_URL ?>/app/webroot/img/facebookfooter.png" /></a>&nbsp;&nbsp;
				<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo SITE_URL ?>&amp;title=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;summary=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;source=<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Linkedin.com About <?php echo SITENAME ?>" src="<?php echo SITE_URL ?>/app/webroot/img/linkedinfooter.png" /></a>		 
			</div>
		</div>	
			<hr>
				
				<p><?php echo SITENAME ?> is a 100% opt-in and SPAM-FREE service. Please see our Anti-Spam Policy to learn about our position on SPAM and how it's handled.</p>
<p>Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online.</p>
<br/>
<?php if (API_TYPE==2){?>
<p>For help, text HELP to 47711<br/>
To opt-out, text STOP to 47711<br/>
msg&data rates may apply.</p>

<?php }?>
      <!--<p class="pull-right"><a href="<?php echo SITE_URL ?>/#">Back to top</a></p>-->


<p class="pull-right"><div id='MicrosoftTranslatorWidget' class='Dark' style='color:white;background-color:#555555;margin-top:-5px;float:right'></div><script type='text/javascript'>setTimeout(function(){{var s=document.createElement('script');s.type='text/javascript';s.charset='UTF-8';s.src=((location && location.href && location.href.indexOf('https') == 0)?'https://ssl.microsofttranslator.com':'http://www.microsofttranslator.com')+'/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**&ctf=True&ui=true&settings=Manual&from=en';var p=document.getElementsByTagName('head')[0]||document.documentElement;p.insertBefore(s,p.firstChild); }},0);</script></p>
      <br />
        <p>&copy;2014 <a href="<?php echo SITE_URL ?>"><?php echo SITENAME ?></a> All Rights Reserved. </p>
          		</div>
      </footer>



<script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js"></script> 
            <!-- Wrapper end-->
			
        
        <!--footer area-->
        
	</body>
</html>

<script type="text/javascript">
$().ready(function() {
	$('a.nyroModal').nyroModal();
	
});
</script>