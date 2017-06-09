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
<script type="text/javascript">




var count = "160";
function update(){
var tex = $("#message1").val();
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
//tex = tex.replace(':','');
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
$("#message1").val(tex);

if(len > count){
tex = tex.substring(0,count1);

//$("#Preview1").val(tex);
return false;
}

$("#limit2").val(count1);
//$("#Preview1").val(tex);
}


/*var x;

function showClock() {
x=setTimeout("showClock()",1000);
$('#timezone').html(("Current Time:<?php echo date('Y-m-d g:i:s');?>"));
}*/

function validate(){

var phoneno =document.getElementById("twiliosPhoneNumber").value;
var message1 =document.getElementById("message1").value;
//alert(message1);

if(phoneno!=''){
var phone =(/^[+0-9]+$/);
 
 //alert(phoneno);

 
 
 if(phoneno.match(phone)){
 
 
 }else{
 alert("Please enter correct phone no.");  
    return false;
 
 }


}
 if(message1==''){
 
 alert("Please enter a message.");  
    return false;
 
 }
}

</script>

<div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Send a Single SMS
	</div>
</div>



<div class="portlet-body">

<!-- login box-->
<div class="loginbox">
	<div class="loginner">
		<div class="login-left">
		<?php 
		 $sms_balance=$this->Session->read('User.sms_balance');
		 $assigned_number=$this->Session->read('User.assigned_number');
		 $active=$this->Session->read('User.active');
		 $this->Session->write('User.pay_activation_fees_active',$users['User']['pay_activation_fees_active']);
		
	if($active == 1 && $assigned_number !=0 && $sms_balance > 0){ ?>
<div class="contacts form">
<?php echo $this->Form->create('slooces', array('controller'=> 'slooces', 'action'=> 'sendsms/1', 'id' => 'sendMsgForm', 'onsubmit'=>' return validate()'));?>
	<div class="form-group">
		<!--Javascript validation goes Here---------->
		<div id="validationMessages" style="display:none"></div>
		<!--Javascript validation goes Here---------->
		
		<label>Select a contact</label>
	<?php echo $this->Form->input('phone', array('options' => $contacts, 'class' =>'form-control', 'label' => false, 'div' => false));?>
	</div>
	
	
	<p style="margin-bottom:5px;margin-top:5px">OR</p>
	<div class="form-group">
	<label>Enter a phone number</label>
		<?php echo $this->Form->input('phone_number', array('class' =>'form-control','label' => false,'div' => false,'value'=>$phoneno,'readonly'=>true));?>
     </div>
	<div class="form-group">
	<label>Message</label>
	<?php echo $this->Form->input('message', array('type' =>'textarea','id'=>'message1','maxlength'=>'160','onKeyup'=>'return update()','class' =>'form-control','label' => false,'div' => false));?>
	</div>
	<div id='counter'>Remaining Characters
	<input type=text name=limit2 id=limit2 size=4 readonly value="160">
	</div>
	<input type="Submit" value="Send" class="btn btn-primary">
<?php 
echo $this->Form->end();
//echo $this->Validation->rules('twilios',array('formId'=>'sendMsgForm','validationBlock' =>'sendMsg'));
?>
</div>
<?php
	} else {?>
	
	
	<?php 
	//$pay_activation_fees=PAY_ACTIVATION_FEES;
if($active==1 && $assigned_number ==0){?>
	
	<h3>
You need to get an online number to use this feature.</h3>
<br>
Purchase Number to use this feature by <b><?php 

if(API_TYPE==0){
echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
}else if(API_TYPE==1){


echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));

}

?>.</b><br />
	
<?php }else if($active==0 || $assigned_number ==0){?>


	<h3>
Oops! You need to activate your account to use this feature.</h3>
<br>
<?php 
 $payment=PAYMENT_GATEWAY;
 if($payment=='1' && PAY_ACTIVATION_FEES=='1'){
?>
Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
<?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />

<?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
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
</div>
</div>
<?php } ?>