<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="<?php echo SITE_URL ?>/app/webroot/favicon.ico" type="x-icon">
<title>
		<?php echo SITENAME ?> - 
		<?php echo $title_for_layout; ?>
</title>
    
<link rel='stylesheet' id='bootstrap-css'  href="<?php echo SITE_URL ?>/app/webroot/css/bootstrapwp.css" type='text/css' media='all' />
<link rel="stylesheet" href="<?php echo SITE_URL ?>/app/webroot/css/homestyle.css">
<link rel="stylesheet" href="<?php echo SITE_URL ?>/app/webroot/css/animate.min.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


<meta property="og:site_name" content="<?php echo SITENAME ?>" /> 
<meta property="og:title" content="Deliver your brand to the palm of your customers hand" />
<meta property="og:description" content="Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online." /> 

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<script src="<?php echo SITE_URL ?>/app/webroot/js/jquery-1.7.1.js" type="text/javascript"></script>

</head>



<script>


function openhelpdesk() {
      
window.open('<?php echo SITE_URL ?>/helpdesk',
                '_blank',
                'toolbar=0,status=0,scrollbars=1,width=826,height=636');

            return false;
        }


</script>


        
  <body class="home page page-id-14 page-template page-template-template-home-php"  data-spy="scroll" data-target=".subnav" data-offset="50"  >



    <div class="navbar navbar-fixed-top" >

      <div class="navbar-inner">
        <div class="container">
			<div class="topbar">			
				<!--<i class="micon-user-add"></i>&nbsp;<a href="">Add us / Follow Us / Like Us</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
				<!--<span style="background: url(./img/support2.gif) no-repeat scroll left center transparent; padding: 0 10px 0 25px"><a onclick="openhelpdesk()" style="cursor: pointer">Customer Support</a></span>-->
				<span><i class="fa fa-life-ring" style="color:#c0cddc;font-size:16px"></i><a onclick="openhelpdesk()" style="cursor: pointer"> Helpdesk</a></span>&nbsp;
				<!--<span style="background: url(./img/contact2.gif)  no-repeat scroll left 3px transparent; padding: 1px 5px 0 27px"><a href="mailto:<?php echo SUPPORT_EMAIL ?>"><?php echo SUPPORT_EMAIL ?></a></span>-->
				<span><i class="fa fa-envelope" style="color:#c0cddc;font-size:16px"></i><a href="mailto:<?php echo SUPPORT_EMAIL ?>"> <?php echo SUPPORT_EMAIL ?></a></span>&nbsp;
				<?php if(!$this->Session->check('User')):?>
					<!--<span style="background: url(./img/login2.gif) no-repeat scroll left center transparent; padding: 1px 0 0 22px"><?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?></span>-->
					<span><i class="fa fa-sign-in" style="color:#c0cddc;font-size:16px"></i><a href="<?php echo SITE_URL ?>/users/login" style="cursor: pointer"> Login</a></span>
					<?php else:?>
					<!--<?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?>&nbsp;&nbsp;&nbsp;&nbsp; -->
					<!--<span style="background: url(./img/login2.gif) no-repeat scroll left center transparent; padding: 1px 0 0 22px"><?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?></span>-->
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
				echo $this->Html->link('Sign Up Now',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'btn btn-primary btn-large'));
				else:
				echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'), array('escape' =>false,'class'=>'btn btn-primary btn-large'));
endif;?>	

					</li>
							
				</ul>
			</div> 
                 <div class="topbar"><ul id="main-menu" class="nav pull-right"><li id="menu-item-924" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-924 dropdown"><?php echo $this->Html->link('Home','/')?></li>
<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('Features',array('controller' => 'pages', 'action' =>'features'))?></li>
<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('Industries',array('controller' => 'pages', 'action' =>'industries'))?></li>
<!--<li id="menu-item-815" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-815"><?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'))?></li>-->
<li id="menu-item-748" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-748">
<?php if(!$this->Session->check('User')):{
echo $this->Html->link('Pricing',array('controller' => 'pages', 'action' =>'pricingplans'), array('escape' =>false)); 
}else:
  $payment=PAYMENT_GATEWAY;
  if($payment=='1' || $payment=='3'){
     echo $this->Html->link('Pricing', array('controller' =>'users', 'action' =>'paypalpayment'), array('escape' =>false)); 
 }else if($payment=='2'){
     echo $this->Html->link('Pricing', array('controller' =>'users', 'action' =>'checkoutpayment'), array('escape' =>false));
 } endif;?>

