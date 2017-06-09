<div class="page-content-wrapper">
  <div class="page-content">
    <h3 class="page-title">Packages<img style="float:right;margin-top:-7px" src="<?php echo SITE_URL; ?>/app/webroot/img/poweredbystripe.png"></h3>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <i class="icon-home"></i> <a href="<?php echo SITE_URL; ?>/users/dashboard">Home</a> <i class="fa fa-angle-right"></i> </li>
        <li> <span>Packages</span> </li>
      </ul>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->Session->flash(); ?>
    <div class="portlet light portlet-fit ">
      <div class="portlet-title">
        <div class="caption"> <i class="fa fa-cart-plus font-blue"></i> <span class="caption-subject font-blue bold uppercase">MONTHLY PACKAGES</span> </div>
      </div>
      <div class="portlet-body">
        <div class="pricing-content-1">
          <div class="row">
		  <?php 
		  foreach ($monthlydetails as $monthlydetail){ ?>
            <div class="col-md-4">
              <div class="price-column-container border-active">
                <div class="price-table-head bg-blue">
                  <h2 class="no-margin"><?php echo ucfirst($monthlydetail['MonthlyPackage']['package_name']);?></h2>
                </div>
                <div class="arrow-down border-top-blue"></div>
                <div class="price-table-pricing">
					<?php 
					$currencycode=PAYMENT_CURRENCY_CODE;
					if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){ ?>
						<h3><span class="price-sign">$</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='JPY'){ ?>
						<h3><span class="price-sign">¥</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='EUR'){ ?>
						<h3><span class="price-sign">€</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='GBP'){ ?>
						<h3><span class="price-sign">£</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
						<h3><span class="price-sign">kr</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='CHF'){ ?>
						<h3><span class="price-sign">CHF</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<h3><?php } else if($currencycode=='BRL'){ ?>
						<h3><span class="price-sign">R$</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='PHP'){ ?>
						<h3><span class="price-sign">₱</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='ILS'){ ?>
						<h3><span class="price-sign">₪</span><?php echo $monthlydetail['MonthlyPackage']['amount'];?></h3><p>per month</p>
					<?php } ?>
                </div>
                <div class="price-table-content">
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"> <i class="fa fa-comment"></i> </div>
                    <div class="col-xs-9 text-left mobile-padding"><?php echo $monthlydetail['MonthlyPackage']['text_messages_credit'];?> Text Messages</div>
                  </div>
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"> <i class="fa fa-bullhorn"></i> </div>
                    <div class="col-xs-9 text-left mobile-padding"><?php echo $monthlydetail['MonthlyPackage']['voice_messages_credit'];?> Voice Messages</div>
                  </div>
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"> <i class="fa fa-key"></i> </div>
                    <div class="col-xs-9 text-left mobile-padding">Unlimited Keywords</div>
                  </div>
                </div>
                <div class="arrow-down arrow-grey"></div>
                <div class="price-table-footer">
					<!--<?php echo $this->Form->create('User',array('action'=> 'paypalpayment/'.$this->Session->read('User.id'),'name'=>'signupForm','id'=>'signupForm'));?>
						<input type="hidden" name="data[MonthlyPackage][packageid]" value="<?php echo $monthlydetail['MonthlyPackage']['id']?>">
						<input type="hidden" name="data[User][id]" value="<?php echo $this->Session->read('User.id'); ?>">
						<input type="hidden" name="data[User][amount]" value="<?php echo $monthlydetail['MonthlyPackage']['amount']?>">
						<input type="hidden" name="data[User][package_name]" value="<?php echo $monthlydetail['MonthlyPackage']['package_name']?>">
						<button type="submit" class="btn green btn-outline sbold uppercase price-button">Sign Up</button>
					<?php echo $this->Form->end();?>-->

                
