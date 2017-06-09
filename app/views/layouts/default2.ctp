<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
		<?php echo SITENAME ?> - 
		<?php echo $title_for_layout; ?>
</title>
<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('style');
	echo $this->Html->css('nyroModal');
	
	
	echo $this->Html->script('jquery');
	echo $this->Html->script('jquery-1.7.1.js');
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<?php
	//echo $this->Html->script('validation');
	echo $this->Html->script('jquery.nyroModal.custom');
	echo $this->Html->script('jquery.cycle.all');
	echo $this->Html->css('cycle');

	
	echo $this->Html->script('twiliofunctions');
  
	
   echo $this->Html->script('jQvalidations/jquery.validation.functions');
   echo $this->Html->script('jQvalidations/jquery.validate');
   
  
	echo $scripts_for_layout;
	?>
<style>
#flashMessage{
font-size:16px;
 font-weight: bold;

}


</style>


</head>
	<body id="inner">
	
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
					<li style="margin-top: 12px;" class="<?php echo $homeCss?>"><?php echo $this->Html->link('Home','/')?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<li style="margin-top: 12px;" class="<?php echo $aboutCss?>"><?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'))?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<?php if(!$this->Session->check('User')):?>
					<li style="margin-top: 12px;" class="<?php echo $loginCss?>"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<?php else:?>
					<li style="margin-top: 12px;" class="<?php echo $myaccountCss?>"><?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<li style="margin-top: 12px;"><?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<?php endif;?>
					<!--<a href="<?php echo SITE_URL ?>/helpdesk"><img src="<?php echo SITE_URL ?>/app/webroot/img/hdsupport.jpg"/></a>-->
					               <li><div id='MicrosoftTranslatorWidget' class='Light' style='color:white;background-color:#555555;margin-top:-10px'></div><script type='text/javascript'>setTimeout(function(){{var s=document.createElement('script');s.type='text/javascript';s.charset='UTF-8';s.src=((location && location.href && location.href.indexOf('https') == 0)?'https://ssl.microsofttranslator.com':'http://www.microsofttranslator.com')+'/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**&ctf=true&ui=true&settings=Manual&from=en';var p=document.getElementsByTagName('head')[0]||document.documentElement;p.insertBefore(s,p.firstChild); }},0);</script></li>  
				</ul>
				<!--nav-->
			</div>
			<!-- Header end-->
        </div>
        <!-- Wrapper end-->
		
		
        <!-- Wrapper begin-->
        <div class="wrapper">
			<div id="content">
			
<?php


 if($this->params['controller']!='users' || $this->params['action'] != 'about' && $this->params['action'] != 'login' && $this->params['action'] != 'terms_conditions' && $this->params['action'] != 'privacy_policy' && $this->params['action'] != 'faq' && $this->params['action'] != 'antispampolicy' && $this->params['action'] != 'pricingplans' && $this->params['action'] != 'success' && $this->params['action'] != 'forgot_password' && $this->params['action'] != 'paypalpayment' && $this->params['action'] != 'checkoutpayment' && $this->params['action'] != 'purchase_credit' && $this->params['action'] != 'purchase_credit_checkout' && $this->params['action'] != 'review' && $this->params['action'] != 'activation' && $this->params['action'] != 'dashboard' && $this->params['action'] != 'thankyou' && $this->params['action'] != 'order_confirm' && $this->params['action'] != 'paypal_credit' && $this->params['action'] != 'checkout_credit' && $this->params['action'] != 'payment' && $this->params['action'] != 'thank_you' && $this->params['action'] != 'account_credited' && $this->params['action'] != 'add'){ ?>	  <div class="box-info">
           
               <div class="infobox-1">
               
                  <div class="info-img">
                     
                     <img src="<?php echo SITE_URL?>/img/messagesent1.png" alt=""  />
                  
                  </div>
                  
                  
                  <div class="info-text">
                      <p style="font-size: 18px"><?php echo $logsdetails;?> <br /> <span>Messages Sent (Month)</span></p>
                     
                  
                  </div>
               
               </div>
               
               <div class="infobox-2">
               
                  <div class="info-img">
                     <img src="<?php echo SITE_URL?>/img/contactsadd1.png" alt=""  />
                  
                  </div>
                  
                  
                  <div class="info-text">
                    <p style="font-size: 18px"><?php echo $subscriberdetails;?>   <br /><span>New Subscribers (Month)</span></p>
                  
                  </div>
               
               </div>
               
               <div class="infobox-3">
               
                  <div class="info-img">
                    <img src="<?php echo SITE_URL?>/img/contactsdelete1.png" alt=""  />   
                  
                  </div>
                  
                  
                  <div class="info-text">
                    <p style="font-size: 18px"><?php echo $un_subscribersdetails;?>   <br /><span>New Unsubscribers (Month)</span></p>
                  
                  </div>
               
               </div>
               
               <div class="infobox-4">
               
                  <div class="info-img">
                     <img src="<?php echo SITE_URL?>/img/rating.png" alt=""  />
                  
                  </div>
                  
                  
                  <div class="info-text">
                    <p style="font-size: 18px"><?php echo $percentagedata;?>% <br /><span>Overall Success Rating</span></p>	
                  
                  </div>
               
               </div>
           
       <div class="clear"></div>    
           </div>
		
		<?php  } ?>
			
			
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
<div id="footer">
         <!-- Wrapper begin-->
            <div style="background:url('<?php echo SITE_URL ?>/app/webroot/img/x_49.jpg') top repeat-x;height:200px;">
    
