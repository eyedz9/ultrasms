<script LANGUAGE="JavaScript">
function ValidateForm(){
	var option1  = $('#emailalerts1').prop("checked");
	var option2  = $('#emailalerts2').prop("checked");
	var on  = $('#emailalerts3').prop("checked");
	var on1  = $('#incomingsmsalerts3').prop("checked");
	var option3  = $('#incomingsmsemailalerts2').prop("checked");
	var number  = $('#alertmobilenumber').val();
	
	var oncall  = $('#incomingsmsalerts6').prop("checked");
	var callforward_number  = $('#callforward_number').val();
        var broadcast_number  = $('#broadcast').val();

	if(option1==false && option2==false && on==true){
		alert('Please select an option for new subscriber alerts');
		return false;
	}else if(option3==true && number=='' && on1==true){
		alert('Please enter your mobile number for incoming SMS alerts.');
		return false;
	}else if(oncall==true && callforward_number==''){
		alert('Please enter your mobile number for call forward.');
		return false;
	}

        if(option3==true && number!='' && on1==true){
            var phone =(/^[+0-9]+$/);

            if(!number.match(phone)){
               alert("Please enter correct incoming SMS alerts number with NO spaces, dashes, or parentheses.");
               return false;  
            }
        }

       if(oncall==true && callforward_number!=''){
            var phone =(/^[+0-9]+$/);

            if(!callforward_number.match(phone)){
               alert("Please enter correct call forwarding number with NO spaces, dashes, or parentheses.");
               return false;  
            }
        }

       if(broadcast_number!=''){
            var phone =(/^[+0-9]+$/);

            if(!broadcast_number.match(phone)){
               alert("Please enter correct broadcast number with NO spaces, dashes, or parentheses.");
               return false;  
            }
        }
}
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Settings</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li><span>Settings  </span></li>
			</ul> 
		</div>
		<?php  echo $this->Session->flash(); ?>		
		<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-cog font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"> Settings </span>
					</div>
				</div>
			<div class="portlet-body form">
				<?php echo $this->Form->create('User',array('action'=> 'setting','id'=>'settingform','onsubmit'=>'return ValidateForm()'));?>
				<div class="form-body">
					<fieldset>
						<legend><font style="font-size:20px">New Subscriber Alerts</font></legend>

						<div class="radio-list" >
							<label style="margin-top: 5px">As they happen<span class="required_star"></span></label>
							<?php if($users['User']['email_alerts']==1){?>								
								<input name="data[User][email_alerts]" type="radio" value="1" id="emailalerts1" checked />
								<?php }else{?>
								<input name="data[User][email_alerts]" type="radio" value="1" id="emailalerts1" />
							<?php } ?>
						</div>
				
						<div class="radio-list" >
							<label>Daily Summary<span class="required_star"></span></label>
							<?php if($users['User']['email_alerts']==2){?>
								<input name="data[User][email_alerts]" type="radio" value="2" id="emailalerts2" checked />
								<?php }else{?>
								<input name="data[User][email_alerts]" type="radio" value="2" id="emailalerts2" />
							<?php } ?>
						</div>
						<div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<?php if($users['User']['email_alert_options']==0){?>
								<input name="data[User][email_alert_options]" type="radio" value="0" id="emailalerts3" checked />
								<?php }else{ ?>
								<input name="data[User][email_alert_options]" type="radio" value="0" id="emailalerts3" />
							<?php } ?>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
								<?php if($users['User']['email_alert_options']==1){?>
								<input name="data[User][email_alert_options]" type="radio" value="1" id="emailalerts4" checked />
								<?php }else{ ?>
								<input name="data[User][email_alert_options]" type="radio" value="1" id="emailalerts4" />
								<?php } ?>
						</div>
					</fieldset>
