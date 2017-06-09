<style>

input[type="submit"] {
    cursor: pointer;
    margin-left: 0px;
}
</style>
<div class="purchase_credit">
	<h2>Purchase Monthly Package</h2>
	<p>Please confirm the following before checking out</p>
	<br/>
	<p>Credits for: <b><?php echo $this->Session->read('User.username')?></b></p>
	<p>Item: <b><?php echo $monthlydetail['MonthlyPackage']['package_name']?></b></p>
	<p>Total Cost: <b>$<?php echo $monthlydetail['MonthlyPackage']['amount']?>/month</b></p>
	
      <?php echo $this->Form->create('User',array('action'=> 'paypalpayment/'.$this->Session->read('User.id'),'name'=>'signupForm','id'=>'signupForm'));?>
		<input type="hidden" name="data[MonthlyPackage][packageid]" value="<?php echo $monthlydetail['MonthlyPackage']['id']?>">
		<input type="hidden" name="data[User][id]" value="<?php echo $this->Session->read('User.id'); ?>">
		<input type="hidden" name="data[User][amount]" value="<?php echo $monthlydetail['MonthlyPackage']['amount']?>">
		
		
		<input type="hidden" name="data[User][package_name]" value="<?php echo $monthlydetail['MonthlyPackage']['package_name']?>">
		
		<input style=" background: none repeat scroll 0 0 #D78F13;padding: 5px 15px;font-weight: bold;font-size: 12px;float: left;color: #FFFFFF;border: medium none;"type="submit" value="Pay Now" class="submit">
               <?php echo $this->Form->end();?>
	
</div>
	