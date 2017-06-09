<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Paypal' => '/admin/paypals/index',
						'Edit Paypal' => '/admin/paypals/edit/1'
					   					   
				);				
				$matchingLinks = array();
				
				foreach ($navigation as $link) {
						if (preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base)))) {
								$matchingLinks[strlen($link)] = $link;
						}
				}
				
				krsort($matchingLinks);
				
				$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
				$out = array();
				
				foreach ($navigation as $title => $link) {
						$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
				}
				
				echo join("\n", $out);
				?>			
</ul>
<div class="configs form">
<?php echo $this->Form->create('Paypal');?>
	<fieldset>
		<legend><?php __('Edit PayPal'); ?></legend>
		<!--<h3 style="margin-top: -40px">Server Time <?php echo date("Y-m-d h:i:s A",$_SERVER['REQUEST_TIME']);?></h3>
	<p>Calculate the # of hours ahead or behind you are of this server time and put that number in profilestartdate. Attach "-" before the number if behind.</p>
	<p style="padding-top: 15px">Example: If your local time is 10 hours ahead of your server time, then put 10 in the field. If 10 hours behind, then -10(NO space between the - and number).</p>
	<p style="padding-bottom: 15px; padding-top: 15px"><b>NOTE:</b> This field is used to better determine the PayPal profile start date for the monthly subscription packages and is really only a factor if your hosting server is in a different country than where you are. If you know that your hosting server resides in the same country as you, then just leave this field 0.</p>-->
	

<p style="padding-bottom: 15px"><b>NOTE:</b> You will need to obtain PayPal API credentials if you want to setup <b>monthly subscription</b> packages in the software.<br/><br/>

Follow the below steps:<br/>

1. Go to https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run and login with your PayPal credentials<br/>
2. Enter your API Username, API Password, and API Signature below<br/>
3. Submit changes

</p>
<br/>


<?php
	$Option=array('1'=>'PayPal','2'=>'2Checkout','3'=>'Both');
	$Option1=array('1'=>'On','2'=>'Off');
	$Option2=array('AUD'=>'Australian Dollar','BRL'=>'Brazilian Real','GBP'=>'British Pound','CAD'=>'Canadian Dollar','DKK'=>'Danish Krone','EUR'=>'Euro','HKD'=>'Hong Kong Dollar','ILS'=>'Israeli New Shekel','JPY'=>'Japanese Yen','MXN'=>'Mexican Peso','NZD'=>'New Zealand Dollar','NOK'=>'Norwegian Krone','PHP'=>'Philippine Peso','SGD'=>'Singapore Dollar','SEK'=>'Swedish Krona','CHF'=>'Swiss Franc','USD'=>'United States Dollar');
		echo $this->Form->input('id');
		echo $this->Form->input('paypal_api_username');
		echo $this->Form->input('paypal_api_password');
		echo $this->Form->input('paypal_api_signature')           
		?>


PayPal IPN Notification setup
===============================================================================================================	
<br/>
If you create MONTHLY PayPal subscription packages in the admin, then you need to set an IPN notification URL in PayPal so that 
credits are properly processed every month for that user after you receive payment. In PayPal, go to your Profile. Then 
click on "My Selling Tools". On the right, you will see "Instant Payment Notifications". Click that and enter:
<br/><br/>
http://YOURSITEURL/users/recurringpayment 
<br/><br/>**YOURSITEURL is the complete path the script is installed at
<br/><br/>
Make sure it is also enabled.
<br/>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>