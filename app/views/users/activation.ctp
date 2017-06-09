	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> Account Activation</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Account Activation</span>
					</li>
				</ul>                
			</div>
			<div class="clearfix"></div>
			<?php echo $this->Session->flash(); ?>
			<div class="portlet light portlet-fit ">
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
					<i class="fa fa-money font-red-sunglo"></i>
			
						<span class="caption-subject font-red sbold uppercase">Account Activation</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered table-striped" id="user" >
								<tbody>
									<tr>            
										<td style="width:100%">
											<span class="text-muted"> 
												<h4>Please confirm the following before checking out!</h4>
												<h4 class="pro-txt"><font size="5">Account activation for:&nbsp;<?php echo $this->Session->read('User.username')?></font>
													<br><br>
													<p class="m-heading-1 border-blue m-bordered">Total cost:<b> 
													<?php 
														$currencycode=PAYMENT_CURRENCY_CODE;
														if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
														$<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='JPY'){ ?>
														¥<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='EUR'){ ?>
														€<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='GBP'){ ?>
														£<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
														kr<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='CHF'){ ?>
														CHF<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='BRL'){ ?>
														R$<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='PHP'){ ?>
														₱<?php echo REGISTRATION_CHARGE?></b></p>
														<?php } else if($currencycode=='ILS'){ ?>
														₪<?php echo REGISTRATION_CHARGE?></b></p>
														<?php }?>		
														<font size="2">* includes <?php echo FREE_SMS?> text message credits & <?php echo FREE_VOICE?> voice credits!</font><br/>	
														<?php if($id=='1'){
														?>	<br/>
														<?php echo $this->Paypal->button('Pay Now', array('custom' =>$this->Session->read('User.id'),
														'amount' => REGISTRATION_CHARGE,
														'business'=>PAYPAL_EMAIL,
														'notify_url' => SITE_URL.'/paypal_ipn/process',
														'return' => SITE_URL.'/users/thank_you',
														'cancel_return' => SITE_URL,
														'currency_code' => PAYMENT_CURRENCY_CODE,
														'item_name' => SITE_URL.' Membership'	
														));
														?>
														<?php }else if($id=='2'){ ?>
<br/>
															<form action="" method="post" id="signupForm" name="signupForm">
																<button class="btn blue" id="customButton">Pay Now</button>
															</form>




														<?php }?>
												</h4>
											</span>
										</td>
									</tr>
								</tbody>
							</table>						
						</div>
					</div>     
				</div>
			</div>
		</div>
	</div>
<script src="https://checkout.stripe.com/checkout.js"></script>
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
		document.getElementById('customButton').addEventListener('click', function(e) {
			// Open Checkout with further options:
			handler.open({
				name: '<?php echo SITENAME;?>',
				description: 'Activate Account',
                                currency: '<?php echo PAYMENT_CURRENCY_CODE;?>',
				amount: <?php echo REGISTRATION_CHARGE * 100;?>,
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