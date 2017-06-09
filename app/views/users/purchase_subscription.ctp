<div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Purchase Subscription
	</div>
</div>

<div class="portlet-body">

<!--<div class="purchase_credit">
	<h2>Purchase Subscription</h2>-->
	<div class="message">Please confirm the following before making your purchase</div>
	<p>Credits for: <b><?php echo $this->Session->read('User.username')?></b></p>
	<p>Item: <b><?php echo $monthlypackage['MonthlyPackage']['package_name']?></b></p>
	<p>Total Cost: <b>
	
	<?php 
	       $currencycode=PAYMENT_CURRENCY_CODE;
			
	       if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
              
              $<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='JPY'){ ?>
              ¥<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='EUR'){ ?>
              €<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='GBP'){ ?>
              £<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
              kr<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='CHF'){ ?>
              CHF<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='BRL'){ ?>
              R$<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='PHP'){ ?>
              ₱<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php } else if($currencycode=='ILS'){ ?>
              ₪<?php echo $monthlypackage['MonthlyPackage']['amount']?>/month</b></p>
              <?php }?>
	
	
              <?php echo $this->Paypal->button('Subscribe', array('type' => 'subscribe', 
                                'term' => 'month', 
                                'period' => '1',
                                'custom' =>$this->Session->read('User.id'),
                                'amount' => $monthlypackage['MonthlyPackage']['amount'],
	                        'business'=>PAYPAL_EMAIL,
	                        'notify_url' => SITE_URL.'/paypal_ipn/purchase_subscription/'.$monthlypackage['MonthlyPackage']['id'],
	                        'return' => SITE_URL.'/users/account_credited',
	                        'cancel_return' => SITE_URL.'/users/paypalpayment',
	                        'currency_code' => PAYMENT_CURRENCY_CODE,
	                        'item_name' => $monthlypackage['MonthlyPackage']['package_name']

              ));
	


	//,'test' =>true
	?>
	
</div>
</div>
</div>