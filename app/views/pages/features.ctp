<style>
#featurescontainer {margin-bottom: 50px;margin-top: 40px;overflow: hidden;}
.col-row {clear: both;display: block;margin-bottom: 15px;margin-top: 15px;padding-bottom: 5px;padding-top: 5px;text-align: center;}
.col-2 {box-sizing: border-box;display: inline-block;padding-left: 10px;padding-right: 10px;text-align: left;vertical-align: top;width: 49.5%;}
#featurescontainer .col-2 .icon {float: left;height: 130px;margin-right: 5px;text-align: left;width: 70px;}
#featurescontainer {
    margin-top: 40px;
}
#featurescontainer .col-row {
    margin: 0;
}
#featurescontainer .col-row .col-2 {
    margin-bottom: 20px;
}
#featurescontainer .col-row .col-2 .icon {
    float: left;
    height: 100px;
    margin-right: 15px;
    width: 45px;
}
#featurescontainer .col-row .col-2 .icon img {
    max-height: 55px;
}
#featurescontainer .col-row .col-2 p {
    max-width: 80%;
    overflow: hidden;
}
@media all and (max-width: 768px) {
#hero {
    padding: 0;
}
#featurescontainer .col-row .col-2 .icon {
    float: none;
    height: auto;
    margin: 0 auto 20px;
    text-align: center;
}
#featurescontainer .col-row .col-2 p {
    max-width: 100%;
}
#featurescontainer h4 {
    text-align:center;
}
#featurescontainer .col-2, .col-3, .col-4 {
    margin-bottom: 50px !important;
    padding-left: 0;
    padding-right: 0;
    text-align: center;
    width: 100% !important;
}
#featurescontainer .col-row .col-2 p {
    max-width: 100%;
}
}
@media all and (max-width: 992px) {
}
@media all and (max-width: 1200px) {
}

