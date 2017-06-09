<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<title>
		<?php echo SITENAME ?> - 
		<?php echo $title_for_layout; ?>
</title>

<meta property="og:site_name" content="<?php echo SITENAME ?>" /> 
<meta property="og:title" content="Deliver your brand to the palm of your customers hand" />
<meta property="og:description" content="Smarter SMS Marketing solutions by <?php echo SITENAME ?> allows you to create effective text message marketing campaigns easily online." /> 

<!-- Bootstrap -->
<link href="<?php echo SITE_URL ?>/app/webroot/css/bootstrap.css"type="text/css" rel="stylesheet" />
<link href="<?php echo SITE_URL ?>/app/webroot/css/font-awesome.css" type="text/css" rel="stylesheet" />
<link href="<?php echo SITE_URL ?>/app/webroot/css/stylehome.css" rel="stylesheet" type="text/css" />
<!--Animation Style-->
<link href="<?php echo SITE_URL ?>/app/webroot/css/animation.css" rel="stylesheet" type="text/css" />
<!--Animation Style-->
<link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!--START : MAIN -->
<div id="main"> 
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
          <li><a class="active-nav" href="<?php echo $this->Html->url('/')?>">HOME</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/features">FEATURES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/industries">INDUSTRIES</a></li>
          <li><a href="<?php echo SITE_URL ?>/pages/pricingplans">PRICING</a></li>
                    <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn signup">SIGN UP</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn signup">MY ACCOUNT</a><?php } ?> <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/login" class="btn login">LOGIN</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/logout" class="btn login">LOGOUT</a><?php } ?>
        </ul>
      </div>
    </div>
  </nav>
  <!--END : NAV --> 
  <!--START : HEADER AND BANNER -->
  <header id="header">
    <div class="container banner-text text-center ">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <h1>Build a SMS Marketing List</h1>
          <p>Grow your mobile audience, engage clients and increase sales with our smart</p>
          <p> and effective SMS Marketing solutions.</p>
          <div class="banner-buttons"> <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn btn-transparent-white">Create Your account</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn btn-transparent-white">My Account</a><?php } ?> </div>
        </div>
      </div>
    </div>
    <div class="spcial-offer text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2"> SPECIAL OFFER: <?php echo FREE_SMS ?> FREE SMS credits upon account activation - Register Now! <br>
            <?php if (PAY_ACTIVATION_FEES=='2') {?>No Credit Card, No Obligations, No Setup Fee <?} ?> </div>
        </div>
      </div>
    </div>
  </header>
  <!--END : HEADER AND BANNER --> 
  <!--START : THREE COLORED BOX-->
  <section class="container-fluid">
    <div class="row">
      <div class="col-sm-4 colored-box-1 color-box"> <img src="<?php echo SITE_URL ?>/app/webroot/img/capture.png" height="70" width="87" />
        <h3>Capture Leads Anywhere</h3>
        <p>With a text message, QR code, or web form.</p>
      </div>
      <div class="col-sm-4 colored-box-2 color-box"> <img src="<?php echo SITE_URL ?>/app/webroot/img/reach.png" height="70" width="87" />
        <h3>Reach People Instantly</h3>
        <p>In 200+ countries via SMS.</p>
      </div>
      <div class="col-sm-4 colored-box-3 color-box"> <img src="<?php echo SITE_URL ?>/app/webroot/img/grow.png" height="70" width="87" />
        <h3>Grow Your Business via SMS</h3>
        <p>Gain new customers & keep in touch with old.</p>
      </div>
    </div>
  </section>
  <!--END : THREE COLORED BOX --> 
  <!--START : MAIN CONTACT SECTION -->
  <section id="contact">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 ">
          <h1>What is <?php echo SITENAME ?>?</h1>
          <div class="divder-h"></div>
          <p class="marginbot">Smarter SMS Marketing software that allows you to create effective SMS marketing campaigns easily </p>
        </div>
      </div>
      
      <!-- START : CONTECTS SECTION-->
      <section class="section-bg  image-section">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6  animate-ltr">
              <div class="left-box">
                <h2>Contacts</h2>
                <div class="divder-s"></div>
                <p>Manage your professional list of subscribers in our contact management module. View the source of all sign-ups, whether they signed up through SMS keyword, web widget, kiosk, or if they were manually added.</p>
                <p>Search on name, number, group and source of sign-up. Send these contacts a SMS individually from here or as part of a group from the bulk SMS module.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="  animate-rtl image-right image-width"> <img src="<?php echo SITE_URL ?>/app/webroot/img/contacts_bg.png" width="604" height="436" class="img-full  "> </div>
      </section>
      
      <!-- END : CONTECTS SECTION--> 
      
      <!-- START : BULK SMS SECTION-->
      <section class="section-bg  image-section">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6">&nbsp;</div>
            <div class="col-md-6 col-sm-6  animate-rtl">
              <div class="left-box">
                <h2>Bulk SMS</h2>
                <div class="divder-s"></div>
                <p>At the heart of a SMS marketing campaign is the ability to send Bulk SMS messages to your subscribers.</p>
                <p>Send to 1 group or multiple groups at once! Send messages to your customers to announce deals or discounts you want to promote which can bring in massive business. </p>
              </div>
            </div>
          </div>
        </div>
        <div class=" animate-ltr image-left image-width" > <img src="<?php echo SITE_URL ?>/app/webroot/img/bulksms_bg.png" height="456" width="625"  class="img-full" /> </div>
      </section>
      
      <!-- END : BULK SMS SECTION--> 
      
      <!-- START : SMS POLLS SECTION-->
      <section class="section-bg  image-section">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6  animate-ltr">
              <div class="left-box">
                <h2>SMS Polls</h2>
                <div class="divder-s"></div>
                <p>Create polls to collect valuable information from your subscribers and another tool to keep your subscribers engaged.</p>
                <p>What better way to engage a large audience than by sending a poll out to your subscribers allowing them to vote on their favorite menu item, or favorite song for example.</p>
              </div>
            </div>
          </div>
        </div>
        <div class=" animate-rtl image-right image-width " > <img src="<?php echo SITE_URL ?>/app/webroot/img/smspolls_bg.png" height="438" width="603" class="img-full "  /> </div>
      </section>
      <!-- END : SMS Polls SECTION--> 
      
      <!-- START : SMS Loyalty Rewards SECTION-->
      <section class="section-bg  image-section">
        <div class="container">
          <div class="row ">
            <div class="col-md-6 col-sm-6">&nbsp;</div>
            <div class="col-md-6 col-sm-6  animate-rtl">
              <div class="left-box">
                <h2>SMS Loyalty Rewards</h2>
                <div class="divder-s"></div>
                <p>Forget those archaic and often misplaced paper punch cards. Offer SMS “punch card” loyalty rewards to your customers and build loyalty to your brand to keep customers happy and coming back.</p>
                <p>Digital punch cards remove any unnecessary hurdles and dramatically increase customer engagement.</p>
              </div>
            </div>
          </div>
        </div>
        <div class=" animate-ltr image-left image-width" > <img src="<?php echo SITE_URL ?>/app/webroot/img/smsloyalty_bg.png" height="458" width="623" class="img-full"  /></div>
      </section>
      <!-- END : SMS Loyalty Rewards SECTION--> 
      
      <!-- START : SMS POLLS SECTION-->
      <section class=" section-bg image-section">
        <div class="container ">
          <div class="row ">
            <div class="col-md-6 col-sm-6  animate-ltr">
              <div class="left-box">
                <h2>Autoresponders</h2>
                <div class="divder-s"></div>
                <p>Send your subscribers automated messages after subscribing on a preset schedule, like email autoresponder work.</p>
                <p>Automatically send them a messsage 1 day, 7 days or 30 days after they subscribe. Setup an entire follow-up SMS campaign with them after they subscribe.</p>
              </div>
            </div>
          </div>
        </div>
        <div class=" animate-rtl image-right image-width" > <img src="<?php echo SITE_URL ?>/app/webroot/img/autorespondsr_bg.png" height="437" width="599" class="img-full pull-right" /></div>
      </section>
      <div class="container">
        <div class="row">
          <div class="col-sm-12"> <a href="<?php echo SITE_URL ?>/pages/features" class="btn loadmore">See More Features</a></div>
        </div>
      </div>
      <!-- END : SMS Polls SECTION--> 
    </div>
  </section>
  <!--END : MAIN CONTACT SECTION --> 
  
  <!--START : WHY SMS MARKERTING SECTION -->
  <section id="whysmsmarketing">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 ">
          <h1>Why SMS Marketing?</h1>
          <div class="divder-w"></div>
          <p class="marginbot">Smarter SMS Marketing software that allows you to create effective SMS marketing campaigns easily </p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="whysmsma-inner">
           <img src="<?php echo SITE_URL ?>/app/webroot/img/faster.png" height="77" width="83" />
            <h3>Faster than Email</h3>
            <p>90% of text messages are read within 5 minutes. No email SPAM filters.</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="whysmsma-inner"><img src="<?php echo SITE_URL ?>/app/webroot/img/mobile_icon.png" height="77" width="83" />
            <h3>More Personal than Social</h3>
            <p>SMS reaches your audience directly in the palms of their hands.</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="whysmsma-inner"><img src="<?php echo SITE_URL ?>/app/webroot/img/marketing_reach.png" height="77" width="83" />
            <h3>Further Reach than TV</h3>
            <p>Deliver your SMS instantly to 200+ countries. Superb reach.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!--END : WHY SMS MARKERTING SECTION --> 
  
  <!--START : INDURIES SECTION -->
  <section id="industries">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 ">
          <h1>Industries We Serve</h1>
          <div class="divder-h"></div>
          <p class="marginbot">Real Estate Agents, Bars & Nightclubs, Restaurants, Salons & Spas, Schools, Non-Profits, and much more. </p>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-4"> 
        <img src="<?php echo SITE_URL ?>/app/webroot/img/gym.jpg" height="257" width="427" border="0" class="img-responsive fullwidth"  />
          <div class="image-desc">
            <div class="img-content">
             <img src="<?php echo SITE_URL ?>/app/webroot/img/gyms.png" height="76" width="76" />
              <h2>Fitness Centers</h2>
              <p>One of the challenges most gym owners face in a very competitive market is keeping their customers coming back to their facility to work out. Well, with our easy to use text marketing software, that problem will quickly become a thing of the past. </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4"> 
        <img src="<?php echo SITE_URL ?>/app/webroot/img/stores.jpg" height="257" width="427" border="0" class="img-responsive fullwidth" />
          <div class="image-desc">
            <div class="img-content">
             <img src="<?php echo SITE_URL ?>/app/webroot/img/stores.png" height="76" width="76" />
              <h2>Retail</h2>
              <p>Thanks to its unavoidable nature, SMS marketing is perfectly suited to the retail market. Marketing in the retail sector requires a massive campaign of direct mail, email or inserts to be effective. This makes SMS marketing a convenient and very effective way to get a message to consumers.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-4"><img src="<?php echo SITE_URL ?>/app/webroot/img/resturants.jpg" height="257" width="427" border="0" class="img-responsive fullwidth" />
          <div class="image-desc">
            <div class="img-content">
             <img src="<?php echo SITE_URL ?>/app/webroot/img/resturants.png" height="76" width="76" />
              <h2>Restaurants & Cafes</h2>
              <p>Text message marketing is a simple and inexpensive way for restaurants to increase customer loyalty and revenue. Reward your customers with discounts on menu items and let them know about specials you are running. </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4"><img src="<?php echo SITE_URL ?>/app/webroot/img/danceclass.jpg" height="257" width="427" border="0" class="img-responsive fullwidth" />
          <div class="image-desc">
            <div class="img-content">
            <img src="<?php echo SITE_URL ?>/app/webroot/img/danceclass.png" height="76" width="76" />
              <h2>Dance Classes</h2>
              <p>Let's face it. Students and parents at your school are always on their smartphone. Our easy to use text marketing system allows you to keep consistent communication with all of them about class schedules and announcements. </p>
            </div>
          </div>
        </div>
        <div class="col-sm-4"><img src="<?php echo SITE_URL ?>/app/webroot/img/salon.jpg" height="257" width="427" border="0" class="img-responsive fullwidth" />
          <div class="image-desc">
            <div class="img-content">
             <img src="<?php echo SITE_URL ?>/app/webroot/img/salon.png" height="76" width="76" />
              <h2>Salons & Spas</h2>
              <p>The market is changing everyday, in terms of technology, and spa owners do very well utilizing SMS messaging to their benefit. The majority of clients at spas and salons are return customers, opening the door for them to opt in to receive SMS messages about the latest sale, special, or service.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-4"><img src="<?php echo SITE_URL ?>/app/webroot/img/medical.jpg" height="257" width="427" border="0" class="img-responsive fullwidth" />
          <div class="image-desc">
            <div class="img-content"> 
            <img src="<?php echo SITE_URL ?>/app/webroot/img/medical.png" height="76" width="76" />
              <h2>Medical</h2>
              <p>Missed appointments and forgotten medications can be a thing of the past. Our automated service is an ideal way to reach out to patients with appointment or prescription reminders. Doctors and dentists both find the appointment reminder feature very helpful in maintaining a very tight schedule. </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--END : INDURIES SECTION --> 
  
  <!--START : LOOKS GREAT SECTION -->
  <section id="looksgreat">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 text-left">
          <h1>Looks Great On All Devices 
            You Use Every Day</h1>
          <div class="divder-l"></div>
          <p >Our comprehensive feature set is enjoyed by marketing pros, however our intuitive web-based interface is easy enough for anyone to use. </p>
          <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn loadmore btnmargin">Sign Up Now</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn loadmore btnmargin">My Account</a><?php } ?> </div>
        <div class="col-sm-6"> <img src="<?php echo SITE_URL ?>/app/webroot/img/alldevices.png" height="409" width="629" class="img-responsive img-center" /> </div>
      </div>
    </div>
  </section>
  <!--END : LOOKS GREAT SECTION --> 
  <!--START : CAMPAIGN SECTION -->
  <section id="campaign">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 ">
          <h1>Start Your SMS Marketing Campaign Today</h1>
          <div class="divder-w"></div>
          <div class="campain-spcial">
            <ul>
              <li>Unlimited Keywords</li>
              <li>Unlimited Subscribers</li>
              <li>Free Local Phone Number</li>
            </ul>
          </div>
          <?php if(!$this->Session->check('User')){?><a href="<?php echo SITE_URL ?>/users/add" class="btn btn-transparent-white">Get Started</a><?php }else{?><a href="<?php echo SITE_URL ?>/users/profile" class="btn btn-transparent-white">My Account</a><?php } ?> </div>
      </div>
    </div>
  </section>
  <!--END : CAMPAIGN SECTION --> 
  <!--START : SAFE TEXT BOX SECTION -->
  <section id="safebox">
    <div class="container">
      <p>You can be assured your personal information and any SMS messages/voicemails are completely safe with us<br>
        and will <strong>NEVER</strong> be shared with third parties. </p>
    </div>
  </section>
  <!--END : SAFE TEXT BOX SECTION --> 
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
          <!--<div class="translate"> <a href="#" class="btn translate-btn">Translate</a>&nbsp;&nbsp; <a href="#bing" ><img src="<?php echo SITE_URL ?>/app/webroot/img/bing.png" height="21" width="58" /></a></div>-->
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
  <!--END : FOOTER SECTION --> 
  
</div>
<!--END : MAIN --> 

<!--JAVASERIPT FILES--> 
<script src="<?php echo SITE_URL ?>/app/webroot/js/jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo SITE_URL ?>/app/webroot/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="<?php echo SITE_URL ?>/app/webroot/js/smsmarketing.js" type="text/javascript"></script> 
<script>


function openhelpdesk() {
      
window.open('<?php echo SITE_URL ?>/helpdesk',
                '_blank',
                'toolbar=0,status=0,scrollbars=1,width=826,height=636');

            return false;
        }


</script>
<!--JAVASERIPT FILES-->
</body>
</html>
