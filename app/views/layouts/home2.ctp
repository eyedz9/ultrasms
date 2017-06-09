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
<?
echo $this->Html->css('style');
echo $this->Html->css('cycle');
echo $this->Html->script('jquery');
echo $this->Html->script('jQvalidations/jquery.validate');
echo $this->Html->script('jQvalidations/jquery.validation.functions');
echo $this->Html->script('jquery.cycle.all');
?>    
<style>
@import url(http://fonts.googleapis.com/css?family=Source+Sans Pro:200italic,200,300italic,300,400italic,400,600italic,600,700italic,700,900italic,900);

body {margin:0;padding:0;background:#fff}
img {display:block;}
.text {font-family:'Source Sans Pro';font-size:16px;color:#171a1c;line-height:23px;}
//.text {font-family: 'verdana';font-size:15px;color:#171a1c;line-height:23px;}
.head1 {font-family:'verdana';font-size:18px;font-weight:300;color:#171a1c;line-height:23px;}
.head2 {font-family:'Source Sans Pro';font-size:18px;font-weight:600;color:#7C5800;line-height:19px;
text-shadow: 1px 1px 0px rgba(255,255,255, 0.8);margin-top:22px;}
.head3 {font-family:'Source Sans Pro';font-size:21px;font-weight:300;color:#fff;line-height:19px;
text-shadow: 1px 1px 0px rgba(0,0,0, 0.4);margin-top:12px;margin-bottom:11px;}
.menu-b {font-family:'Source Sans Pro';font-size:13px;font-weight:300;color:#fff;line-height:19px;
margin-top:12px;margin-bottom:11px;text-shadow: 1px 1px 0px rgba(0,0,0, 0.2);}
.text1 {font-family:'verdana';font-size:13px;font-weight:400;color:#171a1c;line-height:18px;margin-top:8px;}
//.text1 {font-family:'verdana';font-size:14px;font-weight:400;color:#171a1c;line-height:18px;margin-top:8px;}
.pic {padding-right:20px;padding-top:5px;text-align:right;}
.thumb {border:0px solid #bab38e;text-align:center;margin:15px;
-webkit-box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.4);
-moz-box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.4);
box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.4);}

.logo {font-family:'Source Sans Pro';font-size:21px;font-weight:300;color:#fff;line-height:23px;}
.menu a {font-family:'Source Sans Pro';font-size:15px;font-weight:bold;color:#fff;line-height:23px;text-transform:uppercase;text-decoration:none;padding-top:2px;text-shadow: 1px 1px 0px rgba(0,0,0, 5);}
.menu a:hover {color:#fce13c;}
.menu2 a {font-family:'Source Sans Pro';font-size:12px;font-weight:300;color:#fff;line-height:23px;text-transform:uppercase;text-decoration:none;padding-top:2px;}
.menu2 a:hover {color:#cacaca;}
a {color:#ffc438;}

</style>
    
</head>
<body>
    
    
<div style="background:url(./img/x_02.jpg) top repeat-x;height:57px;">
    
<center style="padding-top: 5px"><table cellspacing=0 cellpadding=0 style="padding-top:11px;" width=968 border=0>
<tr>
        <td width=200><?php echo $this->Html->link($this->Html->image('logo.png'), '/', array('escape' =>false, 'class' =>'logo'));?></td>
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
				
				<td width=620 align=right><div class=menu>
					<?php echo $this->Html->link('Home','/')?>&nbsp;&nbsp;&nbsp;&nbsp; 
					<?php echo $this->Html->link('About',array('controller' => 'users', 'action' =>'about'))?>&nbsp;&nbsp;&nbsp;&nbsp; 
					<?php if(!$this->Session->check('User')):?>
					<?php echo $this->Html->link('Login',array('controller' => 'users', 'action' =>'login'))?>
					<?php else:?>
					<?php echo $this->Html->link('My Account',array('controller' => 'users', 'action' =>'profile'))?>&nbsp;&nbsp;&nbsp;&nbsp; 
					<?php echo $this->Html->link('Logout',array('controller' => 'users', 'action' =>'logout'))?>
					<?php endif;?></td>
					<!--<td>
            <div style="padding-left:22px;position:relative;bottom:1px;"><a href="<?php echo SITE_URL ?>/helpdesk"><img src="./img/hdsupport.jpg"/></a>
		</div>
        </td>-->
        <td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td><div id='MicrosoftTranslatorWidget' class='Light' style='color:white;background-color:#555555;margin-top:-5px'></div><script type='text/javascript'>setTimeout(function(){{var s=document.createElement('script');s.type='text/javascript';s.charset='UTF-8';s.src=((location && location.href && location.href.indexOf('https') == 0)?'https://ssl.microsofttranslator.com':'http://www.microsofttranslator.com')+'/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**&ctf=True&ui=true&settings=Manual&from=en';var p=document.getElementsByTagName('head')[0]||document.documentElement;p.insertBefore(s,p.firstChild); }},0);</script></td>  
    </tr>		   
</table></center>      
</div>    
    
    
<center><table cellspacing=0 cellpadding=0 style="margin-top:13px;">
<tr>
    <td colspan=2><img src="./img/x_06.jpg"></td>
</tr>
<tr>
    <td><?php if(!$this->Session->check('User')):
				echo $this->Html->link($this->Html->image('x_08.jpg'), array('controller' =>'users', 'action' => 'add'), array('escape' =>false));
				else:
				echo $this->Html->link($this->Html->image('x_08.jpg'), array('controller' => 'users', 'action' =>'profile'), array('escape' =>false));
endif;?>				</td>
    <td><img src="./img/x_09.jpg"></td>
</tr>
<TR>
    <td colspan=2><img src="./img/x_10.jpg"><div style="text-align: center; margin-left: -265px; margin-top: -30px;font-weight:bold;color:#830202;"><?php echo FREE_SMS ?></div> </td>
</TR>
</Table></center>

   
<center><table cellspacing=0 cellpadding=0 style="margin-top:15px;" width=978>
<tr>
    <td colspan=4><div class="loginner" style="margin-top: 30px; color: #035172; padding: 20px; line-height: 1.5">
<?php echo SITENAME ?>'s platform is very easy to use, but don't mistake ease of use for lack of power and functionality. Starting a SMS marketing campaign shouldn't be time-consuming and difficult. Our comprehensive feature set we offer will be enjoyed by marketing pros, however our intuitive web-based interface is easy for anyone to use. Whether you're looking to market your business, launch text-2-join campaigns, create polls and contests, or simply get in touch with all of your contacts at once, our SMS marketing service allows you to do so quickly and easily.
    </div></td>
</tr>
<TR>
    <td valign=top>
        <center><table cellspacing=0 cellpadding=0 width=303 style="margin-top:53px;">
            <tr>
                <td><img src="./img/x_16.jpg"></td>
            </tr>
            <Tr>
                <td background="./img/s_25.jpg">
                    <center>
                        
                    <div class=head2>Contacts</div>    
                    <a href="./img/s1.jpg"><img src="./img/s1-s.jpg" class=thumb></a>
                        
                    <div class=head2>Groups</div>       
                    <a href="./img/s2.jpg"><img src="./img/s2-s.jpg" class=thumb></a>
                        
                    <div class=head2>Bulk SMS</div>       
                    <a href="./img/s3.jpg"><img src="./img/s3-s.jpg" class=thumb></a>
                        
                    <div class=head2>Logs</div>       
                    <a href="./img/s4.jpg"><img src="./img/s4-s.jpg" class=thumb></a><BR>
                    
                    </center>
                </td>
            </Tr>
        </Table></center>
    </td>
    <td width=30></td>
    <TD valign=top>
        <center><table cellspacing=0 cellpadding=0 style="margin-top:53px;">
        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_38.jpg" class=pic></td>
                        <td><div class=head1>Keyword Marketing</div>
                            <div class=text1>Keywords allow people to join your subscriber list by texting that keyword to a number. We allow unlimited keywords.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=35></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_19.jpg" class=pic></td>
                        <td><div class=head1>Bulk SMS Scheduling</div>
                            <div class=text1>Have full control of when your SMS messages get sent out. Schedule them at any date and time and view what's in the scheduled queue.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        
        <TR><td height=58></td></TR>

        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_23.jpg" class=pic></td>
                        <td><div class=head1>Bulk SMS</div>
                            <div class=text1>At the heart of a SMS marketing campaign is the ability to send SMS messages in bulk to your subscribers.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=30></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_41.jpg" class=pic></td>
                        <td><div class=head1>AutoResponders</div>
                            <div class=text1>After a person joins your subscriber list, votes in a poll, or enters a contest, automatically send them a custom message back.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        
        <TR><td height=58></td></TR>

        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_33.jpg" class=pic></td>
                        <td><div class=head1>Campaign Analysis</div>
                            <div class=text1>Track your campaigns and take a look into keyword performance, SMS logs, and new subscribers.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=35></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/x_30.jpg" class=pic></td>
                        <td><div class=head1>Contact Management</div>
                            <div class=text1>Manage and import your contacts, collect a directory of personal and professional contacts and engage your audience for sales.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        
        <TR><td height=58></td></TR>

        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/qr.png" class=pic></td>
                        <td><div class=head1>QR Codes</div>
                            <div class=text1>QR (short for 'Quick Response') Codes are two dimensional barcodes which help bridge offline advertising to the online medium.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=30></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/contest.png" class=pic></td>
                        <td><div class=head1>SMS Contests</div>
                            <div class=text1>Engage and reward your loyal customers by creating SMS contests. People enter your contest via text message and the system randomly picks a winner.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        
        <TR><td height=58></td></TR>

        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/poll.png" class=pic></td>
                        <td><div class=head1>Polling/Text-to-Vote</div>
                            <div class=text1>Create polls to collect valuable information and as another way to keep your subscribers engaged.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=30></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/voicemail.png" class=pic></td>
                        <td><div class=head1>Voicemail</div>
                            <div class=text1>Receive voicemails. Either listen or even read them directly from your account since we transcribe those voicemails into text format!
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        
        <TR><td height=58></td></TR>

        <tr>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/list.png" class=pic></td>
                        <td><div class=head1>Web Opt-In Widgets</div>
                            <div class=text1>Create web based opt-in widgets to allow potential customers to join a list through a web based form.
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
            <td width=30></td>
            <td>
                <center><table cellspacing=0 cellpadding=0>
                    <tr>
                        <td valign=top width=70 align=right><img src="./img/mobile.png" class=pic></td>
                        <td><div class=head1>Mobile Page Builder</div>
                            <div class=text1>Create your own mobile web pages with video, images, or any HTML and then send those page URLs to your list. Perfect for event information!
                            </div>
                        </td>
                    </tr>
                </table></center>
            </td>
        </tr>
        </table></center>
    </td>
</Tr>    
            
</Table></center>

<BR><BR><BR>
<center>

<?php if(!$this->Session->check('User')):{
echo $this->Html->link($this->Html->image('x_46.jpg'), array('controller' => 'pages', 'action' =>'pricingplans'), array('escape' =>false)); 
}else:
  $payment=PAYMENT_GATEWAY;
  if($payment=='1' || $payment=='3'){
     echo $this->Html->link($this->Html->image('x_46.jpg'), array('controller' =>'users', 'action' =>'paypalpayment'), array('escape' =>false)); 
 }else if($payment=='2'){
     echo $this->Html->link($this->Html->image('x_46.jpg'), array('controller' =>'users', 'action' =>'checkoutpayment'), array('escape' =>false));
 } endif;?>

</center>

<BR><BR><BR><BR><BR>
 



<div style="background:url(./img/x_49.jpg) top repeat-x;height:200px;">
    
<center><table cellspacing=0 cellpadding=0 style="padding-top:11px;" width=968 border=0>
    <tr>
        <td valign=top width=340><div class=head3>Contact Us</div>
        <div class=menu-b>Questions? Comments? Feedback? You can email
us directly at <a href="mailto:<?php echo SUPPORT_EMAIL ?>"><?php echo SUPPORT_EMAIL ?></a>. 
Issues? Submit your issue with our <a href="<?php echo SITE_URL ?>/helpdesk">Helpdesk</a>.
We will respond as soon as possible.
        </div>
        <table><tr><td><a href="<?php echo SITE_URL ?>/helpdesk"><img src="<?php echo SITE_URL ?>/app/webroot/img/hd1.jpg" style="width:160px"/></a></td><td>&nbsp;&nbsp;</td><td><a href="mailto:<?php echo SUPPORT_EMAIL ?>"><img src="<?php echo SITE_URL ?>/app/webroot/img/hd2.jpg" style="width:160px"/></a></td></tr></table>
	</td>
        <td width=20></td>
        <td valign=top width=340><div class=head3>Privacy</div>
        <div class=menu-b>You can be assured your personal information and any sent / received messages & voicemails are completely safe with us and will NEVER be shared with third parties.
        <!--<BR><img src="<?php echo SITE_URL ?>/app/webroot/img/paypal2co.gif" style="margin-top:9px;">-->
        <!--<BR><img src="<?php echo SITE_URL ?>/app/webroot/img/paypal-2co.png" style="margin-top:9px;">-->
        </div></td>
        <td width=20></td>
        <td valign=top width=240><div class=head3>Connect With Us</div>
        <div class=menu-b>
            <table cellspacing=3><TR><TD><a href="#" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/facebook.png"></a></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><TD><a href="#" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/twitter.png"></a></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><TD><a href="#" target="_blank"><img src="<?php echo SITE_URL ?>/app/webroot/img/linkedin.png"></a></td></tr></table>
        <img src="<?php echo SITE_URL ?>/app/webroot/img/paypal-2co.png" style="margin-top:9px;"></div></td>
    </tr>
</table></center>   
    
</div> 
    

<div style="background:url(./img/x_61.jpg) top repeat-x;height:47px;">
    <div style="padding:11px;width:968px;text-align:center;font-size:13px;margin-left:159px;color: #1B3A5C;font-size: 14px;font-family:Source Sans Pro;font-weight:bold;"><?php echo SITENAME ?> is a 100% opt-in and SPAM-FREE service. Please see our Anti-Spam Policy to learn about our position on SPAM and how it's handled.</div>
    
</div>    
    

<div style="background:url(./img/x_62.jpg) top repeat-x;height:41px;">
    <div class=menu2 style="padding:8px;width:968px;margin-left:185px;font-size:11px;">
        <a href="<?php echo $this->Html->url('/')?>">Home</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'about'))?>">About</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'terms_conditions'))?>"> Terms and Conditions</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'privacy_policy'))?>">Privacy Policy</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'faq'))?>">FAQ</a> | <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'antispampolicy'))?>">Anti-Spam Policy</a></div>
                
    </div>
    
</div>  
<script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js"></script>    
</body>
</html>