h4 {color: #4d4d4d;margin-top:0px;text-align:left;font-size:18px;font-weight:normal}
.leftcolumn {width: 100%;margin-left:0px;}
.rightcolumn {display:none;}
h1 {display:block;text-align:center;font-size:30px;}
/*h4 {text-align:center;color:#448c69;}*/
.wrapper {width: 80%;}
p {font-size:13px;}
.highlight {
    background: #f3f5f6 none repeat scroll 0 0;
    color: #2c3744;
    font-size: 24px;
    font-weight: 300;
    text-align: center;
    text-transform: uppercase;
padding-left:15px;
padding-right:15px;

}
.bg-grey-steel, .bg-hover-grey-steel:hover {
    background: #e9edef none repeat scroll 0 0 !important;
}
.mt-element-ribbon {
    margin-bottom: 30px;
    padding: 25px;
    position: relative;
}
.mt-element-ribbon .ribbon.ribbon-color-success::after {
    border-color: #27a4b0;
}
.mt-element-ribbon .ribbon::after {
    border-color: #62748f;
}
.portlet > .portlet-body p, .table .btn {
    margin-top: 0;
}
.mt-element-ribbon .ribbon-content {
    margin: 0;
    padding-top: 3em;
    font-size:16px;
}
.mt-element-ribbon .ribbon.ribbon-color-success {
    background-color: #286090;
    color: #fff;
    font-size: 18px;
}
.mt-element-ribbon .ribbon.ribbon-shadow {
    box-shadow: 2px 2px 7px rgba(0, 0, 0, 0.4);
}
.mt-element-ribbon .ribbon {
    left: -2px;
    padding: 0.5em 1em;
    position: absolute;
    top: 15px;
    z-index: 5;


</style>



<!--*******************************************-->
<!--<div class="container"  style="z-index:101">-->
    <div class="container" style="margin-top:50px">
      <div class="row">
	<!--<div class="mt-element-ribbon bg-grey-steel">
      <div class="ribbon ribbon-shadow ribbon-color-success uppercase">SMS Marketing Made Easy</div>
      <p class="ribbon-content">Our comprehensive feature set is enjoyed by marketing pros, however our intuitive web-based interface is easy enough for anyone to use.</p>
</div>-->
			<!--<div class="highlight"><h1 style="margin-top: -20px; padding-top: 25px;">SMS Marketing Made Easy!</h1>
			<h4 style="text-align:center;text-align: center; margin-top: -10px; color: #6a829f;margin-bottom:10px ">Comprehensive feature set enjoyed by marketing pros - intuitive web-based interface easy enough for anyone to use.</h4></div>-->
			
			

                <!--<div class="row-fluid" style="margin-top: 30px">
			<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/bulksms64.png" alt="Send Bulk SMS" /></span>
                        <h4>Bulk SMS</h4>
                        <p>At the heart of a SMS marketing campaign is the ability to send SMS messages in bulk to your subscribers. Send messages to your customers to announce deals or discounts you want to promote which can bring in massive business.</p>
                	</div>
                	
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/keyword64.png" alt="SMS marketing keywords" /></span>
                        <?php if(API_TYPE !=2){ ?>
                        <h3>Unlimited Mobile Keywords</h3>
                        <p>The ability for users to engage in keyword marketing. Mobile keywords are an element of mobile marketing campaigns to appeal to a certain target market. You get access to unlimited keywords! People can sign up using these keywords.</p>
                        <?php }else{?>		
                        <h3>Mobile Keyword</h3>
                        <p>The ability for users to engage in keyword marketing. Mobile keywords are an element of mobile marketing campaigns to appeal to a certain target market. People can sign up for your text marketing by texting in a keyword.</p>
                        <?php }?>
                       </div>
           
                       <div class="span3 home-features">
                       <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/bulksmsschedule64.png" alt="Bulk SMS Scheduling" /></span>
                       <h3>Bulk SMS Scheduling</h3>
                       <p>Scheduling messages in intervals is a great way to ensure your customers won't forget about you. We allow you to have full control of when SMS messages get sent out so you can schedule out messages months in advance.</p>
                       </div>
                       <div class="span3 home-features">
                       <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/autoresponders64.png" alt="Autoresponders" /></span>
                       <h3>Autoresponders</h3>
                       <p>After a person joins your subscriber list, automatically send them a custom message back. You can also setup the autoresponders to automatically send SMS back to the subscribers after they subscribe on a preset schedule.</p>
                       </div>
                </div>
                
                <div class="row-fluid" style="margin-top: 30px">
                	<?php if(API_TYPE !=2){ ?>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/mms64.png" alt="Send MMS messages" /></span>
                        <h3>MMS / Picture Messaging</h3>
                        <p>We have added MMS capability! MMS brings the best of email and urgency of SMS to your customer communications. Paint the whole picture with rich media on every mobile phone, with near-100% open rates.</p>
                	</div>
                	<?php }else{?>		
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/emailcapture64.png" alt="Email Capture" /></span>
                    	<h3>Name and Email Capture</h3>
                    	<p>Have the option to collect the name and email from a new subscriber joining your opt-in list! Collect names to personalize your messages and emails if you want to promote to them with email.</p>
                	</div>
                	<?php }?>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/2way64.png" alt="2-way messaging" /></span>
                        <h3>2-Way Messaging</h3>
                        <p>2-way messaging allows existing subscribers to reply to your campaigns, send you text messages, and engage you in 2-way chat. It's a great way to stay connected and answer any your subscribers' questions.</p>
                        </div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/contests64.png" alt="SMS Contests" /></a></span>
                        <h3>Contests</h3>
                        <p>Create SMS contests as a way to engage and reward your customers. An invaluable tool for keeping your current customers happy and engaged and staying with your marketing campaigns.</p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/birthdaywishes64.png" alt="Birthday SMS Wishes" /></span>
                        <h3>Birthday SMS Wishes</h3>
                        <p>Easily collect your contacts' birthdays when they subscribe to your list. Then, on their birthday or even a certain amount of days before, our system will automatically send them your birthday SMS. </p>
                        </div>
                </div>
                
                
                <div class="row-fluid" style="margin-top: 30px">
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/facebook64.png" alt="Facebook Integration" /></span>
                        <h3>Facebook Integration</h3>
                        <p>Ability to share your message to your Facebook page! Spread the word and increase participation among your fans giving you the ability to grow your subscribers.</p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/smsstats64.png" alt="Bulk SMS Delivery Stats" /></span>
                        <h3>Bulk SMS Delivery Stats</h3>
                        <p>View detailed stats like # of successful messages, # of failed messages, and allow the user to delete any subscribers from the list that have failed to receive messages.</p>
                	</div>
              	        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/analytics64.png" alt="Detailed Campaign Analytics" /></a></span>
                        <h3>Campaign Analytics</h3>
                        <p>Track your campaigns to take a deeper look into which keywords are performing the best, detailed SMS logs and new subscribers and unsubscribers over a certain period of time.</p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/widgets64.png" alt="Website Signup Widgets" /></span>
                        <h3>Website Signup Widgets</h3>
                        <p>Allow customers to join a SMS marketing list through a web based form that is placed on a website, providing yet another cost-effective avenue to reach new consumers.</p>
                        </div>
                </div>
                
                <div class="row-fluid" style="margin-top: 30px">
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/polls64.png" alt="SMS Polls" /></span>
                        <h3>Text-to-Vote</h3>
                        <p>Create text-to-vote polls to keep your subscribers engaged and interested in what you have to offer as well as collect valuable information and gain insight into what they want and need from you.</p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/splashbuilder64.png" alt="Mobile Splash Page Builder" /></span>
                        <h3>Mobile Splash Page Builder</h3>
                        <p>Perfect for users that want to create their own pages with video, images, or any HTML and then send those page URLs out to their subscriber list to view. Equipped with a full-featured HTML editor.</p>
                        </div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/apptreminder64.png" alt="Appointment Reminders" /></span>
                        <h3>Appointment Reminders</h3>
                        <p>Schedule and send appointment reminders to your customers ensuring they won't forget about an appointment they made. Search for your contact then easily schedule an SMS to go out to them.</p>
                	</div>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/linkshort64.png" alt="Built In Link Shortening & Tracking" /></a></span>
                    	<h3>Link Shortening & Tracking</h3>
                    	<p>You have the option to shorten your links so they don't take up as many characters in your text messages and also track how many clicks were made for a given link to see how effective your message was.</p>
                        </div>
                </div>
                
                <div class="row-fluid" style="margin-top: 30px">
                	<?php if(API_TYPE !=2){ ?>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/configure64.png" alt="Assign Multiple Long Codes" /></a></span>
                    	<h3>Assign Multiple Long Codes</h3>
                    	<p>Add multiple numbers to a user account. Very useful if you have a large number of opt-in contacts to market to, you can spread your workload across multiple numbers!</p>
                	</div>
                	<?php }else{?>		
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/configure64.png" alt="Shared Short Code" /></a></span>
                    	<h3>Shared Short Code</h3>
                    	<p>Use our memorable shared short code 47711 for enhanced speed and opt for recognizably-branded and a easy to remember number that your customers won't forget.</p>
                	</div>
                	<?php }?>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/emailalerts64.png" alt="Email Alerts" /></span>
                    	<h3>Email Alerts</h3>
                    	<p>Get new subscriber email alerts as they happen or in a daily summary. Also get low credit balance email alerts so you'll always be aware of when to replenish your credits.</p>
                        </div>
                       	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/msgtemplates64.png" alt="Message Templates" /></span>
                        <h3>Message Templates</h3>
                        <p>Save common or often used SMS messages so that you will not have to re-enter the same message repeatedly. Simply select which template to use and have it auto-populate.</p>
                        </div>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/broadcastfromphone64.png" alt="Broadcast From Phone" /></span>
                        <h3>Broadcast From Phone</h3>
                        <p>On the run? No problem at all! You'll have the ability to blast out your SMS marketing campaigns with a simple text message! No need to login to your account! </p>
                        </div>
                </div>
                
                
                <div class="row-fluid" style="margin-top: 30px">
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/qrcodes64.png" alt="QR Codes" /></span>
                    	<h3>QR Codes</h3>
                    	<p>Generate QR codes as a way to bridge your offline marketing campaign to the online medium. Our software comes equipped with SMS, web page URL, v-card, location, email, PayPal, and call QR codes.</p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/groups64.png" alt="Segment Your Contacts" /></span>
                    	<h3>Segment Your Contacts</h3>
                    	<p>With our group segmenting function, you can easily create groups within your text marketing lists. This organizes your contacts into groups and allows you to keep all your subscribers organized!</p>
                        </div>
              
                	
                	<?php if(API_TYPE !=2){ ?>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/voicebroadcast64.png" alt="Voice Broadcasting" /></span>
                    	<h3>Voice Broadcasting</h3>
                    	<p>Broadcast a voice message out to your contacts! Either type in a message and the system will convert the text into voice, or upload your own message via a MP3 file. Another way to communicate with your list.</p>
                	</div>
                	<?php }else{?>	
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/loyalty.png" alt="Loyalty Rewards" /></span>
                    	<h3>SMS Loyalty Rewards</h3>
                    	<p>Forget those archaic and often misplaced paper punch cards. Offer SMS "punch card" loyalty rewards to your customers and build loyalty to your brand to keep customers happy and coming back.</p>
                	</div>                	
                	<?php }?>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/upload64.png" alt="Upload Your Subscriber Lists" /></span>
                    	<h3>Upload Your Subscriber Lists</h3>
                    	<p>Have an opt-in SMS marketing list from somewhere else that you want to migrate over? Provided you have explicit written consent from your subscribers, you can upload your list as well.</p>
                        </div>
                </div>
                
                <div class="row-fluid" style="margin-top: 30px">
                <?php if(API_TYPE !=2){ ?>
                
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/voicemail64.png" alt="Voicemail" /></span>
                    	<h3>Voicemail</h3>
                    	<p>Assign a number that is voice enabled and list this number where ever you wish and listen or read your voicemail directly from your account.</b></p>
                	</div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/smstoemail64.png" alt="SMS to Email/Email to SMS" /></span>
                    	<h3>SMS to Email/Email to SMS</h3>
                    	<p>Ability to get email notices when someone texts in something to your online number(SMS to Email). You can then respond directly to that email from your email client, the system will take that email, turn it around, and text them back(Email to SMS). A great and very useful tool!</p>
                        </div>
               
                <?php }else{?>	
                
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/smstoemail64.png" alt="SMS to Email/Email to SMS" /></span>
                    	<h3>SMS to Email/Email to SMS</h3>
                    	<p>Ability to get email notices when someone texts in something to your online number(SMS to Email). You can then respond directly to that email from your email client, the system will take that email, turn it around, and text them back(Email to SMS). A great and very useful tool!</p>
                        </div>
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/contacts64.png" alt="Contact Management" /></span>
                    	<h3>Contact Management</h3>
                    	<p>Very simple to use contact management system that contains your contacts/subscribers. Search on and manage all your subscribers here.</p>
                        </div>
                
                <?php }?>
           
                	<?php if(API_TYPE !=2){ ?>
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/emailcapture64.png" alt="Email Capture" /></span>
                    	<h3>Name and Email Capture</h3>
                    	<p>Have the option to collect the name and email from a new subscriber joining your opt-in list! Collect names to personalize your SMS messages and emails if you want to promote to them with your email marketing campaigns.</p>
                	</div>
                   
                        <div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/contacts64.png" alt="Contact Management" /></span>
                    	<h3>Contact Management</h3>
                    	<p>Very simple to use contact management system that contains your contacts/subscribers. Search on and manage all your subscribers here.</p>
                        </div>
                     <?php }?>
                </div>
                
                <?php if(API_TYPE !=2){ ?>
                 <div class="row-fluid" style="margin-top: 30px">
                	<div class="span3 home-features">
                        <span class="circle"><img src="<?php echo SITE_URL ?>/app/webroot/img/loyalty.png" alt="Loyalty Rewards" /></span>
                    	<h3>SMS Loyalty Rewards</h3>
                    	<p>Forget those archaic and often misplaced paper punch cards. Offer SMS "punch card" loyalty rewards to your customers and build loyalty to your brand to keep customers happy and coming back.</p>
                	</div>
                </div>	
                <?php }?>
-->

<div id="featurescontainer">
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/bulksms64.png" alt="Send Bulk SMS" /></div>
                        <h4>Bulk SMS</h4>
                        <p>At the heart of a SMS marketing campaign is the ability to send SMS messages in bulk to your subscribers. Send to 1 group or multiple groups at once! Send messages to your customers to announce deals or discounts you want to promote which can bring in massive business. </p>
                	</div>
                    <div class="col-2">
                		<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/keyword64.png" alt="SMS marketing keywords" style="margin-top:-10px;" /></div>
                        <h4>Unlimited Mobile Keywords</h4>
                        <p>The ability for users to engage in keyword marketing. Mobile keywords are an element of mobile marketing campaigns to appeal to a certain target market. You get access to unlimited keywords! People can sign up for your text marketing by texting in a keyword. </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/bulksmsschedule64.png" alt="Bulk SMS Scheduling" /></div>
                        <h4>Bulk SMS Scheduling</h4>
                        <p>Scheduling out messages in intervals is a great way to ensure your customers won't forget about you. Our script allows you to have full control of when SMS messages get sent out so you can schedule out messages months in advance. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/autoresponders64.png" alt="Autoresponders" /></div>
                    <h4>Autoresponders</h4>
                    <p>After a person joins your subscriber list, automatically send them a custom message back. You can also setup the autoresponders to automatically send SMS back to the subscribers after they subscribe on a preset schedule much like email autoresponders work. </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/mms64.png" alt="Send MMS messages" /></div>
                        <h4>MMS / Picture Messaging</h4>
                        <p>We have added MMS capability! MMS brings the best of email and urgency of SMS to your customer communications. Paint the whole picture with rich media on every mobile phone, with near-100% open rates and fast response times.</p>
                	</div>
                    <div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/2way64.png" alt="2-way messaging" /></div>
                        <h4>2-Way Messaging</h4>
                        <p>2-way messaging allows existing subscribers to reply to your campaigns, send you text messages, and engage you in 2-way chat. It's a great way to stay connected and answer any questions your subscribers may have.</p>
                    </div>
                </div>
               	<div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/contests64.png" alt="SMS Contests" /></a></div>
                        <h4>Contests</h4>
                        <p>Create SMS contests as a way to engage and reward your customers. An invaluable tool for keeping your current customers happy and engaged and staying with your marketing campaigns. </p>
                	</div>
                    <div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/birthdaywishes64.png" alt="Birthday SMS Wishes" /></div>
                        <h4>Birthday SMS Wishes</h4>
                        <p>Easily collect your contacts' birthdays when they subscribe to your list. Then, on their birthday or even a certain amount of days before, our system will automatically send them your birthday text message.</p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/facebook64.png" alt="Facebook Integration" /></div>
                        <h4>Facebook Integration</h4>
                        <p>Ability to share your message to your Facebook page! Spread the word and increase participation among your fans giving you the ability to grow your subscribers by sharing your messages on your Facebook account.</p>
                	</div>
                    <div class="col-2">
                		<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/smsstats64.png" alt="Bulk SMS Delivery Stats" /></div>
                        <h4>Bulk SMS Delivery Stats</h4>
                        <p>View detailed stats like # of successful messages, # of failed messages, reason the message failed, and allow the user to delete any subscribers from the list that have failed to receive messages.</p>
                	</div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/analytics64.png" alt="Detailed Campaign Analytics" /></a></div>
                        <h4>Detailed Campaign Analytics</h4>
                        <p>Track your campaigns to take a deeper look into which keywords are performing the best, detailed SMS logs and new subscribers and unsubscribers over a certain period of time.</p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/widgets64.png" alt="Website Signup Widgets" /></div>
                        <h4>Website Signup Widgets</h4>
                        <p>Allow potential customers to join a SMS marketing list through a web based form that is placed on a website. This provides yet another cost-effective avenue to reach and attract new consumers. </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/polls64.png" alt="SMS Polls" /></div>
                        <h4>Polls</h4>
                        <p>Create text-to-vote polls to keep your subscribers engaged and interested in what you have to offer as well as collect valuable information and gain insight into what they want and need from you. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/splashbuilder64.png" alt="Mobile Splash Page Builder" /></div>
                        <h4>Mobile Splash Page Builder</h4>
                        <p>Perfect for users that want to create their own pages with video, images, or any HTML and then send those page URLs out to their subscriber list to view. Equipped with a full-featured HTML editor.</p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/apptreminder64.png" alt="Appointment Reminders" /></div>
                        <h4>Appointment Reminders</h4>
                        <p>Schedule and send appointment reminders to your customers ensuring they won't forget about an appointment they made. Search for your contact then easily schedule an SMS to go out to them. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/linkshort64.png" alt="Built In Link Shortening & Tracking" /></a></div>
                    	<h4>Built In Link Shortening & Tracking</h4>
                    	<p>You have the option to shorten your links so they don't take up as many characters in your text messages and also track how many clicks were made for a given link to see how effective your message was. A very helpful little tool! </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/configure64.png" alt="Assign Multiple Long Codes" /></a></div>
                    	<h4>Assign Multiple Long Codes</h4>
                    	<p>Add multiple numbers to a user account. Very useful if you have a large number of opt-in contacts to market to, you can spread your workload across multiple numbers for better performance and caution! </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/emailalerts64.png" alt="Email Alerts" /></div>
                    	<h4>Email Alerts</h4>
                    	<p>Get new subscriber email alerts as they happen or in a daily summary. Also get low credit balance email alerts so you'll always be aware of when to replenish your credits.</p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/msgtemplates64.png" alt="Message Templates" /></div>
                        <h4>Message Templates</h4>
                        <p>Save common or often used SMS messages so that you will not have to re-enter the same message repeatedly. Simply select which template to use and have it populate the message for you.</p>
                    </div>
                	<div class="col-2">
                        <div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/broadcastfromphone64.png" alt="Broadcast From Phone" /></div>
                        <h4>Broadcast From Phone</h4>
                        <p>On the run? No problem at all! You'll have the ability to blast out your SMS marketing campaigns with a simple text message! No need to login to your account to manage this process. Talk about fast and convenient! </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/qrcodes64.png" alt="QR Codes" /></div>
                    	<h4>QR Codes</h4>
                    	<p>Generate QR codes as a way to bridge your offline marketing campaign to the online medium. Our software comes equipped with new subscriber and web page URL QR codes. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/groups64.png" alt="Segment Your Contacts" /></div>
                    	<h4>Segment Your Contacts</h4>
                    	<p>With our group segmenting function, you can easily create groups within your text marketing lists. This organizes your contacts into groups and allows you to keep organized all your subscribers and where they are coming from!</p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/voicebroadcast64.png" alt="Voice Broadcasting" /></div>
                    	<h4>Voice Broadcasting</h4>
                    	<p>Broadcast a voice message out to your contacts! Either type in a message and the system will convert the text into voice, or upload your own message via a MP3 file. Another great way to communicate and keep your contacts engaged in your offerings. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/upload64.png" alt="Upload Your Subscriber Lists" /></div>
                    	<h4>Upload Your Subscriber Lists</h4>
                    	<p>Have an opt-in SMS list from somewhere else that you want to migrate over? Provided you have explicit written consent from your subscribers that they agreed to receive messages from you, you can upload your list as well. We've made that processes incredibly easy! </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/voicemail64.png" alt="Voicemail/Call Forwarding" /></div>
                    	<h4>Voicemail / Call Forwarding</h4>
                    	<p>Voicemail and call forwarding capability. Have the option to have your calls go directly to voicemail, where you can then listen to them inside the control panel, or have your calls forwarded to any number you wish! </b></p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/smstoemail64.png" alt="SMS to Email/Email to SMS" /></div>
                    	<h4>SMS to Email / Email to SMS</h4>
                    	<p>Get email notices when someone texts in something to your online number(SMS to Email). You can then respond directly to that email from your email client, the system will take that email, and text them back(Email to SMS). A great and very useful tool! </p>
                    </div>
                </div>
                <div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/emailcapture64.png" alt="Helpdesk" /></div>
                    	<h4>Name and Email Capture</h4>
                    	<p>Have the option to collect the name and email from a new subscriber joining your opt-in list! Collect names to personalize your SMS messages and emails if you want to promote to them with your email marketing campaigns. </p>
                	</div>
                    <div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/contacts64.png" alt="Contact Management" /></div>
                    	<h4>Contact Management</h4>
                    	<p>Very simple to use contact management system that contains your contacts/subscribers. Search on and manage all your subscribers here.</p>
                    </div>
                </div>
<div class="col-row">
                	<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/loyalty.png" alt="SMS Loyalty Rewards" /></div>
                    	<h4>SMS Punch Card Loyalty Rewards</h4>
                    	<p>Forget those archaic and often misplaced paper punch cards. Offer SMS "punch card" loyalty rewards to your customers and build loyalty to your brand to keep customers happy and coming back. </p>
                	</div>
<div class="col-2">
                    	<div class="icon"><img src="<?php echo SITE_URL ?>/app/webroot/img/loyaltykiosk64.png" alt="Kiosk Builder" /></div>
                    	<h4>Kiosk Builder</h4>
                    	<p>The digital loyalty kiosk is a cutting edge tool that lets you create an easy to use kiosk display. It provides your on-site customers with a user friendly display – letting them join a mobile club, check-in to a loyalty program, and check their current status. </p>
                    </div>
</div>
                
            </div>
</div></div>
                
            <!--</div>-->