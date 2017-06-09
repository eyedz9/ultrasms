<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo SITE_URL; ?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="<?php echo SITE_URL; ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_URL; ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<?php
echo $this->Html->css('jAlerts/jquery.alerts');
echo $this->Html->script('jquery.alerts');
?>

<script>
 /* <![CDATA[ */
function checkdata(){


   var name = document.getElementById('contact_last_name').value;
   var phone = document.getElementById('phone').value;
   var bday = document.getElementById('birthday').value;
   
   /*if(name==''){
   
   alert('Please enter your name');
   return false;
   }else*/ 

   if(phone==''){
      alert('Please enter your phone number');
      return false;
   }else{
    var number=(/^[+0-9]+$/);

    if(!phone.match(number)){
      alert("Please enter correct phone number with NO spaces, dashes, or parentheses.");
      return false;  
    }
  }

  if (bday !=''){
    if (!isValidDate(bday)) {
        alert('Please enter date in format: YYYY-MM-DD');
        return false;
    }
 }
   

}

function isValidDate(dateString) {

  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  //alert (dateString.match(regEx));
  
  if(!dateString.match(regEx)){
    return false;  // Invalid format
  }else{

     var d;

     d = new Date(dateString);

     if(!((d = new Date(dateString))|0)){
        return false;
     }else{
        return true;
     }
     
  }

  
}
				
function  submitForm()
{
	if (!$("#bill_check").is (':checked'))
	{
	
	jQuery("#contact_last_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Address"
                }); jQuery("#phone").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter City"
                }); jQuery("#cc_state").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter State"
                }); jQuery("#cc_zip_code").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Postal code"
                });
	}
	if($("#key_check").val()=='')
	{
	 $('#keyworderr').show();	
	 $('#keyworderr').html("keyword already taken");
	 $('#Error').hide();
	 return false;
	}else{
	$('#keyworderr').hide();
	}
	
	if (!$("#term_check").is (':checked'))
	{
		$('#tc_error').html("Please check terms and conditions");
		$('#tc_error').show();
	   
	   return false;
	}else{
	$('#tc_error').hide();
	}
	
}			
            /* ]]> */	

</script>

<style>
<?php 
if($_GET['ff'] == 'H'){
$ff = 'Helvetica';
}elseif($_GET['ff'] == 'T'){
$ff = 'Times New Roman';
}elseif($_GET['ff'] == 'V'){
$ff = 'Verdana';
}else{
$ff = 'Arial';
}

if($_GET['fs'] == 'B'){
$fs = 'font-weight:bold;';
}elseif($_GET['fs'] == 'I'){
$fs = 'font-style:italic;';
}elseif($_GET['fs'] == 'BI'){
$fs = 'font-weight:bold;font-style:italic;';
}else{
$fs = '';
}
?>
	body,table#mainTable{
		background-color:#<?=$_GET['bgc'] ?> !important;
		color:#<?=$_GET['fc'] ?> !important;
		font-size: <?=$_GET['fz'] ?>px;
		font-family: <?=$ff ?>; 	
		<?=$fs?>
	}
</style>
<!--Account Section Start---------->

<? if(isset($_GET['ll']) && $_GET['ll'] != '' && $_GET['ll'] != 'null'){ ?>
<center><img src="<?=$_GET['ll']?>"></center>
<? }?>

<?php


if($saved){
if(isset($_GET['ms']) && trim($_GET['ms']) != ''){
	$ms = $_GET['ms'];
}
else{
$ms = "You've been successfully subscribed!";
   if(isset($_GET['rd']) && $_GET['rd'] != '' && $_GET['rd'] != 'null'){
      echo '<script type="text/javascript">window.top.location.href="' . $_GET['rd'] . '";</script>';
   }
}?>
<center><span class="help-block" style="color:#<?=$_GET['fc'] ?>"><?=$ms?></span></center>
<?} 
else {
?>
<? if(isset($_GET['mt']) && $_GET['mt'] != ''){ ?>
<center><span class="help-block" style="color:#<?=$_GET['fc'] ?>"><?=$_GET['mt']?></span></center>
<? }?>
<form action="" onsubmit="return checkdata()" method="post">
<input type="hidden" name="data[Contact][autoresponse]" value="<?php echo $_GET['mar']?>">
<input type="hidden" id="advance_pack" name="data[Contact][group_id]" value="<?php echo $_GET['group'];?>">


<div class="form-group" style="padding:10px;">	
	<?php if($err){ ?>
		<!--<tr>
			<td align="center" style="color:#FF0000;"><?=$err?></td>
		</tr><tr><td>&nbsp;</td></tr>-->
                <div style="align:center;color:#FF0000;"><?=$err?></div>
	<?php } ?>
<table border="0" id="mainTable" cellspacing="3" cellpadding="3" align="center">
		
		<tr>
		<!--<td><i class="fa fa-user" aria-hidden="true" style="font-size:18px;">&nbsp;</i>
</td>-->
		<td>
<div class="input-group">
<?php echo $this->Form->input('Contact.name',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'contact_last_name','placeholder'=>'Name is optional'))?>
<span class="input-group-addon">
			<i class="fa fa-user"></i>
</span>
</div>
</td>
		</tr>
<tr><td>&nbsp;</td></tr>
		<tr>
		<!--<td><i class="fa fa-phone" aria-hidden="true" style="font-size:18px;">&nbsp;</i>
</td>-->
		<td>
<div class="input-group">
<?php echo $this->Form->input('Contact.phone_number',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'phone','placeholder'=>'Examples: 12025248725 or 447481340516'))?>
<span class="input-group-addon">
			<i class="fa fa-phone"></i>
</span>
</div>
</td>
		</tr>
<?php if($_GET['ce'] == '1') {?>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<div class="input-group">
<input type="email" class="form-control input-xlarge" id="email" name="data[Contact][email]" placeholder="Email is optional" />
<span class="input-group-addon">
			<i class="fa fa-envelope-o"></i>
</span>
</div>
</td>
</tr>
<?php } ?>

<?php if($_GET['cb'] == '1') {?>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<div class="input-group">
<input type="date" class="form-control input-xlarge" id="birthday" name="data[Contact][birthday]" placeholder="Birthday is optional. Format: YYYY-MM-DD" />
<span class="input-group-addon">
			<i class="fa fa-birthday-cake"></i>
</span>
</div>
</td>
</tr>
<?php } ?>

<tr>
<td>&nbsp;</td>
</tr>
		<tr>
		<!--<td>&nbsp;</td>-->
		<td><?php echo $form->submit('Join Now', array('type'=>'submit','class'=>'btn green-meadow btn-block sbold uppercase' ,'value'=>'Join Now'));  ?>   </td>
		</tr>

	</table>
</div>

<? }?>

<div class="form-group" style="padding:10px">	
	<span class="help-block">
			
				<strong>To Unsubscribe</strong> - Reply 'STOP' to any message you receive.<br />
				<strong>For Help</strong> - Reply 'HELP' anytime.<br />
				Your privacy is always protected and your information will not be shared. Consent is not required as a condition of purchase.<br/> Message &amp; Data Rates May Apply<br />
				Powered by <a href="<?=SITE_URL?>" target="_blank"><?php echo SITENAME;?></a>
			
        </span>
</div>
<?php echo $this->Form->end();?>

</html>