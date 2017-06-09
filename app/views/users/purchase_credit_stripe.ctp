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
              <?php } ?>
				<form action="<?php echo SITE_URL; ?>/users/purchase_credit_stripe/<?php echo $package['Package']['id']?>" method="post" id="signupForm" name="signupForm">
					<button class="btn blue" id="customButtonpay">Pay Now</button>
				</form>
			</div>
		</div>
	</div>
	<script>
		var handler = StripeCheckout.configure({
			key: '<?php echo PUBLISHABLE_KEY;?>',
			locale: 'auto',
			image: '<?php echo SITE_URL?>/app/webroot/img/security.png',
			token: function(token) {
				var form$ = $("#signupForm");
				var token = token.id;
				form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
				form$.get(0).submit();
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
			}
		});
		document.getElementById('customButtonpay').addEventListener('click', function(e) {
			// Open Checkout with further options:
			handler.open({
				name: '<?php echo SITENAME;?>',
				description: '<?php echo $package['Package']['name']; ?>',
				currency: '<?php echo PAYMENT_CURRENCY_CODE;?>',
				amount: <?php echo $package['Package']['amount'] * 100;?>,
				zipCode: true,
				billingAddress: true
			});
			e.preventDefault();
		});
		// Close Checkout on page navigation:
		window.addEventListener('popstate', function() {
			handler.close();
		});
	</script>