<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Past Receipts
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
					<span>Past Receipts</span>
				</li>
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-money"></i>Past Receipts </div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Amount</th>
								<th>Txn ID</th>
								<th>Payment</th>
                                                                <th>Package</th>
								<th>Receipt Date</th>
							</tr>
						</thead>
							<?php 
							$i = 0;
							foreach($invoicedetils as $invoicedetil) { 
							$class = null;
							if ($i++ % 2 == 0) {
								$class = ' class="altrow"';
							}
							?>
						<!--<tbody>-->
							<tr <?php echo $class;?> > 
								<td>
								<?php 
								$currencycode=PAYMENT_CURRENCY_CODE;
								if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
								<?php echo '$'.$invoicedetil['Invoice']['amount'] ?>
								</td>
								<?php } else if($currencycode=='JPY'){ ?>
								<?php echo '¥'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='BRL'){ ?>
								<?php echo 'R$'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='PHP'){ ?>
								<?php echo '₱'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='ILS'){ ?>
								<?php echo '₪'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='EUR'){ ?>
								<?php echo '€'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='GBP'){ ?>
								<?php echo '£'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
								<?php echo 'kr'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php } else if($currencycode=='CHF'){ ?>
								<?php echo 'CHF'.$invoicedetil['Invoice']['amount'] ?></td>
								<?php }?>
								<td><?php echo $invoicedetil['Invoice']['txnid'] ?></td>
								<td><?php 
								if($invoicedetil['Invoice']['type']==0){
								echo "PayPal";
								}else{
								echo "Credit Card";
								}
								?>
								</td>
                                                                <td><?php echo $invoicedetil['Invoice']['package_name'] ?></td>
								<td><?php echo $invoicedetil['Invoice']['created'] ?></td>
							</tr>
						<!--</tbody>-->
						<?php } ?>
					</table>
					
				</div>
<div class="dataTables_paginate paging_bootstrap_number">
						<ul class="pagination" style="visibility: visible;">
							<ul class="pagination">
								<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
								echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));?>
								</li>
								<li>
								<?php //echo $this->Paginator->numbers(array('class' => 'paginate_button'));?> 
								</li>
								<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));?>
								</li>
							</ul>
						</ul>
					</div>
			</div>
		</div>
	</div>
</div>