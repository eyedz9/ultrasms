<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<title>
		<?php echo SITENAME ?> - 
		<?php echo $title_for_layout; ?>
</title>
<link href="<?php echo SITE_URL ?>/app/webroot/css/bootstrap.css"type="text/css" rel="stylesheet" />
<link href="<?php echo SITE_URL ?>/app/webroot/css/font-awesome.css" type="text/css" rel="stylesheet" />
<link href="<?php echo SITE_URL ?>/app/webroot/css/stylehome.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<?php
	echo $this->Html->meta('icon');
?>
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

	
     <!--START : NAV -->
  <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle nav-toggle-btn nav-show" data-toggle="collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <!--<a class="navbar-brand" href="#">
        <h1 class="logo"> Your Logo Here</h1>
        </a>-->
        <?php echo $this->Html->link($this->Html->image(LOGO), '/', array('escape' =>false, 'class' =>'brand'));?>
        </div>
      <div class="navbar-collapse nav-slide-toggle" id="nav-collapse">
        <ul class="nav navbar-nav navbar-right text-uppercase">
 <?php if ($this->params['controller'] == 'pages' && $this->params['action'] == 'features') {?>          
          <li><a href="<?php echo $this->Html->url('/')?>">HOME</a></li>
          <li><a class="active-nav" href="<?php echo SITE_URL ?>/pages/features">FEATURES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/industries">INDUSTRIES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/pricingplans">PRICING</a></li>
 <?}elseif ($this->params['controller'] == 'pages' && $this->params['action'] == 'pricingplans') { ?>
          <li><a href="<?php echo $this->Html->url('/')?>">HOME</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/features">FEATURES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/industries">INDUSTRIES</a></li>
          <li><a class="active-nav" href="<?php echo SITE_URL ?>/pages/pricingplans">PRICING</a></li>
 <?}elseif ($this->params['controller'] == 'pages' && $this->params['action'] == 'industries') { ?>
          <li><a href="<?php echo $this->Html->url('/')?>">HOME</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/features">FEATURES</a></li>
          <li><a class="active-nav" href="<?php echo SITE_URL ?>/pages/industries">INDUSTRIES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/pricingplans">PRICING</a></li>
 <?} else { ?>
          <li><a class="active-nav" href="<?php echo $this->Html->url('/')?>">HOME</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/features">FEATURES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/industries">INDUSTRIES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/pricingplans">PRICING</a></li>
 <?} ?>
                    <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn signup">SIGN UP</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn signup">MY ACCOUNT</a><?php } ?> <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/login" class="btn login">LOGIN</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/logout" class="btn login">LOGOUT</a><?php } ?>
        </ul>
      </div>
    </div>
  </nav>
  <!--END : NAV --> 

