<?php if($this->Session->check('User')):?>
<style>
.sidebarbox li {
    background: #FBFBFB;
}
.sidebarbox li:hover {
    background: #ededed;
    background: url(<?php echo SITE_URL ?>/app/webroot/img/arrow-blue.png);
    background-position: 0 8px;
    background-repeat: no-repeat no-repeat;
   
  
}



</style>
<div class="sidebarbox" style="margin-top:-21px">
	<ul>
	<?php 
	$pay_activation_fee=PAY_ACTIVATION_FEES;
	//if($loggedUser['User']['active']=='0'){
	if($loggedUser['User']['active']=='0' && $pay_activation_fee==1){?>
	<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/dashboard_menu.png) no-repeat scroll 0 2px rgba(0, 0, 0, 0); border-top: #FBFBFB;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Dashboard', true), array('controller' =>'users', 'action' => 'dashboard')); ?></li>
	<?php }else if($loggedUser['User']['assigned_number']=='0'){ ?>
	
	<?php if(API_TYPE==1){?>
	
	<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/smsnumber.png) no-repeat scroll 0 2px rgba(0, 0, 0, 0); border-top: #FBFBFB;">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $this->Html->link(__('Get Number', true), array('controller' =>'nexmos', 'action' => 'searchcountry'), array('class' => 'forgetpass nyroModal','style'=>'color:#ff0000;')); ?></b></li>
	
	<?php }else{?>
	
	<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/smsnumber.png) no-repeat scroll 0 2px rgba(0, 0, 0, 0); border-top: #FBFBFB;">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $this->Html->link(__('Get Number', true), array('controller' =>'twilios', 'action' => 'searchcountry'), array('class' => 'forgetpass nyroModal','style'=>'color:#ff0000;')); ?></b></li>
	
	<?php } ?>
	
	<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/profile_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0); ">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('My Profile', true), array('controller' =>'users', 'action' => 'profile')); ?></li>
<?php }else if($loggedUser['User']['active']=='1'){ ?>

<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/profile_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0); border-top: #FBFBFB;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('My Profile', true), array('controller' =>'users', 'action' => 'profile')); ?></li>

<?php } ?>
		
		
		<!--<li><?php echo $this->Html->link(__('Edit Profile', true), array('controller' =>'users', 'action' => 'edit')); ?></li>
		<li><?php echo $this->Html->link(__('Change Password', true), array('controller' =>'users', 'action' => 'change_password')); ?></li>-->
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/group_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
	<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/contacts_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Contacts', true), array('controller' => 'contacts', 'action' => 'index')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/external_contact.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Import Contacts', true), array('controller' => 'contacts', 'action' => 'upload')); ?> </li>
		
			
				
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/send_sms_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Send Bulk SMS', true), array('controller' => 'messages', 'action' => 'send_message')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/voicebroadcast.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Voice Broadcast', true), array('controller' => 'groups', 'action' => 'broadcast_list')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/templates_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Message Templates', true), array('controller' => 'messages', 'action' => 'smstemplate')); ?> </li>
		<?php if((BITLY_USERNAME !='') && (BITLY_API_KEY !='')){ ?>
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/shortlinks.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Short Links', true), array('controller' => 'users', 'action' => 'shortlinks')); ?> </li>
		<?php } ?>
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/autoresponder_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Auto Responders', true), array('controller' => 'responders', 'action' => 'index')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/polls_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Polls', true), array('controller' => 'polls', 'action' => 'index')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/gift_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Contests', true), array('controller' => 'contests', 'action' => 'index')); ?> </li>
			
			<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/loyalty_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('SMS Loyalty Programs', true), array('controller' => 'smsloyalty', 'action' => 'index')); ?> </li>
			<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/cake.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Birthday SMS Wishes', true), array('controller' => 'birthday', 'action' => 'index')); ?> </li>
		<!--<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/qrcodes_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('QR Codes', true), array('controller' => 'users', 'action' => 'qrcodeindex')); ?> </li>-->
<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/qrcode_menu.png) no-repeat scroll -2px 2px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('QR Codes', true), array('controller' => 'users', 'action' => 'qrcodeindex')); ?> </li>
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/skins_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Mobile Splash Page Builder', true), array('controller' => 'mobile_pages', 'action' => 'pagedetails')); ?> </li>
		
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/widget_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Web Sign-up Widgets', true), array('controller' => 'weblinks', 'action' => 'index')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/reports_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Reports', true), array('controller' => 'users', 'action' => 'reports')); ?> </li>
				
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/log_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Logs', true), array('controller' => 'logs', 'action' => 'index')); ?> </li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/character_encoding.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Non-GSM Character Checker', true), array('controller' =>'messages', 'action' => 'nongsm'), array('class' => 'nyroModal')); ?></li>
		
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/referrals_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('List Referrals', true), array('controller' => 'referrals', 'action' => 'index')); ?> </li>
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/partnership_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0);">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('My Affiliate Page', true), array('controller' => 'users', 'action' => 'affiliates')); ?> </li>
		<li style="background: url(<?php echo SITE_URL ?>/app/webroot/img/sign_out_menu.png) no-repeat scroll -5px -3px rgba(0, 0, 0, 0); border-bottom: #FBFBFB;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>