</ul>
</div>      
       </div>
      </div>
    </div>
 

    

   
	<header class="jumbotron masthead">
	<!--<div id="banner-screen">-->
<!--<img style="display:block;" src="<?php echo SITE_URL ?>/app/webroot/img/bannerscreenshot.png">-->
<!--<div style="text-align:center; width: 73%; margin-top:130px; background:url(<?php echo SITE_URL ?>/app/webroot/img/videoborder2.png) no-repeat top center;height:458px;padding-top:20px;margin-left:120px"><script type="text/javascript" src="<?php echo SITE_URL ?>/app/webroot/embed.php?v=c732759b&p="></script></div>
	</div>-->
		<div class="carousel slide" id="myCarousel">

		
		
			<div class="carousel-inner">
			    <div class="item slide-4 active">
			    	<div class="container">		 
			    		<div class="row-fluid">
			    			<div class="span7" style="width:100%;text-align:center">
			
			    					
					    					<h1 class="stretchLeft" id="header1" style="text-shadow: 1px 1px 1px #292929;text-align:center !important">Build a SMS Marketing List</h1><br />

			    					<h2 class="stretchRight" id="header2" style="text-shadow: 1px 1px 1px #292929;width:100%;text-align:center !important">Grow your mobile audience, engage clients and increase sales with our smart and effective SMS Marketing solutions.</h2><br />	  

<?php if(!$this->Session->check('User')):
				echo $this->Html->link('Create your account',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'btn btn-large btn-primary'));
				else:
				echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'), array('escape' =>false,'class'=>'btn btn-large btn-primary'));
endif;?>	
<br/><br/><font style="color: white;"><b>SPECIAL OFFER:</b> <font style="color: yellow; font-weight:bold"><?php echo FREE_SMS ?></font> <font style="color: white; font-weight: bold">FREE</font> SMS credits upon account activation - Register Now!
<?php if (PAY_ACTIVATION_FEES=='2') {?>
<br/><br/>
<font style="color: white;">No Credit Card, No Obligations, No Setup Fee</font>
<?} ?>
</div>  <br />
 				

				    		</div>

			    		</div>
			    	</div>
			    </div>

			</div>
			 
		</div>
	</header>	
	
	<div style="background: url(<?php echo SITE_URL ?>/app/webroot/img/footer-bg.png)  repeat-x scroll 0 0 #161616">
	<div class="marketing" style="background: url(<?php echo SITE_URL ?>/app/webroot/img/top-pattern.png) repeat scroll 0 0 ">		<div class="container">
    		<div class="row-fluid" class="animate" id="banner1">
	    		<div class="span4">
					<span class="circle"><!--<img src="./img/captureleads.png" style="margin-top:0px">--><i class="fa fa-plus-circle" style="color:#46b982"></i></span>					
					<h2 class="center" style="text-shadow: 1px 1px 1px #000;">Capture Leads Anywhere</h2>		
					<p>With a text message, QR code, or web form.</p>			   		
	    		</div>	  		
	    		<div class="span4">
	 				<span class="circle"><!--<img src="./img/reachpeople.png" style="margin-top:0px">--><i class="fa fa-globe" style="color:#46b982"></i></span>
	 				<h2 class="center" style="text-shadow: 1px 1px 1px #000;">Reach People Instantly</h2>
	 				<p>In 200+ countries via SMS.</p>
	    		</div>	
	    		<div class="span4">
					<span class="circle"><!--<img src="./img/growbusiness.png" style="margin-top:0px">--><i class="fa fa-line-chart" style="color:#46b982"></i></span>	
					<h2 class="center" style="text-shadow: 1px 1px 1px #000;">Grow Your Business via SMS</h2>
					<p>Gain new customers &amp; keep in touch with old.</p>		   		
	    		</div>	  
    		</div>
    	</div></div>	
	</div>


		
	<!--<div class="content" style="background-image: url(<?php echo SITE_URL ?>/app/webroot/img/smsbackground.jpg); height: 642.5px; background-position:center top">-->
	<div class="content" style="background-color:#fff">
		 <div id="far-clouds" class="far-clouds stage"></div>
    <div id="near-clouds" class="near-clouds stage"></div>
		<div class="container"  style="z-index:101;">
	
			<h1 class="center" style="margin-top:-10px; color:#666;">What is <?php echo SITENAME ?>?</h1>
			<p class="center" style="margin-bottom:20px; color:#666">Smarter SMS Marketing software that allows you to create effective SMS marketing campaigns easily online</p>
			<!--<div style="text-align:center; width: 63%; margin-top:30px; background:url(http://www.ultrasmsscript.com/images/videoborder.png) no-repeat top center;height:458px;padding-top:26px;margin-left:160px"><iframe width="552" height="308" src="http://www.easywebvideo.com/video.php?v=c732759b" frameborder="0" allowfullscreen></iframe>-->
		<!--<script type="text/javascript" src="http://www.easywebvideo.com/embed.php?v=c732759b&p="></script>-->
		
		
				