<!--START : HEADER AND BANNER -->
  <header id="header" style="min-height:60%">
    <div class="container banner-text text-center ">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
 <?php if ($this->params['controller'] == 'pages' && $this->params['action'] == 'features') {?>
          <h1 style="margin-top:180px">Features</h1>
          <p>Our comprehensive feature set is enjoyed by marketing pros, however our intuitive web-based interface is easy enough for anyone to use</p>

<?}elseif ($this->params['controller'] == 'pages' && $this->params['action'] == 'pricingplans') { ?>
          <h1 style="margin-top:180px">Pricing</h1>
          <p>Inexpensive and Flexible Plans to Fit Any Budget For Your SMS Marketing Campaigns</p>
<?}elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'antispampolicy') { ?>
          <h1 style="margin-top:180px">Anti-Spam Policy</h1>
<?}elseif ($this->params['controller'] == 'pages' && $this->params['action'] == 'industries') { ?>
          <h1 style="margin-top:180px">Industries</h1>
          <p>SMS marketing is fast becoming the most effective yet affordable method to target interested subscribers for many industries, largely because it's extremely affordable, has a high ROI, high deliverability, very high open rates, and is permission-based</p>
<?}elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'about') { ?>
          <h1 style="margin-top:180px">About Us</h1>
<?}elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'faq') { ?>
          <h1 style="margin-top:180px">FAQ</h1>
<?}elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'terms_conditions') { ?>
          <h1 style="margin-top:180px">Terms & Conditions</h1>
<?}elseif ($this->params['controller'] == 'users' && $this->params['action'] == 'privacy_policy') { ?>
          <h1 style="margin-top:180px">Privacy Policy</h1>
<?} ?>
          <!--<div class="banner-buttons"> <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn btn-transparent-white">Create Your account</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn btn-transparent-white">My Account</a><?php } ?> </div>-->
        </div>
      </div>
    </div>
    <!--<div class="spcial-offer text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2"> SPECIAL OFFER: <?php echo FREE_SMS ?> FREE SMS credits upon account activation - Register Now! <br>
            <?php if (PAY_ACTIVATION_FEES=='2') {?>No Credit Card, No Obligations, No Setup Fee <?} ?> </div>
        </div>
      </div>
    </div>-->
  </header>
  <!--END : HEADER AND BANNER --> 

        
        
        <!--<div class="wrapper">-->

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
		<!--</div>-->
		<!-- Wrapper end-->
		
        <!--START : FOOTER SECTION -->
  <footer id="footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-3">
          <h3 class="text-uppercase">Company</h3>
          <div class="divder-s-w"></div>
          <ul>
            <li><a href="<?php echo $this->Html->url('/')?>">Home</a></li>
            <li><a href="<?php echo SITE_URL ?>/users/about">About</a></li>
            <li><a onclick="openhelpdesk()" style="cursor: pointer">Help & Support</a></li>
            <li><a href="mailto:<?php echo SUPPORT_EMAIL ?>">Contact Us</a></li>
            <li><a href="<?php echo SITE_URL ?>/users/login">Login</a></li>
            <li><a href="<?php echo SITE_URL ?>/users/faq">FAQ</a></li>
          </ul>
        </div>
        <div class="col-sm-3">
          <h3 class="text-uppercase">Quick links</h3>
          <div class="divder-s-w"></div>
          <ul>
            <li><a href="<?php echo SITE_URL ?>/users/terms_conditions">Terms & Conditions</a></li>
            <li><a href="<?php echo SITE_URL ?>/users/privacy_policy">Privacy Policy</a></li>
            <li><a href="<?php echo SITE_URL ?>/users/antispampolicy">Anti-Spam Policy</a></li>
          </ul>
        </div>
        <div class="col-sm-3 newsletter">
          <h3 class="text-uppercase">Subscribe Newsletter</h3>
          <div class="divder-s-w"></div>
          <p>For the latest tips, offers, and news. 
            We promise not to spam!</p>
          <form>
            <div class="form-group">
              <input type="email" class="form-control" id="email" placeholder="Enter Your Email">
            </div>
            <div class="form-group">
              <button type="submit" class="btn submitbtn">
              <a href="">Subscribe Me</a>
              </button>
            </div>
          </form>
        </div>
        <div class="col-sm-3 paddingleft">
          <h3 class="text-uppercase">Share</h3>
          <div class="divder-s-w"></div>
          <a href="http://www.facebook.com/sharer.php?u=<?php echo SITE_URL ?>" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/facebook.png" height="30" width="31" /></a> <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo SITE_URL ?>&amp;title=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;summary=Deliver%20your%20brand%20to%20the%20palm%20of%20your%20customers%20hand&amp;source=<?php echo SITE_URL ?>" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/linkedin.png" height="30" width="31" /></a> <a href="#twitter"><a href="http://twitter.com/home?status=Deliver+your+brand+to+the+palm+of+your+customers+hand+<?php echo SITE_URL ?>" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/twitter.png" height="30" width="31" /></a> 
          <!--<div class="translate"> <a href="#" class="btn translate-btn">Translate</a>&nbsp;&nbsp; <a href="#bing" ><img src="images/bing.png" height="21" width="58" /></a></div>-->
<?php if (API_TYPE==2){?>
<div class="translate">
<p>For help, text HELP to 47711<br/>
To opt-out, text STOP to 47711<br/>
msg&data rates may apply</p></div><?}?>
        </div>
      </div>
      <div class="copyright">
        <p><?php echo SITENAME ?> is a 100% opt-in and SPAM-FREE service. Please see our Anti-Spam Policy to learn about our position on SPAM and how it's handled.<br />
          Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online.</p>
        <p>©2016 <?php echo SITENAME ?>. All Rights Reserved. </p>
      </div>
    </div>
  </footer>
<script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js"></script> 
<script src="<?php echo SITE_URL ?>/app/webroot/js/jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo SITE_URL ?>/app/webroot/js/smsmarketing.js" type="text/javascript"></script> 

<script type="text/javascript">
$().ready(function() {
	$('a.nyroModal').nyroModal();
	
});
</script>        
	</body>
</html>

