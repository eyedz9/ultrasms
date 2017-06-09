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
	<p>Please confirm the following before checking out</p>
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
	

	<a class="btn blue" href='https://www.2checkout.com/checkout/purchase?sid=<?php echo $config['Config']['2CO_account_ID']; ?>&quantity=1&product_id=<?php echo $package['Package']['product_id'] ?>&currency_code=<?php echo $currencycode ?>'>Pay Now</a>
		
	<?php/* echo $this->Paypal->button('Pay Now', array('custom' =>$this->Session->read('User.id'),
	'amount' => $package['Package']['amount'],
	'business'=>PAYPAL_EMAIL,
	'notify_url' => SITE_URL.'/paypal_ipn/purchase_credit/'.$package['Package']['id'],
	'return' => SITE_URL.'/users/account_credited',
	'cancel_return' => SITE_URL,
	'item_name' => $package['Package']['name']
	
	));*/
	//,'test' =>true
	?>
	
</div>
</div>
</div>
	