<h4><span>Statistics</span></h4>
<div class="sidebarbox">
	<p>Primary Number: <b><?php echo $loggedUser['User']['assigned_number'];?></b>
	<?php if(API_TYPE==1){?>
	Number List: (<b><?php echo $this->Html->link('Number List',array('controller' =>'users', 'action' =>'numberlist_nexmo'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
	Timezone: <b><?php echo $loggedUser['User']['timezone'];?></b>
	<?php }else{?>
	Number List: (<b><?php echo $this->Html->link('Number List',array('controller' =>'users', 'action' =>'numberlist_twillio'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
	Timezone: <b><?php echo $loggedUser['User']['timezone'];?></b>
	
	<?php } ?></p>
	Referred Users (activated/paid): <b><?php echo $statistic['referredUser'];?></b><br/>
	Overall Credited Commissions: <b>
<?php 
	       $currencycode=PAYMENT_CURRENCY_CODE;
			
	       if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
              
              $<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='JPY'){ ?>
              ¥<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='EUR'){ ?>
              €<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='GBP'){ ?>
              £<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
              kr<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='CHF'){ ?>
              CHF<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='BRL'){ ?>
              R$<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='PHP'){ ?>
              ₱<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php } else if($currencycode=='ILS'){ ?>
              ₪<?php echo $statistic['overAllCredit'];?></b><br/>
              <?php }?>
	
	Unpaid Commissions: <b>
	<?php
	 if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
              
              $<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='JPY'){ ?>
              ¥<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='EUR'){ ?>
              €<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='GBP'){ ?>
              £<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
              kr<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='CHF'){ ?>
              CHF<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='BRL'){ ?>
              R$<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='PHP'){ ?>
              ₱<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php } else if($currencycode=='ILS'){ ?>
              ₪<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
              <?php }?>
	
	
<?php if($loggedUser['User']['email_alert_credit_options']==0 && $loggedUser['User']['sms_balance'] <= $loggedUser['User']['low_sms_balances']){?>
		
		SMS Credits: <b style="color:red;"> <?php echo $loggedUser['User']['sms_balance']?></b><br>
		
		<?php }else{?>
		SMS Credits:	<b><?php echo $loggedUser['User']['sms_balance']?></b><br>
		<?php } ?>
		

		<?php if($loggedUser['User']['email_alert_credit_options']==0 && $loggedUser['User']['voice_balance'] <= $loggedUser['User']['low_voice_balances']){?>
		
		Voice Credits: <b style="color:red;"> <?php echo $loggedUser['User']['voice_balance']?></b><br>
		
		<?php }else{?>
			Voice Credits:	<b><?php echo $loggedUser['User']['voice_balance']?></b><br>
		<?php } ?>
			
		
		<br/>
		Next Renewal Date: 
		<?php 
		$date=strtotime($loggedUser['User']['next_renewal_dates']); 
		list($year, $month, $day) = explode('-', $loggedUser['User']['next_renewal_dates']); 
		if (checkdate($month,$day,$year)){?>
			
		(<?php echo $datereplace=date('Y-m-d',$date);?>)
		<?php } else { ?>
		<?php echo "(<font style='color: green'><b>None</b></font>)"; }?>
			
		<?php ?>
		<br/>
		
		Past Receipts: (<b><?php echo $this->Html->link('Past Receipts',array('controller' =>'users', 'action' =>'invoices'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
		<br/>
		<br/>
	<?php 
			 $payment=PAYMENT_GATEWAY;
			
			if($payment=='1'){?>
			
			Purchase Credits <?php echo $this->Html->link(__('PayPal', true), array('controller' =>'users', 'action' =>'paypalpayment'),array('class' => 'paypalpayment'))?><br />
			
			
			
			<?php }else if($payment=='2'){ ?>
			
			Purchase Credits <?php echo $this->Html->link('2Checkout', array('controller' =>'users', 'action' =>'checkoutpayment'),array('class' => 'checkoutpayment'))?><br />
			
			
			<?php }else if($payment=='3'){ ?>
			
			Purchase Credits <?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'paypalpayment'),array('class' => 'paypalpayment'))?> or <?php echo $this->Html->link('2Checkout', array('controller' =>'users', 'action' =>'checkoutpayment'),array('class' => 'checkoutpayment'))?><br />
			
			
			<?php } ?>

<br/>
<font color="red">ATTENTION:</font> Web developers, utilize our incredibly simple 
		<b><?php echo $this->Html->link(__('PHP API', true), array('controller' => 'twilios','action' => 'apibox', $loggedUser['User']['id']), array('class' => 'nyroModal', 'style' => 'color:#12759E;')); ?>	</b>
</div>
<?php endif;?>