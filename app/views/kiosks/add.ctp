<style>
.padd {
  padding: 0;
}
.ValidationErrors {
    color: red;
}
</style>
<?php
echo $this->Html->css('colorPicker');
echo $this->Html->script('jquery.colorPicker');
?>
<script>
$(function() {
	$('#color').colorPicker();
	$('#background_color').colorPicker();
	$('#keypad_text_color').colorPicker();
	$('#keypad_button_color').colorPicker();
	$('#textcolor').colorPicker();
	$('#buttoncolor').colorPicker();
	$('#bottom_text_color').colorPicker();
 });
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Kiosk</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
				<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo SITE_URL;?>/kiosks/index">Kiosks</a>
				</li>
			</ul>  			
		</div>	
<?php echo $this->Session->flash(); ?>		
		<div class="clearfix"></div>
								<!--<div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">-->

<div class="portlet mt-element-ribbon light portlet-fit ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create your kiosk form
</div>
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="fa fa-tablet font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase"></span>
                                    </div>
                                </div>

<?php $active=$this->Session->read('User.active');?>
			<?php if((empty($numbers_sms))&&($users['User']['sms']==0)){ ?>
			<div class="portlet-body form">	
					<h3 style="margin-top:5px">You need to get a SMS enabled online number to use this feature.</h3><br><b>Purchase Number to use this feature by </b>
				<?php
				if(API_TYPE==0){
					echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}else if(API_TYPE==1){
					echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}
				?>
			</div>

<?php }elseif ($active==0){?>
					<h3>Oops! You need to activate your account to use this feature.</h3><br>
					<?php $payment=PAYMENT_GATEWAY;
					if($payment=='1' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with 2Checkout by <?php echo $this->Html->link('Clicking Here', array('controller' =>	'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
						Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('2Checkout', array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
					<?php } ?> 

	<?php   }else{ ?>
				

                                <div class="portlet-body form">
                                    <form role="form" method="post" enctype="multipart/form-data">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label>Kiosk Name</label>
                                                <div class="input">
                                                    <input type="text" class="form-control" placeholder="Kiosk Name" id="name" name="data[Kiosk][name]"> </div>
                                            </div>
											<div class="form-group">
												<label>Loyalty Programs</label>
												<?php
												$Smsloyalty[0]='Select Loyalty Programs';
												echo $this->Form->input('Kiosk.loyalty_programs', array(
												'class'=>'form-control',
												'label'=>false,
												'default'=>0,
												'type'=>'select',
												'options' => $Smsloyalty));
												?>
											</div>
											<hr></hr>
											<div class="form-group">
												<label style="font-size:20px;padding-bottom:10px">Background Color</label>
												<div class="input">
												   <input name="data[Kiosk][background_color]" id="background_color" value="#ff9900" type="text">
												</div>
											</div>
											<div class="form-group">
												<label for="exampleInputFile1">File Upload</label>
												<input type="file" name="data[Kiosk][file]" id="file">
											</div>
											<div class="form-group">
												<label>Style</label>
												<select class="form-control">
													<option value="Centered">Centered</option>
													<option value="Stretched">Stretched</option>
													<option value="Titled">Tiled</option>
											   
												</select>
											</div>	
											<hr></hr>
											<div class="form-group">
												<label for="exampleInputFile1" style="font-size:20px;padding-bottom:10px">Business Logo</label>
												<input type="file" id="business_logo" name="data[Kiosk][business_logo]">
												<p class="help-block"> Image Upload. </p>
											</div>											<hr></hr>
											<div class="form-group">
												<label style="font-size:20px;padding-bottom:10px">Text Header</label>
												<textarea class="form-control" id="textheader" name="data[Kiosk][textheader]" rows="3"><strong>[Company Name] Loyalty Program</strong><p>You can join our program if not subscribed currently or check in below</p></textarea>
											</div>
												
											<div class="form-group">
												<label>Alignment</label>
												<select class="form-control" id="alignment" name="data[Kiosk][alignment]">
													<option value="Center">Center</option>
													<option value="Left">Left</option>
													<option value="Right">Right</option>
												</select>
											</div>	
											<div class="form-group">
												<label>Font</label>
												<select class="form-control" id="font" name="data[Kiosk][font]">
													<option value="A">Arial</option>
													<option value="H">Helvetica</option>
													<option value="T">Times New Roman</option>
													<option value="V">Verdana</option>
												</select>
											</div>	
											<div class="form-group">
												<label>Size</label>
												<select class="form-control" id="fontsize" name="data[Kiosk][fontsize]">
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="16">16</option>
													<option value="18">18</option>
													<option value="20">20</option>
													<option value="24">24</option>
												</select>
											</div>
											<div class="form-group">
												<label>Color</label>
												 <input name="data[Kiosk][color]" id="color" value="#ff0000" type="text">
											</div>											
                                            <div class="form-group">
                                                <label>Style</label>
                                                <div class="checkbox-inline">
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleB]" value="B" checked > B </label>
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleI]" value="I"> I</label>
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleU]" value="U"> U</label>
                                                </div>
                                            </div>
											<hr></hr>
											<div class="form-group">
<label style="font-size:20px;padding-bottom:10px">Kiosk Buttons</label>
                                                
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" checked id="joinbuttons" name="data[Kiosk][joinbuttons]"> Join Button </label>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" checked id="punchcard" name="data[Kiosk][punchcard]"> Punch Card </label>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" checked id="checkpoints" name="data[Kiosk][checkpoints]"> Check Points</label>
                                                    <label>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label>Join The Program Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="joinbutton" name="data[Kiosk][joinbutton]" placeholder="Join The Program" value="Join The Program"> 
												<!--</div>-->
                                            </div>
											 <div class="form-group">
                                                <label>Check In Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="checkin" name="data[Kiosk][checkin]" placeholder="Check In" value="Check In"> 
												<!--</div>-->
                                            </div>
											<div class="form-group">
                                                <label>Check Points Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="mypoints" name="data[Kiosk][mypoints]" placeholder="My Points" value="My Points">
												<!--</div>-->
                                            </div>
											<div class="form-group">
												<label>Button Color</label>
												<input name="data[Kiosk][buttoncolor]" id="buttoncolor" value="#ff99cc" type="text">
											</div>
											<div class="form-group">
												<label>Text Color</label>
												<input name="data[Kiosk][textcolor]" id="textcolor" value="#999999" type="text">
											</div>
											<hr></hr>
											<div class="form-group">
												<label style="font-size:18px">KeyPad Buttons</label>
											</div>
											<div class="form-group">
												<label>Button Color</label>
												<input name="data[Kiosk][keypad_button_color]" id="keypad_button_color" value="#999999" type="text">
											</div>
											<div class="form-group">
												<label>Text Color</label>
												<input name="data[Kiosk][keypad_text_color]" id="keypad_text_color" value="#ff99cc" type="text">
											</div>
											<hr></hr>
											<div class="form-group">
                                                <label style="font-size:20px;padding-bottom:10px">Bottom Text</label>
                                                <textarea name="data[Kiosk][bottom_text]" id="bottom_text" class="form-control" rows="3">Copyright <?php echo date('Y');?> - All Rights Reserved</textarea>
                                            </div>
											<div class="form-group">
                                                <label>Alignment</label>
                                                <select class="form-control" name="data[Kiosk][bottom_text_alignment]" id="bottom_text_alignment">
                                                    <option value="Center">Center</option>
                                                    <option value="Left">Left</option>
                                                    <option value="Right">Right</option>
                                                </select>
											</div>	
											<div class="form-group">
													<label>Font</label>
													<select class="form-control" name="data[Kiosk][bottom_text_font]" id="bottom_text_font">
														<option value="A">Arial</option>
														<option value="H">Helvetica</option>
														<option value="T">Times New Roman</option>
														<option value="V">Verdana</option>
													</select>
											</div>	
											<div class="form-group">
												<label>Size</label>
												<select class="form-control" name="data[Kiosk][bottom_text_size]" id="bottom_text_size">
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="16">16</option>
													<option value="18">18</option>
													<option value="20">20</option>
													<option value="24">24</option>
												</select>
											</div>
											<div class="form-group">
												<label>Color</label>
												<input name="data[Kiosk][bottom_text_color]" id="bottom_text_color" value="#ffcc00" type="text">
											</div>											
                                            <div class="form-group">
                                                <label>Style</label>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleB]" id="bottom_text_styleB" checked > B </label>
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleI]" id="bottom_text_styleI"> I</label>
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleU]" id="bottom_text_styleU"> U</label>
                                                </div>
                                            </div>
											<hr></hr>
                                            <div class="form-group">
                                                <label>Collect Additional Information</label>
                                                <div class="checkbox-inline">
                                                    <label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][firstname]" id="firstname"> First Name </label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][lastname]" id="lastname"> Last Name
													</label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][email]" id="email"> Email
													</label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][dob]" id="dob"> Date Of Birth
													</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn blue">Save</button>
											<a class="btn default" href="<?php echo SITE_URL;?>/kiosks/index">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
<?php } ?>	
                        </div>
                        
                        </div>
                    </div>
		<script>
            jQuery(function(){
				jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Name"
                });jQuery("#KioskLoyaltyPrograms").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please select Kiosk Loyalty Programs"
                });jQuery("#textheader").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter text header"
                });jQuery("#bottom_text").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter bottom text"
                });
				
            });		
	</script>