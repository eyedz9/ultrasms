	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> Dashboard</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
									<a href="">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Dashboard </span>
					</li>
				</ul>  			
			</div>
			<?php echo $this->Session->flash(); ?>				
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet light ">
						<div class="portlet-body">
							<h2>Welcome <?php echo $this->Session->read('User.first_name')?>!</h2><br>
							<h3>You logged in successfully, now you need to activate your account! <!--(<?php echo $this->Html->link('Affiliates', array('controller' =>'users', 'action' =>'affiliates'))?>)--></h3><br>
							<p style="margin-bottom:30px">Activating your account costs a one time fee of <b>			
								<?php 
									$currencycode=PAYMENT_CURRENCY_CODE;
									if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
									$<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='JPY'){ ?>
									¥<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='EUR'){ ?>
									€<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='GBP'){ ?>
									£<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
									kr<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='CHF'){ ?>
									CHF<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='BRL'){ ?>
									R$<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='PHP'){ ?>
									₱<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php } else if($currencycode=='ILS'){ ?>
									₪<?php echo REGISTRATION_CHARGE?></b> which includes your
									<?php }?>
									<b>own dedicated phone number</b>, <b><?php echo FREE_SMS?> free SMS credits</b> and <b><?php echo FREE_VOICE?> free voice credits</b>! </p>
									<!--h2 style="font-size: 23px;">Activate account with 2checkout by <?php //echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation'))?>.</h2><br /-->
									<?php 
									$payment=PAYMENT_GATEWAY;
									if($payment=='1'){?>
									<div align="">
										<h2 style="font-size: 23px;">Activate account with PayPal by <?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'activation/'.$payment),array('escape' =>false))?></h2><br />
										
									</div>
									<?php }else if($payment=='2'){ ?>
									<div align="">
										<h2 style="font-size: 23px;">Activate account with Credit Card by <?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'activation/'.$payment),array('escape' =>false))?></h2><br />
										
									</div>
									<?php }else if($payment=='3'){ ?>
									<div align="">
										<h2>Activate account with <?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'activation/1'),array('escape' =>false))?> or <?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'activation/2'),array('escape' =>false))?></h2><br />
										
									</div>
								<?php } ?>

								<div align="">
									<p>Immediately after payment you can select your phone number & get started within seconds. (Simply refresh this page after purchase confirmation)</p>
								</div>
						</div>
					</div>
				</div>                       
			</div>	
		</div>                       
	</div>