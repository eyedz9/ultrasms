<div class="page-content-wrapper">
  <div class="page-content">
    <h3 class="page-title">Packages<img style="float:right;margin-top:-17px" src="<?php echo SITE_URL;?>/app/webroot/img/poweredbystripe.png"></h3>
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
        <div class="caption"> <i class="fa fa-cart-plus font-blue"></i> <span class="caption-subject font-blue bold uppercase">MONTHLY NUMBERS PACKAGES</span> </div>
      </div>
      <div class="portlet-body">
        <div class="pricing-content-1">
          <div class="row">
		  <?php 
		  foreach ($monthlydetails as $monthlydetail){ ?>
            <div class="col-md-4">
              <div class="price-column-container border-active">
                <div class="price-table-head bg-blue">
                  <h2 class="no-margin"><?php echo ucfirst($monthlydetail['MonthlyNumberPackage']['package_name']);?></h2>
                </div>
                <div class="arrow-down border-top-blue"></div>
                <div class="price-table-pricing">
					<?php 
					$currencycode=PAYMENT_CURRENCY_CODE;
					if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){ ?>
						<h3><span class="price-sign">$</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='JPY'){ ?>
						<h3><span class="price-sign">¥</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='EUR'){ ?>
						<h3><span class="price-sign">€</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='GBP'){ ?>
						<h3><span class="price-sign">£</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
						<h3><span class="price-sign">kr</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='CHF'){ ?>
						<h3><span class="price-sign">CHF</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<h3><?php } else if($currencycode=='BRL'){ ?>
						<h3><span class="price-sign">R$</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='PHP'){ ?>
						<h3><span class="price-sign">₱</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
						<?php } else if($currencycode=='ILS'){ ?>
						<h3><span class="price-sign">₪</span><?php echo $monthlydetail['MonthlyNumberPackage']['amount'];?></h3><p>per month</p>
					<?php } ?>
                </div>
                <div class="price-table-content">
                  <div class="row mobile-padding">
                    <div class="col-xs-3 text-right mobile-padding"><i class="fa fa-phone"></i></div>
                    <div class="col-xs-9 text-left mobile-padding"><b><?php echo $monthlydetail['MonthlyNumberPackage']['total_secondary_numbers'];?></b>&nbsp;Secondary Numbers</div>
                  </div>
                </div>
                <div class="arrow-down arrow-grey"></div>
                <div class="price-table-footer">
					<a class="nyroModal" href="<?php echo SITE_URL; ?>/users/purchase_subscription_stripe_numbers/<?php echo $monthlydetail['MonthlyNumberPackage']['id']?>"><button type="button" class="btn blue btn-outline sbold uppercase price-button">Confirm</button></a>
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