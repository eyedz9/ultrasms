<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Affiliate Center
			<small></small>
		</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Affiliate Center</span>
					</li>
				</ul>                
			</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet light portlet-fit ">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-sitemap font-red"></i>
					<span class="caption-subject font-red sbold uppercase">Affiliate Center</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12">
						<!--div><//?php $i = 0; $class = ' class="altrow"';?></div-->
						<table class="table table-bordered table-striped" id="user" >
							<tbody>
							<tr>            
								<td style="width:100%">
									<span class="text-muted"> 
										<h4>Welcome <?php echo $this->Session->read('User.username')?>!</h4>
										<h4 class="pro-txt"><font size="5">Affiliate Center Payouts:</font>
											<br><br>
											<p class="m-heading-1 border-blue m-bordered">For each user that is referred from <b><?php echo SITE_URL.'?ref='.$this->Session->read('User.id')?></b> and pays to activate their account, you will be rewarded<font color="green"><b> 
											<?php 
											$currencycode=PAYMENT_CURRENCY_CODE;
											if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
												$<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='JPY'){ ?>
												¥<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='EUR'){ ?>
												€<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='GBP'){ ?>
												£<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
												kr<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='CHF'){ ?>
												CHF<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='BRL'){ ?>
												R$<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='PHP'){ ?>
												₱<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php } else if($currencycode=='ILS'){ ?>
												₪<?php echo REFERRAL_AMOUNT?></b></font></p>
												<?php }?>
												<p class="m-heading-1 border-blue m-bordered">For each user that is referred from <b><?php 
												$package=$this->Session->read('User.package');
												if(!empty($package)){
												echo SITE_URL.'?recurring_ref='.$this->Session->read('User.id');
												}else{
												echo 'No Url';
												}
											?></b> and signs up to a monthly subscription package, you will be rewarded <font color="green"><b><?php echo RECURRING_REFERRAL_PERCENT.'%'?></b></font> of that package on a monthly basis for as long as that referred user remains active</p>
											<p class="m-heading-1 border-blue m-bordered">Affiliates are paid every Friday, unless stated otherwise. There are currently <B>NO</B> minimum earning policies for payments.</p>
										</h4>
									</span>
								</td>
							</tr>
							</tbody>
						</table>
						<table class="table table-bordered table-striped" id="user" >
							<tbody>
								<tr>            
									<td style="width:100%">
										<span class="text-muted"> 
											<h4><font size="5">Your Referral URL:</font>
												<input type="text" class="form-control" value="<?php echo SITE_URL.'?ref='.$this->Session->read('User.id')?>"><br/>
												<font size="5">Your Recurring Referral URL:</font>
												<input type="text" class="form-control" value="<?php 
												 $package=$this->Session->read('User.package');
												if(!empty($package)){
												echo SITE_URL.'?recurring_ref='.$this->Session->read('User.id');
												}
												?>">
												<br>
												<p class="m-heading-1 border-blue m-bordered">There is a 7 day tracking cookie. If someone clicks your link & signs up within 7 days of clicking, you get commission. Payments go out every single Friday via Paypal. Your paypal email is set as <b>
												<?php
													if($user['User']['paypal_email'])
													echo $user['User']['paypal_email'];
													else 
													echo 'None';
												?></b>
												 (<a class="" href="<?php echo $this->Html->url(array('controller' =>'users', 'action' =>'edit'))?>"><b>click to change<b></a>).
												<p>
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