<center><div class="areaone"><div class=head3>Contact Us</div>
        <div class=menu-b>Questions? Comments? Feedback? You can email
us directly at <a href="mailto:<?php echo SUPPORT_EMAIL ?>"><?php echo SUPPORT_EMAIL ?></a>. 
Issues? Submit your issue with our <a href="<?php echo SITE_URL ?>/helpdesk">Helpdesk</a>.
We will respond as soon as possible.
        <div style="margin-top:10px;position:relative;bottom:1px;"><a href="<?php echo SITE_URL ?>/helpdesk"><img src="<?php echo SITE_URL ?>/app/webroot/img/hd1.jpg" style="width:160px"/></a>&nbsp;&nbsp;<a href="mailto:<?php echo SUPPORT_EMAIL ?>"><img src="<?php echo SITE_URL ?>/app/webroot/img/hd2.jpg" style="width:160px"/></a></div>
        </div>
        </div>
        <div class="areatwo"><div class=head3>Privacy</div>
        <div class=menu-b>You can be assured your personal information and any sent / received messages & voicemails are completely safe with us and will NEVER be shared with third parties.
        <!--<BR><img src="<?php echo SITE_URL ?>/app/webroot/img/paypal2co.gif" style="margin-top:9px;">-->
        <!--<BR><img src="<?php echo SITE_URL ?>/app/webroot/img/paypal2co.gif" style="margin-top:9px;">-->
        </div></div>
        <div class="areathree"><div class=head3>Connect With Us</div>
                  <a href="#" class="facebook"><img src="<?php echo SITE_URL ?>/app/webroot/img/facebook.png"/></a>&nbsp;&nbsp;
                  <a href="#" class="twitter"><img src="<?php echo SITE_URL ?>/app/webroot/img/twitter.png"/></a>&nbsp;&nbsp;
                  <a href="#" class="linkedin"><img src="<?php echo SITE_URL ?>/app/webroot/img/linkedin.png"/></a>
   <br><img src="<?php echo SITE_URL ?>/app/webroot/img/paypal-2co.png" style="margin-top:9px;">     
</center>   
    
</div>   
</div> 
    

<div style="background:url('<?php echo SITE_URL ?>/app/webroot/img/x_61.jpg') top repeat-x;height:47px;">
    <div style="padding:11px;width:968px;text-align:center;font-size:13px;margin-left:159px;color: #1B3A5C;font-size: 14px;font-family:Source Sans Pro;font-weight:bold;"><?php echo SITENAME ?> is a 100% opt-in and SPAM-FREE service. Please see our Anti-Spam Policy to learn about our position on SPAM and how it's handled.</div>
    
</div>    
    

<div style="background:url('<?php echo SITE_URL ?>/app/webroot/img/x_62.jpg') top repeat-x;height:41px;">
    <div class=menu2 style="padding:8px;width:968px;margin-left:185px;font-size:11px;">
        <a href="<?php echo $this->Html->url('/')?>">Home</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'about'))?>">About</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'terms_conditions'))?>"> Terms and Conditions</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'privacy_policy'))?>">Privacy Policy</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'faq'))?>">FAQ</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'antispampolicy'))?>">Anti-Spam Policy</a></div>
                
    </div>
    
</div>  
            <!-- Wrapper end-->
			<?php echo $this->element('sql_dump')?>
        
        <!--footer area-->
        <script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js">
        </script>
	</body>
</html>

<script type="text/javascript">
$().ready(function() {
	$('a.nyroModal').nyroModal();
	
});
</script>