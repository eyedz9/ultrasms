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
 function checkrotate(){
 
 var rotate_number = $('#rotate_number').val();
 
 if(rotate_number==0){
 
 $('#rotate_number').val(1);
 
 }else{
 $('#rotate_number').val(0);
 
 }
 
 }

</script>
<script>


var count = "110";
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
//tex = tex.replace("'","");
//tex = tex.replace('"','');
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
 var count1 = (110-(len));
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


.feildbox img {
    border: none;
    width: auto;
}
</style>

<style>


.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 71px;
    min-width: 308px;
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
<div class="portlet-title">
		<div class="caption">
			Send Contest
		</div>
	</div>	
<div class="portlet-body">
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
		} ?> 
	</div>
</div>
<?php } else{ ?>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			Send Contest
		</div>
	</div>
	<div class="portlet-body">
		<!--<form role="form">-->
<?php echo $this->Form->create('Contest', array('controller'=> 'contests', 'action'=> 'send_message', 'id' => 'sendMsgForm'));?>
			<div class="form-body">
					
				<div class="form-group" style="margin-top: 5px">	
					<input  style="width:481px;" type="hidden" name="data[Contest][id]"  value="<?php echo $id;?>">
					<label for="some21">Groups</label>	
					<select id="GroupId" class="form-control"  name="data[Group][id][]" >
					<?php
					foreach($Group as $Groups){ 
					//$groupid=$messages['Group']['id'];
					if($Groups['Group']['keyword']!='?')
					{
					?>
					<option value="<?php echo $Groups['Group']['id']; ?>"><?php echo ucwords($Groups['Group']['group_name']).'('.$Groups['Group']['totalsubscriber'].')'; ?></option>
					<?php }} ?>
					</select>
				</div>
				<div class="form-group">
					<label>Contest Message <span class="required_star"></span></label>
					<?php echo $this->Form->textarea('Contest.message',array('div'=>false,'label'=>false,'id'=>'message','class' => 'form-control','onKeyup'=>'return update()','value'=>''))?><br/>
					<span style="font-size:15px;"><?php
					$optmsg=OPTMSG;	
					echo $sendthis=" Reply ".$contestkeyworddata['Contest']['keyword']." <br/>".$optmsg;
					?></span>
					<div id='counter' style="margin-bottom: 10px;
					margin-right: 20px;
					text-align: left;">Remaining Characters
					<input type=text class="form-control input-xsmall" name="limit2" id="limit2" size=4 readonly value="110"><br/>
					Special characters not allowed such as ~ { } [ ] ;
					</div>
				</div>
					<?php if(API_TYPE !=2){?>		
				<div class="form-group" style="margin-top:10px">
					<input id="rotate_number" type="checkbox" value="0" name="data[User][rotate_number]" onclick="checkrotate()">
					<font style="font-weight: bold">
					Rotate through your long code numbers<!--a rel="tooltip" title="Useful if you have a large number of opt-in contacts to market to, you can spread your workload across multiple numbers in your account" class="ico" href="#" title="help"><img src="<?php //echo SITE_URL?>/img/help.png" alt="help"/></a--></font>    
				</div>
					<?php } ?>
			</div>
			<div class="form-actions">
				<?php echo $this->Form->submit('Send',array('div'=>false,'class'=>'btn blue'));?>
			</div>
		</form>
	</div>
</div>

<?php } } else{ ?>
<div class="portlet box blue-dark">
<div class="portlet-title">
		<div class="caption">
			Send Contest
		</div>
	</div>	
<div class="portlet-body">
				<?php 
				//$pay_activation_fees=PAY_ACTIVATION_FEES;
				if($active==1 && $assigned_number ==0){?>
				<h3>You need to get an online number to use this feature.</h3><br>
			Purchase Number to use this feature by <b><?php 
			if(API_TYPE==0){
			echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			}else if(API_TYPE==1){
			echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
			}else if(API_TYPE==3){
		        echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
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

