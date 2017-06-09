<style>

.flag-sec label { width: 100%;}
.flag-sec #image { margin: 0 12px 0 0;}
.cntry-slct {   width: auto; }

.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 300px;
    min-width: 500px;
    padding: 10px;
    position: relative;
}

</style>
<style>
	.feildbox img{
	width:30px!important;
	}
</style>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			Purchase Number
		</div>
	</div>
<div class="portlet-body">
	<h3>To get additional secondary numbers, you must purchase a numbers package first. Thank you.</h3><br>
<div class="note note-warning"><b>NOTE: </b>If you already purchased a numbers package and need more, and you previously purchased with PayPal, you MUST first cancel your current numbers subscription through PayPal and then purchase a new numbers package.</div><br/>
	<?php $payment=PAYMENT_GATEWAY;
	if(PAYMENT_GATEWAY ==1){?>
		Purchase Numbers with <?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalnumbers'),array('escape' =>false))?><br />
	<?php }else if(PAYMENT_GATEWAY==2){?>
		Purchase Numbers with <?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>	'users', 'action' =>'stripenumbers'),array('escape' =>false))?><br />
	<?php }else if(PAYMENT_GATEWAY == 3){ ?>
		Purchase Numbers with <b><?php echo $this->Html->link($this->Html->image('buy-logo-medium.png'), array('controller' =>'users', 'action' =>'paypalnumbers'),array('escape' =>false))?></b> or <b><?php echo $this->Html->link($this->Html->image('stripe-pay-button.png'), array('controller' =>'users', 'action' =>'stripenumbers'),array('escape' =>false))?></b><br />
	<?php } ?> 
<br/>
</div>