<!--<img src="/images/video-shadow-box.png" style="margin-top: -195px; width: 580px"/>-->

<!--</div>-->


			<div class="row-fluid" >
				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/contacts64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-users" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Contacts</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Collect and manage your personal and professional contacts and engage your audience for sales.</p>
				</div>

				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/bulksms64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-comments" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Bulk SMS</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">At the heart of a SMS marketing campaign is the ability to send Bulk SMS messages to your subscribers.</p>
				</div>

				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/polls64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-list-ol" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">SMS Polls</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Create polls to collect valuable information from your subscribers and another tool to keep your subscribers engaged.</p>			
				</div>
				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/voicebroadcast64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-bullhorn" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Voice Broadcast</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Send important alerts, promotions, updates, and notifications to customers, employees, and more.</p>			
				</div>
			</div>
			
			<div class="row-fluid" style="margin-top:20px" >
				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/autoresponders64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-repeat" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Autoresponders</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Send your subscribers automated messages after subscribing on a preset schedule, like email autoresponder work.</p>
				</div>

				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/splashbuilder64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-paint-brush" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Mobile Splash Builder</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Create your own mobile web pages with video, images, or any HTML and then send those page URLs out.</p>
				</div>

				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/widgets64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-cogs" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Web Sign-up Widgets</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Create web based opt-in widgets to allow potential customers to join your subscriber list through a web based form.</p>			
				</div>
				<div class="span3 home-features">
					<!--<span class="circle"><img src="./img/analytics64.png" style="margin-top:0px"></span>-->
					<span class="circle"><i class="fa fa-bar-chart" style="font-size:60px;color:lightblue;"></i></span>
					<h3 style="font-size: 21px;font-weight: 300;margin-top: 15px;text-align: center; color:#414141;">Campaign Analysis</h3>
					<p style="color: #666;font-size: 14px;text-shadow: 1px 1px 1px #fff;">Track your campaigns and take a look into keyword performance, SMS logs, new subscribers and unsubscribers to gain insight.</p>			
				</div>
			</div>
			<br /><br />
			     <h2 class="center" style="color:#414141;">...And Much More</h2><br />




