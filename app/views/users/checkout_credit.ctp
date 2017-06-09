<div class="purchase_credit">
	<h2>Purchase Monthly Package</h2>
	<p>Please confirm the following before checking out</p>
	<br/>
	<p>Credits for: <b><?php echo $this->Session->read('User.username')?></b></p>
	<p>Item: <b><?php echo $monthlydetail['MonthlyPackage']['package_name']?></b></p>
	<p>Total Cost: <b>$<?php echo $monthlydetail['MonthlyPackage']['amount']?>/month</b></p>
	


	<a style=" background: none repeat scroll 0 0 #D78F13;border: medium none;color: #FFFFFF; float: left;font-size: 12px;font-weight: bold;padding: 5px 15px;" href='https://www.2checkout.com/checkout/purchase?sid=<?php echo $config['Config']['2CO_account_ID']; ?>&quantity=1&product_id=<?php echo $monthlydetail['MonthlyPackage']['product_id'] ?>&merchant_order_id=<?php echo $this->Session->read('User.id') ?>'>Pay Now</a>
	
</div>
	