<?php if($users['User']['birthdaywishes']==1){?>	
					<br>					
					<fieldset>
						<legend><font style="font-size:20px">Birthday Capture (Birthday SMS wishes)</font>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="After user subscribes via SMS/Web Widget, this will text them back asking to provide their birth date for the purpose of sending them your birthday wishes on or before their birthday, all handled automatically. This also has to be enabled at the group level." data-original-title="Birthday SMS Wishes" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></legend>

						<div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<input name="data[User][birthday_wishes]" type="radio" value="0" id="emailalerts10" <?php if($users['User']['birthday_wishes']==0){?> checked <?php } ?>/>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
							<input name="data[User][birthday_wishes]" type="radio" value="1" id="emailalerts10" <?php if($users['User'][			'birthday_wishes']==1){?>checked <?php } ?> />
						</div>
					</fieldset>
<?php } ?>
					<br>
					<fieldset>
						<legend><font style="font-size:20px">Name and Email Capture</font>&nbsp;<!--<a rel="tooltipxx" title="After user subscribes via SMS/Web Widget, this will text them back asking to provide their name and email address. Great for personalizing future text messages and promoting with your email marketing campaigns." class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="After user subscribes via SMS/Web Widget, this will text them back asking to provide their name and email address. Great for personalizing future text messages and promoting with your email marketing campaigns" data-original-title="Name and Email Capture" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</legend>

						<div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<input name="data[User][capture_email_name]" type="radio" value="0" id="emailalerts11" <?php if($users['User'][		'capture_email_name']==0){?> checked <?php } ?>/>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
							<input name="data[User][capture_email_name]" type="radio" value="1" id="emailalerts11" <?php if($users['User']['capture_email_name']==1){?>checked <?php } ?> />
						</div>
					</fieldset>
					<br>
					<fieldset>
						<legend><font style="font-size:20px">Email To SMS And SMS To Email</font>&nbsp;<!--<a rel="tooltipxx" title="Get email notices to your registered email address for incoming text messages to your online number(SMS to Email). You can then respond directly to that email from your email client, the system will take that email and text them back(Email to SMS)." class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Get email notices to your registered email address for incoming text messages to your online number(SMS to Email). You can then respond directly to that email from your email client, the system will take that email and text them back(Email to SMS)" data-original-title="Email To SMS/SMS To Email" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>