</div>
	</div>
	<!--<div class="content features">
		<div class="container">

			<div class="row-fluid">
				<div class="span3 home-features">
					<span class="circle"><img src="./img/smscontacts.png" style="margin-top:0px"></span>
					<h3>Contacts</h3>
					<p>Collect and manage your personal and professional contacts and engage your audience for sales.</p>
				</div>

				<div class="span3 home-features">
					<span class="circle"><img src="./img/bulksms.png" style="margin-top:0px"></span>
					<h3>Bulk SMS</h3>
					<p>At the heart of a SMS marketing campaign is the ability to send Bulk SMS messages to your subscribers.</p>
				</div>

				<div class="span3 home-features">
					<span class="circle"><img src="./img/smspolls.png" style="margin-top:0px"></span>
					<h3>SMS Polls</h3>
					<p>Create polls to collect valuable information and as another way to keep your subscribers engaged.</p>			
				</div>
				<div class="span3 home-features">
					<span class="circle"><img src="./img/voiceb.png" style="margin-top:0px"></span>
					<h3>Voice Broadcast</h3>
					<p>Send important alerts, promotions, updates, and notifications to customers, employees, and more.</p>			
				</div>
			</div>
			<div class="row-fluid">
				<div class="span3 home-features">
					<span class="circle"><img src="./img/autores.png" style="margin-top:0px"></span>
					<h3>Autoresponders</h3>
					<p>Send your subscribers automated messages after subscribing on a preset schedule.</p>
				</div>

				<div class="span3 home-features">
					<span class="circle"><img src="./img/splashb.png" style="margin-top:0px"></span>
					<h3>Mobile Splash Builder</h3>
					<p>Create your own mobile web pages with video, images, or any HTML and then send those page URLs out.</p>
				</div>

				<div class="span3 home-features">
					<span class="circle"><img src="./img/widgets.png" style="margin-top:0px"></span>
					<h3>Web Sign-up Widgets</h3>
					<p>Create web based opt-in widgets to allow potential customers to join a list through a web based form.</p>			
				</div>
				<div class="span3 home-features">
					<span class="circle"><img src="./img/reportsanalytics.png" style="margin-top:0px"></span>
					<h3>Campaign Analysis</h3>
					<p>Track your campaigns and take a look into keyword performance, SMS logs, and new subscribers</p>			
				</div>
			</div>
			<br /><br />
			      <h2 class="center">...And Much <strong>MORE</strong> </h2><br />
		</div>
	</div>-->
	<div style="background: url(<?php echo SITE_URL ?>/app/webroot/img/footer-bg.png)  repeat-x scroll 0 0 #161616">
	<div class="marketing" style="background: url(<?php echo SITE_URL ?>/app/webroot/img/top-pattern.png) repeat scroll 0 0 ">		<div class="container">
			<h1 style="text-shadow: 1px 1px 1px #000;">Why SMS Marketing?</h1>
    		<div class="row-fluid" class="animate" id="banner2">
	    		<div class="span4">
					<span class="circle"><!--<img src="./img/fasterthanemail.png" style="margin-top:0px" />--><i class="fa fa-bolt" style="color:#46b982"></i></span>					
					<h2 class="center" style="text-shadow: 1px 1px 1px #000;">Faster than Email</h2>		
					<p>90% of text messages are read within 5 minutes. No email SPAM filters.</p>			   		
	    		</div>	  		
	    		<div class="span4">
	 				<span class="circle"><!--<img src="./img/morepersonal.png" style="margin-top:0px">--><i class="fa fa-mobile" style="color:#46b982"></i></span>
	 				<h2 class="center" style="text-shadow: 1px 1px 1px #000;">More Personal than Social</h2>
	 				<p>SMS reaches your audience directly in the palms of their hands.</p>
	    		</div>	
	    		<div class="span4">
					<span class="circle"><!--<img src="./img/furtherreach.png" style="margin-top:0px">--><i class="fa fa-map-marker" style="color:#46b982"></i></span>	
					<h2 class="center" style="text-shadow: 1px 1px 1px #000;">Further Reach than TV</h2>
					<p>Deliver your SMS instantly to 200+ countries. Superb reach.</p>		   		
	    		</div>	  
    		</div>
    	</div>	
	</div></div>	
	<div class="content who-needs">
		<div class="container">
			<h1 class="center" style="text-shadow: 1px 1px 1px #3d2101;">Who Needs SMS Marketing?</h1>
			<p class="center" style="text-shadow: 1px 1px 1px #3d2101;">Real Estate Agents, Bars &amp; Nightclubs, Restaurants, Salons &amp; Spas, Schools, Non-Profits, and <b>MUCH MORE</b>...</p><br />
		<div class="row-fluid">
		
			<div class="span4 center">			
				<img src="./img/musician.png" style="width:250px" alt="SMS Marketing for Musicians &amp; Artists">
				<h3 style="color: white;text-shadow: 1px 1px 1px #3d2101;">SMS Marketing for Musicians &amp; Artists</h3>				
			</div>
			
			<div class="span4 center">				
				<img src="./img/retail.png" style="width:250px" alt="SMS Marketing for Retail Shops">
				<h3 style="color: white;text-shadow: 1px 1px 1px #3d2101;">SMS Marketing for Retail Stores &amp; Shops</h3>				
			</div>
			
			<div class="span4 center">				
				<img src="./img/coffee.png" style="width:250px" alt="SMS Marketing for Restaurants &amp; Cafes">
				<h3 style="color: white;text-shadow: 1px 1px 1px #3d2101;">SMS Marketing for Restaurants &amp; Cafes</h3>				
			</div>			
								
			<!--<p class="center"><a href="" class="btn btn-large">See More</a></p>-->
		</div>
	</div>






    <!-- End Template Content -->

</div>
	<div class="content what-can">
		<h2 style="text-shadow: 1px 1px 1px #000">Start Your SMS Marketing Campaign Today</h2>
		<?php if (API_TYPE!=2){?>
		<p style="text-shadow: 1px 1px 1px #000">Unlimited Keywords + Unlimited Subscribers + Free Local Phone Number</p><br />
		<?php }else{?>	
		<p style="text-shadow: 1px 1px 1px #000">Free Keyword + Unlimited Subscribers + Free Short Code</p><br />
		<?php }?>
		<p class="center">
		
		<?php if(!$this->Session->check('User')):
				echo $this->Html->link('Get Started',array('controller' =>'users', 'action' => 'add'), array('escape' =>false,'class'=>'btn btn-large btn-primary animate', 'id'=>'bottomsignup'));
				else:
				echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'), array('escape' =>false,'class'=>'btn btn-large btn-primary'));
