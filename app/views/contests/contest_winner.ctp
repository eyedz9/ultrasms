 
<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a message"
                });jQuery("#KeywordId").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please select group"
               
                });
				
				
            });
            /* ]]> */			
</script>
<script type="text/javascript">
 $(document).ready(function (){
$('textarea[maxlength]').live('keyup change', function() {
  var str = $(this).val()
  var mx = parseInt($(this).attr('maxlength'))
  if (str.length > mx) {
     $(this).val(str.substr(0, mx))
     return false;
  }
  }
  )
});


</script>
<script>


var count = "160";
function update(){
var tex = $("#message").val();
var msg = $("#Preview1").val();
/*var firstnamefound = 0;
var lastnamefound = 0;

if(tex.indexOf("%First_Name%") != -1){
			firstnamefound = 1;
		}
		if(tex.indexOf("%Last_Name%") != -1){
			lastnamefound = 1;
		}*/


tex = tex.replace('{','');
tex = tex.replace('}','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace('`','');
tex = tex.replace("'","");
tex = tex.replace('"','');
//texcount = tex.replace('%First_Name%','');
//texcount = texcount.replace('%Last_Name%','');



var len = tex.length;



		/*if(firstnamefound){
			len = len + parseInt($("#firstName").val());
		}
		
		if(lastnamefound){
			len = len + parseInt($("#lastName").val());
		}*/
		//console.log('length:'+len);
 var count1 = (160-(len));
//console.log('count1:'+count1);
//var message = $("#Preview1").val();
//var lenth = message.length;
//alert(lenth);
$("#message").val(tex);

if(len > count){
tex = tex.substring(0,count1);

//$("#Preview1").val(tex);
return false;
}

$("#limit2").val(count1);

}

</script>
<style>


.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 71px;
    min-width: 208px;
    padding: 10px;
    position: relative;
}
span.ValidationErrors{
color:red;
}
</style>
<?php 

 $sms_balance=$this->Session->read('User.sms_balance');
 $assigned_number=$this->Session->read('User.assigned_number');
$active=$this->Session->read('User.active');
$pay_activation_fees_active=$this->Session->read('User.pay_activation_fees_active');

if($active == 1 && $assigned_number !=0 && $sms_balance > 0){

if((empty($numbers_sms))&&($users['User']['sms']==0)){ ?>
<div class="portlet box blue-dark">
	<div class="portlet-body">
		<div class="m-heading-1 border-white m-bordered">
			<h3>
			You need to get a SMS enabled online number to use this feature.</h3>
			<br>
			<b>Purchase Number to use this feature by </b>
			<?php  if(API_TYPE==0){
			 echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			 }else if(API_TYPE==1){
			 echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			 }else if(API_TYPE==3){
		         echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
		         }?>
		</div>
	</div>
</div>

 <?php } else{ ?>

<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			Contest Winner
		</div>
	</div>
	<div class="portlet-body">
		<!--<form role="form">-->
			<div class="form-body">
				<?php echo $this->Form->create('Contest', array('controller'=> 'contests', 'action'=> 'contest_winner'.'/'.$phoneno['ContestSubscriber']['id'].'/'.$id, 'id' => 'sendMsgForm'));?>
				<!--input  style="width:481px;" type="hidden" name="data[Contest][id]"  value="<?php //echo $id;?>"-->
				<input  style="width:481px;" type="hidden" name="data[ContestSubscriber][phoneno]"  value="<?php echo $phoneno['ContestSubscriber']['phone_number'];?>">
				<?php
				$message= "There are no contest entries to pick a winner";
				if($phoneno['ContestSubscriber']['phone_number']!=''){
				?>
				<!--label>Winning Phone Number: </label-->
				<table class="table table-bordered table-striped">
					<tr>												
						<td style="width:15%"><b>Winning Phone Number:</b></td>
						<td style="width:35%">
						<span class="text-muted" style="font-size:16px;font-weight: bold;color: green"> 
                         <?php echo $phoneno['ContestSubscriber']['phone_number'];?>
						</span>
						</td>
					</tr>
				</table>
				<!--span style="font-size:16px;font-weight: bold;color: green"><?php echo $phoneno['ContestSubscriber']['phone_number'];?>
				</span-->
				<div class="form-group">
					<label>Message <span class="required_star"></span></label>
					<?php echo $this->Form->textarea('ContestSubscriber.message',array('div'=>false,'label'=>false,'class' => 'form-control','id'=>'message','maxlength'=>'160','onKeyup'=>'return update()'))?>
						<div id='counter' style="margin-bottom: 10px;
					margin-right: 90px;
					text-align: left;">Remaining Characters
					<input type=text class="form-control input-xsmall" name=limit2 id=limit2 size=4 readonly value="160"><br/>
					Special characters not allowed such as ~ { } [ ] ;
				</div>
	
			<div class="form-actions">	 
				<?php echo $this->Form->submit('Send',array('div'=>false,'class'=>'btn blue'));?>

			</div>

			<?php  }else{?>
			<span style="font-size:15px;font-weight: bold;"><?php echo $message;?></span>
			<?php }	  ?>
		</form>
	</div>
</div>

<?php }}else{ ?>
<div class="portlet box blue-dark">
	<div class="portlet-body">
		<div class="m-heading-1 border-white m-bordered">
			<?php 
			//$pay_activation_fees=PAY_ACTIVATION_FEES;
			if($active==1 && $assigned_number ==0){?>
			<h3>
			You need to get an online number to use this feature.</h3>
			<br>
			Purchase Number to use this feature by <b>
                        <?php if(API_TYPE==0){
			 echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			 }else if(API_TYPE==1){
			 echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			 }else if(API_TYPE==3){
		         echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
		         }?>
                        <br />
			<?php }else if($active==0 || $assigned_number==0){?>
			<h3>
			Oops! You need to activate your account to use this feature.</h3>
			<br>
			<?php 
			$payment=PAYMENT_GATEWAY;
			if($payment=='1'){
			?>
			Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
			<?php }else if($payment=='2'){?>
			Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
			<?php }else if($payment=='3'){ ?>
			Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
			<?php } ?>
			<?php }else{?>
			<h3>
			SMS balance is 0. Please purchase additional SMS credits to allow sending SMS messages.
			</h3>
			<br>
			<?php 
			$payment=PAYMENT_GATEWAY;
			if($payment=='1'){
			?>
			Purchase SMS credits to use this feature by <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'paypalpayment'))?>.</b><br />
			<?php }else if($payment=='2'){?>
			Purchase SMS credits to use this feature by <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'stripepayment'))?>.</b><br />
			<?php }else if($payment=='3'){ ?>
			Purchase SMS credits to use this feature by <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'paypalpayment'))?></b> or <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'stripepayment'))?></b><br />
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>