</legend>

						<div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<input name="data[User][email_to_sms]" type="radio" value="0" id="emailalerts12" <?php if($users['User']['email_to_sms']==0){?> checked <?php } ?>/>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
							<input name="data[User][email_to_sms]" type="radio" value="1" id="emailalerts12" <?php if($users['User']['email_to_sms']==1){?>checked <?php } ?> />
						</div>
						<br>
					</fieldset>
					<fieldset>
						<legend><font style="font-size:20px">Low Credit Balance Alerts</font></legend>
						<div class="form-group">
							<label style="margin-top: 5px">Low SMS Credit Threshold<span class="required_star"></span></label>
							<select id="KeywordId" class="form-control"  name="data[User][low_sms_balances]" >
								<?php
									$Option=array('10'=>'10','25'=>'25','50'=>'50','75'=>'75','100'=>'100');
									foreach($Option as $row => $value){
									$selected = '';
									if($users['User']['low_sms_balances'] == $row){
									$selected = ' selected="selected"';
									}?>
									<option "<?php echo  $selected; ?> " value="<?php echo  $row; ?>"><?php echo  $value; ?></option>
									<?php }
								?>
							</select>		
						</div>
						<?php if(API_TYPE !=2){?>
						<div class="form-group">
							<label>Low Voice Credit Threshold<span class="required_star"></span></label>
							<select id="KeywordId" class="form-control"  name="data[User][low_voice_balances]" >
								<?php
									$Option=array('5'=>'5','10'=>'10','20'=>'20','30'=>'30','50'=>'50');
									foreach($Option as $row => $value){
									$selected = '';
									if($users['User']['low_voice_balances'] == $row){
									$selected = ' selected="selected"';
									}?>
									<option "<?php echo  $selected; ?> " value="<?php echo  $row; ?>"><?php echo  $value; ?></option>
									<?php }
								?>
							</select>		
						</div>
						<?php } ?>
						<div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<?php if($users['User']['email_alert_credit_options']==0){?>
							<input name="data[User][email_alert_credit_options]" type="radio" value="0" checked />
							<?php }else{ ?>
							<input name="data[User][email_alert_credit_options]" type="radio" value="0" id="emailalerts4" />
							<?php } ?>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
							<?php if($users['User']['email_alert_credit_options']==1){?>
							<input name="data[User][email_alert_credit_options]" type="radio" value="1" checked />
							<?php }else{ ?>
							<input name="data[User][email_alert_credit_options]" type="radio" value="1" id="emailalerts4" />
							<?php } ?>
						</div>
					</fieldset>
					<br/>
					<fieldset>
						<legend><font style="font-size:20px">Incoming SMS Alerts</font></legend>
						<div class="feildbox" >
							<label style="margin-top: 5px">Email<span class="required_star"></span></label>
							<?php 
							if($users['User']['incomingsms_emailalerts']==1){?>
							<input name="data[User][incomingsms_emailalerts]" type="radio" value="1" id="incomingsmsemailalerts1" checked />
							<?php }else{?>
							<input name="data[User][incomingsms_emailalerts]" type="radio" value="1" id="incomingsmsemailalerts1" />
						<?php } ?>
						</div>
						<div class="feildbox">
							<label>SMS<span class="required_star"></span></label>
							<?php if($users['User']['incomingsms_emailalerts']==2){?>
							<input class="form-control" name="data[User][incomingsms_emailalerts]" type="radio" value="2" id="incomingsmsemailalerts2" checked />&nbsp;&nbsp;<br/><b>Mobile #:</b> <input class="form-control" name="data[User][smsalerts_number]" type="text" value="<?php echo $users['User']['smsalerts_number']?>" id="alertmobilenumber" placeholder="Mobile number with country code. US example: 12025248725"/>
							<?php }else{?>
							<input  class="form-control" name="data[User][incomingsms_emailalerts]" type="radio" value="2" id="incomingsmsemailalerts2" />&nbsp;&nbsp;<b>Mobile #:</b> <input class="form-control input-small" name="data[User][smsalerts_number]"  type="text" value="<?php echo $users['User']['smsalerts_number']?>" id="alertmobilenumber"/>
							<?php } ?>
						</div>
						<br/><div class="feildbox" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<?php if($users['User']['incomingsms_alerts']==0){?>
							<input name="data[User][incomingsms_alerts]" type="radio" value="0" id="incomingsmsalerts3" checked />
							<?php }else{ ?>
							<input name="data[User][incomingsms_alerts]" type="radio" value="0" id="incomingsmsalerts3" />
							<?php } ?>
						</div>
						<div class="feildbox">
							<label>Off<span class="required_star"></span></label>
							<?php if($users['User']['incomingsms_alerts']==1){?>
							<input name="data[User][incomingsms_alerts]" type="radio" value="1" id="incomingsmsalerts4" checked />
							<?php }else{ ?>
							<input name="data[User][incomingsms_alerts]" type="radio" value="1" id="incomingsmsalerts4" />
							<?php } ?>
						</div>
					</fieldset>
					<br/>
					<fieldset>
					<legend><font style="font-size:20px">Call Forward</font>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="If turned on, any incoming calls to the assigned number selected below will forward to the number you have entered below." data-original-title="Call Forward" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></legend>
						<div class="form-group" style=" float: left;">
							<label>On<span class="required_star"></span></label>
							<?php if($users['User']['incomingcall_forward']==0){?>
							<input name="data[User][incomingcall_forward]" type="radio" value="0" id="incomingsmsalerts6" checked />
							<?php }else{ ?>
							<input name="data[User][incomingcall_forward]" type="radio" value="0" id="incomingsmsalerts6" />
							<?php } ?>
						</div>
						<div class="form-group">
							<label>Off<span class="required_star"></span></label>
							<?php if($users['User']['incomingcall_forward']==1){?>
							<input name="data[User][incomingcall_forward]" type="radio" value="1" id="incomingsmsalerts7" checked />
							<?php }else{ ?>
							<input name="data[User][incomingcall_forward]" type="radio" value="1" id="incomingsmsalerts7" />
							<?php } ?>
						</div>
						<!--<br/>-->
						<div class="form-group">
							<label>Assigned Number<span class="required_star"></span></label>
							<select id="KeywordId" class="form-control"  name="data[User][assign_callforward]" >
							<option "<?php if($users['User']['assigned_number']== $users['User']['assign_callforward']){ echo ' selected="selected" '; } ?>" value="<?php echo  $users['User']['assigned_number']; ?>"><?php echo  $users['User']['assigned_number']; ?></option>
								<?php
									if(!empty($UserNumber)){
									foreach($UserNumber as $UserNumbers){ ?>
									<option "<?php if($UserNumbers['UserNumber']['number']== $users['User']['assign_callforward']){ echo ' selected="selected" '; } ?>" value="<?php echo  $UserNumbers['UserNumber']['number']; ?>"><?php echo  $UserNumbers['UserNumber']['number']; ?></option>
									<?php }}?>
							</select>		
						</div>
						<!--<br/>-->
						<div class="form-group">
							<label>Forward Number<span class="required_star"></span></label>
							<input name="data[User][callforward_number]" type="text" class="form-control" value="<?php echo $users['User']['callforward_number'];?>" id="callforward_number" placeholder="Mobile number with country code. US example: 12025248725"/>	
						</div>
					</fieldset>
					<br/>
					<fieldset>
						<legend><font style="font-size:20px">Broadcast From Phone</font>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Blast out your SMS marketing campaigns with a simple text message! Below is the phone number the text will be coming from. Format - send [group keyword]: message. Example: send pizza: stop in today to receive a 20% discount on all large pizzas!" data-original-title="Broadcast From Phone" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></legend>
						<div class="feildbox">
							<label>Phone Number<span class="required_star"></span></label>
							<input name="data[User][broadcast]" type="text" class="form-control" value="<?php echo $users['User']['broadcast'];?>" id="broadcast" />
							<br><label>NO spaces, dashes, or parentheses in phone numbers</label></br>
							<label><font style="color:red">Include country code in the number</font></label>
							<br><label>US Example: 12025248725</label></br>
							<label>UK Example: 447481340516</label></br>
						</div>
					</fieldset> 
				</div>
				<div class="form-actions">
						<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
			
				</div>
				<?php echo $this->Form->end();?>
			
			</div>
		</div>
	</div>
</div>