endif;?>	
		
		</p>
</div>
      <footer>
      <h1 class="center" style="font-size:24px">You can be assured your personal information and any SMS messages/voicemails are completely safe with us and will <strong>NEVER</strong> be shared with third parties.</h1><br />
      
      <br />
      <hr>
      <div class="container">
		<div class="row-fluid">
			<div class="span6">
				<h3 class="text-left">Navigate</h3>
				<div class="row-fluid">
					<div class="span6">
					
							<ul>
								<li style="line-height: 25px;"><a href="<?php echo $this->Html->url('/')?>">Home</a></li>
<!-- 								<li><a href="/how-it-works/">How it Works</a></li> -->
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

				<a href="http://twitter.com/home?status=Deliver+your+brand+to+the+palm+of+your+customers+hand+<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Twitter About <?php echo SITENAME ?>" src="./img/twitterfooter.png" /></a>&nbsp;&nbsp;
				<a href="http://www.facebook.com/sharer.php?u=<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Facebook About <?php echo SITENAME ?>" src="./img/facebookfooter.png" /></a>&nbsp;&nbsp;
							
				
				<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo SITE_URL ?>&amp;title=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;summary=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;source=<?php echo SITE_URL ?>" target="_blank"><img alt="Tell Linkedin.com About <?php echo SITENAME ?>" src="./img/linkedinfooter.png" /></a>		 
			</div>
		</div>	
			<hr>
				<!--<p>Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online.</p>-->
				<p><?php echo SITENAME ?> is a 100% opt-in and SPAM-FREE service. Please see our Anti-Spam Policy to learn about our position on SPAM and how it's handled.</p>
				<p>Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online.</p>

<?php if (API_TYPE==2){?>
<p>For help, text HELP to 47711<br/>
To opt-out, text STOP to 47711<br/>
msg&data rates may apply</p>

<?php }?>
      <!--<p class="pull-right"><a href="<?php echo SITE_URL ?>/#">Back to top</a></p>
-->

<p class="pull-right"><div id='MicrosoftTranslatorWidget' class='Dark' style='color:white;background-color:#555555;margin-top:-5px;float:right'></div><script type='text/javascript'>setTimeout(function(){{var s=document.createElement('script');s.type='text/javascript';s.charset='UTF-8';s.src=((location && location.href && location.href.indexOf('https') == 0)?'https://ssl.microsofttranslator.com':'http://www.microsofttranslator.com')+'/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**&ctf=True&ui=true&settings=Manual&from=en';var p=document.getElementsByTagName('head')[0]||document.documentElement;p.insertBefore(s,p.firstChild); }},0);</script></p>
      <br />
        <p>&copy;2014 <a href="<?php echo SITE_URL ?>"><?php echo SITENAME ?></a> All Rights Reserved. </p>
          		</div>
      </footer>

   <!-- /container -->
		
	<div style="display:none">
	</div>


<script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js"></script> 
<script>
	$(window).scroll(function() {
		$('#banner1').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+800) {
				$(this).addClass("fadeIn");
			}
		});
	});
	
	$('#banner1').click(function() {
		$(this).addClass("fadeIn");
	});
	
	$(window).scroll(function() {
		$('#banner2').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+800) {
				$(this).addClass("fadeIn");
			}
		});
	});

	$('#banner2').click(function() {
		$(this).addClass("fadeIn");
	});
	
	$(window).scroll(function() {
		$('#features').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+500) {
				$(this).addClass("bigEntrance");
			}
		});
	});

	$('#features').click(function() {
		$(this).addClass("bigEntrance");
	});
	
	$(window).scroll(function() {
		$('#features2').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+500) {
				$(this).addClass("bigEntrance");
			}
		});
	});

	$('#features2').click(function() {
		$(this).addClass("bigEntrance");
	});
	
	$(window).scroll(function() {
		$('#bottomsignup').each(function(){
		var imagePos = $(this).offset().top;

		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+800) {
				$(this).addClass("pulse");
			}
		});
	});

       
	$('#bottomsignup').click(function() {
		$(this).addClass("pulse");
	});
</script>
  </body>
</html>
