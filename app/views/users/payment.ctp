
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

<h2><?php __('Payment');?></h2>

<!-- left_box-->

<div class="signup1" style="padding:0px;width: 538px;border-radius:8px;">

<div class="heading"><h1>You are successfully registered you choose package <?php 
$amount = 0;
//$user_id = 1580;
foreach($sports as $sport){
$amount = $amount+$sport['Sport']['fee'];
echo $sport['Sport']['sport'].',';
}
 ?> amount to $<?php echo $amount; ?> </h1>

     <!--div style="color: #D70000; font-size: 20px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; text-align:center; ">         No credit card required    </div-->


</div>
		<!--Javascript validation goes Here---------->
	<?php echo $this->Form->create('User',array('action'=> 'payment/'.$user_id,'name'=>'signupForm','id'=>'signupForm'));?>
		<div class="login-left">
		<input type="hidden" name="data[User][id]" value="<?php echo $user_id ?>">
		<!--input type="hidden" name="data[User][package_id]" value="<?php //echo $packages['Package']['id'] ?>"-->
		<!--input type="hidden" name="data[User][allowed_credit_monthly]" value="<?php //echo $packages['Package']['credit'] ?>"-->
		<input type="hidden" name="data[User][amount]" value="<?php echo $amount; ?>">
		<input type="hidden" name="data[User][package_name]" value="Sports">
			
			<div class="feildbox">
			
			<?php 
		
				echo $this->Form->submit('Pay Here',array('class'=>'inputbutton'));
			
			?>
			</div>
		</div>
		<?php echo $this->Form->end();?>
		
		
	</div>
    
    
    <!-- right_box-->
     <!--div class="include">
     
        <div class="heading">
       
      <img alt="" class="left" src="<?php echo SITEURL ?>images/includes_bg.png">
       
       </div>
       
       
               <div class="left message">
				<img alt="" class="left" src="<?php echo SITEURL ?>images/cheke.png">
				<span class="left">
					<h2><span><?php echo $packages['Package']['credit'] ?></span> Text Messages</h2>
					<p>a month</p>
				</span>
				</div>
                
                <div class="left message">
				<img alt="" class="left" src="<?php echo SITEURL ?>images/cheke.png">
				<span class="left">
					<h2><span><?php echo $packages['Package']['amount'] ?></span> Charge</h2>
					<p>to our CMS</p>
				</span>
				</div>
       
        
       
                     
       
       </div-->




<div class="clear"></div>

