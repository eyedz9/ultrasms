<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<script>
	jQuery(function(){
		jQuery("#UserCardNumber").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter credit card number"
		});jQuery("#UserCardCvv").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter cvv number"
		});jQuery("#exp_month").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter expirt month"
		});jQuery("#exp_year").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter expiry year"
		});
	});
</script>
<style>
.ValidationErrors {
    color: #ff0000;
}
</style>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Card Details
	</div>
</div>
<div class="portlet-body">
	<!-- login box-->
	<div class="loginbox">
		<div class="loginner">
			<div class="login-left">
				<div class="contacts form">
					<form id="addContactForm" accept-charset="utf-8" action="<?php echo SITE_URL;?>/users/credit_card/<?php echo $id;?>" method="post">
					<?php echo $this->Form->create('User', array('id' => 'addContactForm'));?>
					<div class="form-group" >	
						<label>Card Number</label>	
						<?php echo $this->Form->input('card_number',array('class' =>'form-control','div'=>false,'label'=>false));?>
					</div>
					<div class="form-group" >	
						<label>Cvv</label>	
						<?php echo $this->Form->input('card_cvv',array('class' =>'form-control','div'=>false,'label'=>false)); ?>
					</div>
					<div class="form-group" >	
						<label>Exp Month</label>
						<?php
							$options_dmonth=array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12');
							echo $this->Form->input('exp_month',array('type'=>'select','div'=>false,'label'=>false, 'class' => 'form-control','id'=>'exp_month','options'=>$options_dmonth));
						?>						
					</div>
					<div class="form-group" >	
						<label>Exp Year</label>
						<?php
							$s_dt=date('Y');
							$e_dt=date('Y')+20;
							for($i=$s_dt;$i<=$e_dt;$i++){
								$options_dt[$i]=$i;
							}
							echo $this->Form->input('exp_year',array('type'=>'select','div'=>false,'label'=>false, 'class' => 'form-control','id'=>'exp_year','options'=>$options_dt));
						?>
					</div>
					<input type="Submit" value="Send" class="btn btn-primary">
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