<a class="nyroModal" href="<?php echo SITE_URL; ?>/users/purchase_subscription_stripe/<?php echo $monthlydetail['MonthlyPackage']['id']?>"><button type="button" class="btn blue btn-outline sbold uppercase price-button">Confirm</button></a>

                </div>
              </div>
            </div>
			<?php } ?>
          </div>
        </div>
      </div>
    </div>
	<div class="portlet light portlet-fit ">
      <div class="portlet-title">
        <div class="caption"> <i class="fa fa-comment font-red"></i> <span class="caption-subject font-red bold uppercase">SMS ADD-ON PACKAGES</span> </div>
      </div>
      <div class="portlet-body">
        <div class="pricing-content-1">
          <div class="row">
		  <?php foreach ($Packagedetails as $Packagedetail){ ?>
            <div class="col-md-4">
              <div class="price-column-container border-active">
                <div class="price-table-head bg-red">
                  <h2 class="no-margin"><?php echo ucfirst($Packagedetail['Package']['name']);?></h2>
                </div>
                <div class="arrow-down border-top-red"></div>
                <div class="price-table-pricing">
                 <?php 
					$currencycode=PAYMENT_CURRENCY_CODE;
					if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){ ?>
						<h3><span class="price-sign">$</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='JPY'){ ?>
						<h3><span class="price-sign">¥</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='EUR'){ ?>
						<h3><span class="price-sign">€</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='GBP'){ ?>
						<h3><span class="price-sign">£</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
						<h3><span class="price-sign">kr</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='CHF'){ ?>
						<h3><span class="price-sign">CHF</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<h3><?php } else if($currencycode=='BRL'){ ?>
						<h3><span class="price-sign">R$</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='PHP'){ ?>
						<h3><span class="price-sign">₱</span><?php echo $Packagedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='ILS'){ ?>
						<h3><span class="price-sign">₪</span><?php echo $Packagedetail['Package']['amount'];?></h3>
					<?php } ?>
                </div>
                <div class="price-table-content">
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"> <i class="fa fa-comment font-red"></i> </div>
                    <div class="col-xs-9 text-left mobile-padding"><?php echo $Packagedetail['Package']['credit'];?> <?php echo ucfirst($Packagedetail['Package']['type']);?> Messages</div>
                  </div>
                </div>
                <div class="arrow-down arrow-grey"></div>
                <div class="price-table-footer">
                   <a class="nyroModal" href="<?php echo SITE_URL; ?>/users/purchase_credit_stripe/<?php echo $Packagedetail['Package']['id']?>"><button type="button" class="btn red btn-outline sbold uppercase price-button">Confirm</button></a>

                   
                </div>
              </div>
            </div>
			<?php } ?>
          </div>
        </div>
      </div>
    </div>
	<div class="portlet light portlet-fit ">
      <div class="portlet-title">
        <div class="caption"> <i class="fa fa-bullhorn font-green"></i> <span class="caption-subject font-green bold uppercase">VOICE ADD-ON PACKAGES</span> </div>
      </div>
      <div class="portlet-body">
        <div class="pricing-content-1">
          <div class="row">
				<?php foreach ($Packagevoicedetails as $Packagevoicedetail){ ?>
            <div class="col-md-4">

              <div class="price-column-container border-active">
                <div class="price-table-head bg-green">
                  <h2 class="no-margin"><?php echo ucfirst($Packagevoicedetail['Package']['name']);?></h2>
                </div>
                <div class="arrow-down border-top-green"></div>
                <div class="price-table-pricing">
                  <?php 
					$currencycode=PAYMENT_CURRENCY_CODE;
					if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){ ?>
						<h3><span class="price-sign">$</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='JPY'){ ?>
						<h3><span class="price-sign">¥</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='EUR'){ ?>
						<h3><span class="price-sign">€</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='GBP'){ ?>
						<h3><span class="price-sign">£</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
						<h3><span class="price-sign">kr</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='CHF'){ ?>
						<h3><span class="price-sign">CHF</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<h3><?php } else if($currencycode=='BRL'){ ?>
						<h3><span class="price-sign">R$</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='PHP'){ ?>
						<h3><span class="price-sign">₱</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
						<?php } else if($currencycode=='ILS'){ ?>
						<h3><span class="price-sign">₪</span><?php echo $Packagevoicedetail['Package']['amount'];?></h3>
					<?php } ?>
                </div>
                <div class="price-table-content">
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"> <i class="fa fa-bullhorn font-green"></i> </div>
                    <div class="col-xs-9 text-left mobile-padding"><?php echo $Packagevoicedetail['Package']['credit'];?> <?php echo ucfirst($Packagevoicedetail['Package']['type']);?> Messages</div>
                  </div>
                </div>
                <div class="arrow-down arrow-grey"></div>
                <div class="price-table-footer">
                  <a class="nyroModal" href="<?php echo SITE_URL; ?>/users/purchase_credit_stripe/<?php echo $Packagevoicedetail['Package']['id']?>"><button type="button" class="btn green btn-outline sbold uppercase price-button">confirm</button></a>


                </div>
              </div>
            </div>
			<?php } ?>
          </div>
        </div>
      </div>
    </div>
	
  </div>
</div>
<script src="https://checkout.stripe.com/checkout.js"></script>