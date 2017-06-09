<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> <?php __('Confirm your payment');?>
			<small></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.html">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span><?php __('Confirm your payment');?></span>
				</li>
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>			
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-money font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> <?php __('Confirm your payment');?>
					</span> 
				</div>
			</div>
			<div class="portlet-body form">
				<div class="signup1">
					<div class="feildbox">
						Please check your details:
					</div>
					<div class="feildbox" style="margin-top:10px">
						<b>Name:</b> <?php echo $response['FIRSTNAME']." ".$response['LASTNAME'] ?>
					</div>
					<div class="feildbox">
						<b>Email:</b> <?php echo $response['EMAIL']; ?>
					</div>
					<div class="feildbox">
						<b>Package:</b> <?php echo $response['package_name']; ?> 
					</div>
					<div class="feildbox" style="margin-top:10px; margin-bottom: 10px">
						<font color="green"><b>You will pay 
							<?php 
							$currencycode=PAYMENT_CURRENCY_CODE;
							if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
							$<?php echo $response['AMT']; ?> on this day of every month.</b>
						</font>
							<?php } else if($currencycode=='JPY'){ ?>
							¥<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='EUR'){ ?>
							€<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='GBP'){ ?>
							£<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
							kr<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='CHF'){ ?>
							CHF<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='BRL'){ ?>
							R$<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='PHP'){ ?>
							₱<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php } else if($currencycode=='ILS'){ ?>
							₪<?php echo $response['AMT']; ?> on this day of every month.</b></font>
							<?php }?>
					</div>
					<?php echo $this->Form->create('User',array('action'=> 'order_confirm','name'=>'signupForm','id'=>'signupForm','method'=>'post'));?>
					<div class="form-body">
						<!--input type="hidden" name="amount" value="<?php echo number_format($response['AMT'], '', '.', '');  ?>"-->
						<input type="hidden" name="amount" value="<?php echo $response['AMT'];  ?>">
						<input type="hidden" name="package_name" value="<?php echo $response['package_name'];  ?>">
						<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>">
						<input type="hidden" name="recurring_email" value="<?php echo $response['EMAIL']; ?>">
					</div>
					<div class="form-actions">
						<?php echo $this->Form->submit('CONFIRM',array('div'=>false,'class'=>'btn blue'));?>
					</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>                     
	</div>
</div>         