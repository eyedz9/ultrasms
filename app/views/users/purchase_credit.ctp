<div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Purchase Credits
	</div>
</div>

<div class="portlet-body">

<!--<div class="purchase_credit">
	<h2>Purchase Credits</h2>-->
	<div class="message">Please confirm the following before making your purchase</div>
	<p>Credits for: <b><?php echo $this->Session->read('User.username')?></b></p>
	<p>Item: <b><?php echo $package['Package']['name']?></b></p>
	<p>Total Cost: <b>
	
	<?php 
	       $currencycode=PAYMENT_CURRENCY_CODE;
			
	       if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
              
              $<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='JPY'){ ?>
              ¥<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='EUR'){ ?>
              €<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='GBP'){ ?>
              £<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
              kr<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='CHF'){ ?>
              CHF<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='BRL'){ ?>
              R$<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='PHP'){ ?>
              ₱<?php echo $package['Package']['amount']?></b></p>
              <?php } else if($currencycode=='ILS'){ ?>
              ₪<?php echo $package['Package']['amount']?></b></p>
              <?php }?>
	
	<?php echo $this->Paypal->button('Pay Now', array('custom' =>$this->Session->read('User.id'),
	'amount' => $package['Package']['amount'],
	'business'=>PAYPAL_EMAIL,
	'notify_url' => SITE_URL.'/paypal_ipn/purchase_credit/'.$package['Package']['id'],
	'return' => SITE_URL.'/users/account_credited',
	'cancel_return' => SITE_URL.'/users/paypalpayment',
	'currency_code' => PAYMENT_CURRENCY_CODE,
	'item_name' => $package['Package']['name']
	
	));
	


	//,'test' =>true
	?>
	
</div>
</div>
</div>
	