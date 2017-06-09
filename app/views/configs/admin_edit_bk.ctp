<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Config' => '/admin/configs/index',
						'Edit Config' => '/admin/configs/edit/1'
					   					   
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
<?php echo $this->Form->create('Config');?>
	<fieldset>
		<legend><?php __('Edit Config'); ?></legend>
		<!--<h3 style="margin-top: -40px">Server Time <?php echo date("Y-m-d h:i:s A",$_SERVER['REQUEST_TIME']);?></h3>
	<p>Calculate the # of hours ahead or behind you are of this server time and put that number in profilestartdate. Attach "-" before the number if behind.</p>
	<p style="padding-top: 15px">Example: If your local time is 10 hours ahead of your server time, then put 10 in the field. If 10 hours behind, then -10(NO space between the - and number).</p>
	<p style="padding-bottom: 15px; padding-top: 15px"><b>NOTE:</b> This field is used to better determine the PayPal profile start date for the monthly subscription packages and is really only a factor if your hosting server is in a different country than where you are. If you know that your hosting server resides in the same country as you, then just leave this field 0.</p>-->
	<?php
	$Option=array('1'=>'PayPal','2'=>'2Checkout','3'=>'Both');
	$Option1=array('1'=>'On','2'=>'Off');
	$Option2=array('AUD'=>'Australian Dollar','BRL'=>'Brazilian Real','GBP'=>'British Pound','CAD'=>'Canadian Dollar','DKK'=>'Danish Krone','EUR'=>'Euro','HKD'=>'Hong Kong Dollar','ILS'=>'Israeli New Shekel','JPY'=>'Japanese Yen','MXN'=>'Mexican Peso','NZD'=>'New Zealand Dollar','NOK'=>'Norwegian Krone','PHP'=>'Philippine Peso','SGD'=>'Singapore Dollar','SEK'=>'Swedish Krona','CHF'=>'Swiss Franc','USD'=>'United States Dollar');
		echo $this->Form->input('id');
		echo $this->Form->input('registration_charge');
		echo $this->Form->input('free_sms');
		echo $this->Form->input('free_voice');
		echo $this->Form->input('paypal_email');
		echo $this->Form->input('site_url');
		echo $this->Form->input('sitename');
		echo $this->Form->input('support_email');
		
		echo $this->Form->input('2CO_account_ID');
		echo $this->Form->input('2CO_account_activation_prod_ID');
		echo $this->Form->input('referral_amount');
		echo $this->Form->input('recurring_referral_percent');
		echo $this->Form->input('twitter');
		echo $this->Form->input('facebook');
                echo $this->Form->input('mobile_page_limit');
		echo $this->Form->input('skype');
		echo $this->Form->input('profilestartdate');
		 echo $form->input('payment_gateway',array('type'=>'select','options'=>$Option));
		  echo $form->input('pay_activation_fees',array('type'=>'select','options'=>$Option1));
  		  echo $form->input('payment_currency_code',array('type'=>'select','options'=>$Option2));
                 
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>