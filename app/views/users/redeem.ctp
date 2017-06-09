<!--<h1>Loyalty Program Reward</h1>-->
<!-- login box-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		
		
		.button-green{ background:<?php echo $kiosks['Kiosks']['buttoncolor'];?>; border:none; border-radius:0px; color:<?php echo $kiosks['Kiosks']['textcolor'];?>; padding:10px 0px; display:inline-block; font-size:30px; text-decoration:none;cursor:pointer;}
		
		.button-box { margin: 10px auto; text-align: center; max-width:400px; width:100%;}
		.button1{ width:100%; margin:10px 0;}
		.button2{ width:49%; margin:10px 0;}
		.button3{ width:49%; margin:10px 0;}
		

		.input1{ display:inline-block; padding:10px; width:61.3%;font-size: 30px;height: 36px;}
		.button4{ width:32.49%; margin:2px 0;}
		.button-box1{ max-width:500px;margin:10px auto;}

		.row {clear: both; margin: 20px 0;text-align: center;}
		.row label { display: inline-block; vertical-align: middle;  width: 100px;text-align:left;}
		.input2{ display:inline-block; padding:5px; width:61.3%;font-size: 20px;height: 25px;}

		.count {
		  display: inline-block;
		  margin: 3px 2px;
		}
		@media(max-width:768px){
			.img-box{ float:right;max-width: 350px;}
			.mid-box{ float:left;max-width: 350px;}
			.button-box1 .img-box{ float:none;max-width: 100%;}
		}
		@media(max-width:767px){
			.img-box{ float:right;max-width: 300px;}
			.mid-box{ float:left;max-width: 300px;}
			.button4 {width: 32.7%;}
			.input1{ width:61.3%;}
			.row label { width: 95%;text-align:left;}
			.input2{ width:95%;}
		}
		@media(max-width:639px){
			.img-box{ float:none;max-width: 100%;}
			.mid-box{ float:none;max-width: 100%;}
			.input1{ display:inline-block; padding:10px; width:58.3%;font-size: 20px;height: 24px;}
			.button4 {
				font-size: 20px;
				margin: 10px 0;
				width: 32%;
			}
		}

	</style>
		
	</head>
<?php if($notfound==1){?>
        <div style="padding: 7px; border: solid 1px #e4a2a2; background: #eec4bc; width: 98%;text-align:center;margin-left:3px" class="feildbox">
 	
	<p style="color:#7b2e2e;font-size:46px;">Redeem code not found. Please make sure you are not editing the redeem code.</p>
	</div>
<?php }else if($empty==1){?>
        <div style="padding: 7px; border: solid 1px #e4a2a2; background: #eec4bc; width: 98%;text-align:center;margin-left:3px" class="feildbox">
 	
	<p style="color:#7b2e2e;font-size:46px;">Redeem code is empty. Please make sure you are not editing the redeem code.</p>
	</div>
<?php }else if($loyaltyuser['SmsloyaltyUser']['redemptions']==0){?>
	<!--<p style="color:green;font-size:17px;">Thanks for participating in our loyalty program. Reward has now been redeemed.</p>-->
	<div style="padding: 7px; border: solid 1px #cde4a2; background: #e8fcc2; width: 98%;text-align:center;margin-left:3px" class="feildbox">
 	
	<p style="color:#607b2e;font-size:32px;">Thanks for participating. Reward has now been redeemed.</p>
	</div>
        <?php if($code!=''){?>
        <br/><div class="button-box"><a class="button-green button2" href="<?php echo SITE_URL;?>/kiosks/view/<?php echo $code;?>">Home</a></div>
        <?php }?>	
	<?php }else{?>	
	<div style="padding: 7px; border: solid 1px #e4a2a2; background: #eec4bc; width: 98%;text-align:center;margin-left:3px" class="feildbox">
 	
	<p style="color:#7b2e2e;font-size:46px;">Reward already has been redeemed.</p>
	</div>
	<?php } ?>
	
<!-- login box-->