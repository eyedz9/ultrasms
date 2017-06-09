<script>
 function confirmfrmSubmit()
{ 
var a=document.getElementById('GroupId').value;	

//alert(a);

if(a==0){
alert("Please select group");
return false;
		
}


}
 function confirmmessage(group_id){
 if(group_id>0){
 //alert(id);
 $.ajax({
 
  url: "<?php  echo SITE_URL ?>/polls/check/"+group_id+"/"+<?php echo $id; ?>,
  //url: "<?php  echo SITE_URL ?>/polls/check/"+group_id,
  type: "POST",
 
  dataType: "html",
  success: function(response) {
  
  
  $('#message').html(response);
  
 }
 
 });
 }
 }
 
 
 function checkrotate(){
 
 var rotate_number = $('#rotate_number').val();
 
 if(rotate_number==0){
 
 $('#rotate_number').val(1);
 
 }else{
 $('#rotate_number').val(0);
 
 }
 
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
</style>


<style>

.feildbox img {
    border: none;
    width: auto;
}
</style>
<?php 

$sms_balance=$this->Session->read('User.sms_balance');
$assigned_number=$this->Session->read('User.assigned_number');
$active=$this->Session->read('User.active');
 $pay_activation_fees_active=$this->Session->read('User.pay_activation_fees_active');

if($active == 1 && $assigned_number !=0 && $sms_balance > 0){ 

if((empty($numbers_sms)) && ($users['User']['sms']==0)){ ?>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Send Poll Question
	</div>
</div>

<div class="portlet-body">
<div class="contacts form">
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
 <?php } else { ?>
 
 <div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Send Poll Question
	</div>
</div>



<div class="portlet-body">
 

<!-- login box-->
<div class="loginbox">
	<div class="loginner">
		
		
<div class="contacts form">
<?php echo $this->Form->create('Poll', array('controller'=> 'Poll', 'action'=> 'send_question', 'id' => 'sendMsgForm','onSubmit' => 'return confirmfrmSubmit();'));?>

	<div class="feildbox form-group" >
	
	<input  style="width:481px;" type="hidden" name="data[Question][question_id]"  value="<?php echo $id;?>">
	
	<label for="some21">Groups</label>	
			
	<?php
	
	$Group[0]='Please select';
    echo $this->Form->input('Group.id', array(
    'label'=>false,
    'default'=>0,
    
    'type'=>'select',
    'onchange'=>'confirmmessage(this.value);',
	'class' =>'form-control',
    
    'options' => $Group
	
	
	));
	
	?>
<div>
<p id="message" style="color:red; font-size: 15px;"></p>
</div>
<?php if(API_TYPE !=2){?>
<div class="feildbox form-group" >

 <input id="rotate_number" type="checkbox" value="0" name="data[User][rotate_number]" onclick="checkrotate()">

	<font style="font-weight: bold">
Rotate through your long code numbers&nbsp;<!--a rel="tooltip" title="Useful if you have a large number of opt-in contacts to market to, you can spread your workload across multiple numbers in your account" class="ico" href="#" title="help"><img src="<?php //echo SITE_URL?>/img/help.png" alt="help"/></a--> </font>   
<!--<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Useful if you have a large number of opt-in contacts(>500), you can spread your workload across multiple numbers in your account. If checked, please include your campaign or business name in the message" data-original-title="Long Codes Rotate" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a> -->
    </div>
	<?php } ?>
	<div class="feildbox form-group" >
<label for="some21" style="font-size: 18px;font-weight: bold;">Preview</label>
	<?php 
	

	$options = '';
		foreach($questionmessages as $question){
			$questions1 = $question['Question']['question'];
			
			$options .= $question['Option']['optionb'].". ".$question['Option']['optiona']."\n";
		}
		 $sendthis="Reply A,B,C OR D.";	
		 $optmsg=OPTMSG;	
		 
		  $message1=$questions1."<br/>".$options." <br/>".$sendthis." <br/>".$optmsg;
	
?>

</div>
	
	<p id="message" style="font-size: 15px;"><?php echo $message1;?></p>
	
	</div>
	


<input type="Submit" value="Send" class="btn btn-primary">
<?php 
echo $this->Form->end();

?>
</div>
</div>
</div>
<?php } }else{ ?>
<div class="portlet box blue-dark">
<div class="portlet-title">
		<div class="caption">
			Send Poll Question
		</div>
	</div>	
<div class="portlet-body">
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
<?php } ?>


