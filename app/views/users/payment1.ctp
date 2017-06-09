
<style type="text/css">

.ValidationErrors {  margin-left: 250px;}

.include{ width:430px; height:auto; margin:30px; border:1px solid #e9effd; box-shadow: 0px 1px 2px #000; float:right; border-radius:8px;padding-bottom: 10px;}

.signup1 .heading,.include .heading{ background-color: #00ACF2;border-radius:8px 8px 0px 0px;
    border-bottom: 10px solid #019FDF;
    height: 120px;
    padding: 10px 50px;}

.signup1 .heading h1,.include .heading h1 {
    color: #FFFFFF;
    font-family: "a.c.M.E. Secret Agent";
    font-size: 19px;
    margin: 0px 22px 0;
    padding:10px;
    text-shadow: 0 1px 1px #000000; text-align:center;
	
}

.include ul {
    margin-left:10px;
    margin-top: 15px;
    padding-left: 0px;
}

.include ul li span {
    position: relative;
    top: -8px; color:#000;
}

.include ul li {
    color: #000;
    font-size: 1.5em;
    list-style-image: url("<?php echo SITEURL ?>images/cheke.png");
    list-style-position: inside; margin:10px;
}

.message {
    background: url("<?php echo SITEURL ?>images/seprator.png") no-repeat scroll left bottom transparent;
    padding: 36px 0 15px 45px;
    width: 358px;
}

.message img {
    margin: 0 10px 0 0;
}

.message h2 {
    color: #000000;
    font: bold 30px/20px "Myriad Pro",Arial;
    margin: 0;
	border:none;
	padding:0px;
}

.message h2 span {
    color: #D70000;
    font-size: 24px;
}

.message p {
    color: #707070;
    font: bold 20px "Myriad Pro",Arial;
    margin: 10px 0 0;
    text-align: left;
}

.left{ float:left;}

.login-left {
    float: left;
    width: 100%;
}

.inputtext{
    background: url("<?php echo SITEURL ?>images/input.png") repeat-x scroll 0 0 transparent;
    border: 1px solid #DADADA;
    border-radius: 10px 10px 10px 10px;
    float: left;
    height: 23px;
    padding: 5px 10px;
    width: 323px;
}

.signup1 .feildbox {
    padding: 24px;
    width: 488px;
}

.signup1 .feildbox label{ width:130px;}

.inputbutton1 {background: url("<?php echo SITEURL ?>images/freeaccount.png") no-repeat scroll 0 0 transparent;
   border: medium none;
    cursor: pointer;
    height: 70px;
    width: 428px;
	margin: 43px 38px 0;  
}
.inputbutton2 {background: url("<?php echo SITEURL ?>images/signup-now.png") no-repeat scroll 0 0 transparent;
   border: medium none;
    cursor: pointer;
    height: 70px;
    width: 428px;
	margin: 43px 38px 0;  
}
.signup1 input[type="submit"] {
    margin-left:43px;
}

</style>
<script>
function ValidateForm(form){


  var radiovalue =$("input[type='radio']:checked").val();
 

  
  if(radiovalue >0 ){
  
  form.submit();
  }else{
  
   alert("Please choose your plan");
   return false;
  }

 }
 

</script>

<h2><?php __('Payment');?></h2>

<!-- left_box-->

<div class="signup1" style="padding:0px;width: 538px;border-radius:8px;">

<div class="heading"><h1>You are successfully registered you choose package </h1>
</div>
		<!--Javascript validation goes Here---------->
	<?php echo $this->Form->create('User',array('action'=> 'payment1/5','name'=>'signupForm','id'=>'signupForm','onsubmit'=>' return ValidateForm(this)'));?>
	<?php foreach ($monthlydetails as $monthlydetail){ ?>
		<div class="login-left">
		
	<label><?php echo $monthlydetail['MonthlyPackage']['package_name']?></label>
	<input type="radio" name="data[MonthlyPackage][id]" value="<?php echo $monthlydetail['MonthlyPackage']['id']?>" id="packageid">
	<input type="hidden" name="data[User][id]" value="<?php echo $this->Session->read('User.id'); ?>">
		<input type="hidden" name="data[User][amount]" value="<?php echo $monthlydetail['MonthlyPackage']['amount']?>">
		<input type="hidden" name="data[User][package_name]" value="<?php echo $monthlydetail['MonthlyPackage']['package_name']?>">
		
		
		</div>
		<?php } ?>
		<div class="feildbox">
	<?php echo $this->Form->submit('Pay Here',array('class'=>'inputbutton'));
			?>
			</div>
		<?php echo $this->Form->end();?>
		
		
	</div>
    
   



<div class="clear"></div>

