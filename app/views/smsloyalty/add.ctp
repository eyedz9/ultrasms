<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>
<?php
echo $this->Html->css('dhtmlgoodies_calendar');
echo $this->Html->script('dhtmlgoodies_calendarnew.js');
?>
<style>
.ValidationErrors{
color:red;
}
</style>
		<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> SMS Loyalty Programs</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo SITE_URL;?>/smsloyalty">Loyalty Programs</a>
						</li>
					</ul>  			
				</div>	
<?php echo $this->Session->flash(); ?>	
<div class="clearfix"></div>

				<div class="portlet mt-element-ribbon light portlet-fit ">
					<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
					<div class="ribbon-sub ribbon-clip ribbon-right"></div>
					create loyalty program form
					</div>
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-gift font-red-sunglo"></i>
							<span class="caption-subject bold uppercase">  </span>
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
				}else if(API_TYPE==3){
					echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}
				?>
			</div>

<?php }elseif ($active==0){?>
					<h3>Oops! You need to activate your account to use this feature.</h3><br>
					<?php $payment=PAYMENT_GATEWAY;
					if($payment=='1' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>	'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
						Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
					<?php } ?> 

	<?php   }else{ ?>
					
	
				
					<div class="portlet-body form">
						<?php echo $this->Form->create(array('controller' => 'smsloyalty','action'=> 'add','enctype'=>'multipart/form-data','onsubmit'=>'return validation()'));?>
						<div class="form-group">
							<label>Program Name<span class="required_star"></span></label>    
							<div class="input"> 							
								<?php echo $this->Form->input('Smsloyalty.program_name',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'program_name','Placeholder'=>'Program Name'))?>

							</div>
						</div>                                          
						<div class="form-group">
							<label>Opt-in List<span class="required_star"></span></label>
							<?php $groups[0]='Please select list';
							echo $this->Form->input('Smsloyalty.group_id', array('class'=>'form-control','label'=>false,'default'=>0,'type'=>'select','options' => $groups));?>
						</div>
						<div class="form-group">
							<label>Start Date<span class="required_star"></span></label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.start',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'startdate','value'=>'','Placeholder'=>'Select Start Date','onclick'=>"displayCalendar(startdate,'mm/dd/yyyy',this)"));?> 
							</div>
						</div> 
						<div class="form-group">
							<label>End Date<span class="required_star"></span></label>
							<div class="input">												
								<?php echo $this->Form->input('Smsloyalty.end',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'enddate','value'=>'','Placeholder'=>'Select End Date','onclick'=>"displayCalendar(enddate,'mm/dd/yyyy',this)"));?>
						   </div>
						</div> 
						<div class="form-group">
							<label>Reach Goal After(# of visits or purchases)<span class="required_star"></span></label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.reachgoal',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'reachgoal'))?>
							</div>
						</div>
						<div class="form-group">
							<label>Notify Punch Code&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Specify how you want to be notified with the punch code that is generated automatically daily." data-original-title="Notify Punch Code" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="radio-list" >				
								<input name="data[Smsloyalty][notify_punch_code]" type="checkbox" id="notify_punch_code" />
							</div>
						</div>
						<div id="notify_punch_code_show" style="display:none;">
							<div class="form-group">
								<label>My Email Address(registered on your account)&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will send the punch code to the email address that is registered on your account." data-original-title="My Email Address" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][my_email_address]" type="checkbox" id="my_email_address" />
								</div>
							</div>
							<div class="form-group">
								<label>This Email Address&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will send the punch code to the email address you have entered below." data-original-title="This Email Address" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][email_address]" type="checkbox" id="email_address" />
								</div>
								<div class="input" id="email_address_show" style="display:none;">                                                   
									<!--<?php echo $this->Form->input('Smsloyalty.email_address_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'email_address_input','placeholder'=>'This Email Address'));?>-->
<?php echo $this->Form->text('Smsloyalty.email_address_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'email_address_input','placeholder'=>'This Email Address','type' => 'email'));?>
								</div>
							</div>
							<div class="form-group">
								<label>Mobile Number&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will text the punch code to the number you enter below. Be aware that the punch code is generated between midnight and 2am server time, which will be the time you get the notification." data-original-title="Mobile Number" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][mobile_number]" type="checkbox" id="mobile_number" />
								</div>
								<div class="input" id="mobile_number_show" style="display:none;">                                                   
									<?php echo $this->Form->input('Smsloyalty.mobile_number_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'mobile_number_input','placeholder'=>'Mobile number with country code. US example: 12025248725'))?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Punch Code(automatically generated daily)&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This is the code your customers will text in to get a punch added on their virtual card. Each day a new punch code will be generated automatically to prevent abuse of system." data-original-title="Punch Code" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>

							</label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.coupancode',array('div'=>false,'label'=>false, 'class' => 'form-control input-medium','id'=>'reachgoal','readonly'=>'readonly','style'=>'background-color:#eeeeee','value'=>$coupancode))?>
							</div>
						</div> 
						<div class="form-group">
							<label>Status Keyword<span class="required_star"></span>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Keyword that user can text in to check their current status in the program." data-original-title="Status Keyword" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label>
							<div class="input">                                                   
							  <?php echo $this->Form->input('Smsloyalty.codestatus',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'codestatus'))?>
							</div>
						</div> 
						<div class="form-group">
							<label>Add Points Message(Punch Code)<span class="required_star"></span>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message that is sent after user texts in a punch code to add a point to their account." data-original-title="Punch Code Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label>
								<?php echo $this->Form->input('Smsloyalty.addpoints',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'addpoints','maxlength'=>'160','onKeyup'=>'return update()','value'=>'Hi %%Name%%, you now have %%STATUS%% point(s). You are one step closer to your reward.'))?>
						</div>
						<div class="form-group">
							<label>Remaining Characters</label>
							<input type="text"  name="limit" id="limit" class="form-control input-xsmall" size=4 readonly value="160">
						</div>
						<?php if(API_TYPE==0){?>
						<div class="form-group">
							<!--<div class="radio-list" >-->
								<?php if((!empty($numbers_sms))||($users['User']['sms']==1)){ ?>
								SMS <input type="radio" value="1" name="data[Smsloyalty][type]" id="sms" checked />
								<?php } ?>
								<?php if((!empty($numbers_mms))||($users['User']['mms']==1)){ ?>
								MMS <input type="radio" value="2" name="data[Smsloyalty][type]" id="mms"/>	
								<?php } ?>						  
							<!--</div>-->
						</div>
						<div id='upload' style="display:none;margin-bottom:0px" >
							<div class="feildbox" >
								
							<div data-provides="fileinput" class="fileinput fileinput-new">
								<input type="hidden" id="check_img_validation" value="0" />
									<div style="width: 200px; height: 150px;" class="fileinput-new thumbnail">
										<img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"> </div>
									<div style="max-width: 200px; max-height: 150px; line-height: 10px;" class="fileinput-preview fileinput-exists thumbnail"></div>
								<div>
									<span class="btn default btn-file">
										<span class="fileinput-new"> Select image </span>
										<span class="fileinput-exists"> Change </span>
										<input type="hidden" value="" name="..."><input type="file" id="files" name="data[Smsloyalty][image]" onclick="check_image()" > </span>
									<a data-dismiss="fileinput" class="btn red fileinput-exists" href="javascript:;"> Remove </a>
								</div>
						</div>

							</div>
						</div>
						<?php } ?>
						<div class="form-group" >
							<label>Reach Goal Message(Reward Offer)<span class="required_star"></span>&nbsp;
								<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message sent after user has collected required number of points to reach reward goal. Redeem link will be appended to the message." data-original-title="Reward Goal Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
								<?php echo $this->Form->input('Smsloyalty.reachedatgoal',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'reachedatgoal','maxlength'=>'160','rows'=>5,'cols'=>45,'onKeyup'=>'return update1()','value'=>'Hi %%Name%%, congratulations, you have reached your goal!'))?>
						</div>
						<div class="form-group">
							<label>Remaining Characters</label>
							<input type="text"  name="limit1" id="limit1" class="form-control input-xsmall" size=4 readonly value="160">
						</div>
						<div class="form-group">
							<label>Check Status Message(Status Keyword)<span class="required_star"></span>&nbsp;
								<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message that is sent after user texts in status keyword to check their status" data-original-title="Status Message" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<?php echo $this->Form->input('Smsloyalty.checkstatus',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'checkstatus','maxlength'=>'160','onKeyup'=>'return update2()','value'=>'Hi %%Name%%, you have %%STATUS%% out of %%GOAL%% points. Keep going, you are almost there.'))?>
						</div>
						<div class="form-group">
							<label>Remaining Characters</label>
							<input type="text"  name="limit2" id="limit2" class="form-control input-xsmall" size=4 readonly value="160">
						</div>
					</div>
							<div class="form-actions">

								<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
								<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'smsloyalty','action' => 'index'),array('class'=>'btn default')); ?>
							</div>
						<?php echo $this->Form->end(); ?>
				</div>
			</div>  
<?php } ?>	
		</div>


	<script type="text/javascript">
		function update(){
			var count = 160;
			var tex = $("#addpoints").val();
			tex = tex.replace('{','');
			tex = tex.replace('}','');
			tex = tex.replace('[','');
			tex = tex.replace(']','');
			tex = tex.replace('~','');
			tex = tex.replace(';','');
			tex = tex.replace('`','');
			tex = tex.replace('"','');
			var len = tex.length;
			var count1 = (count-(len));
			$("#addpoints").val(tex);
			if(len > count){
				tex = tex.substring(0,count1);
				return false;
			}
			$("#limit").val(count1);
		}
		function update1(){
			var count = 160;
			var tex = $("#reachedatgoal").val();
			tex = tex.replace('{','');
			tex = tex.replace('}','');
			tex = tex.replace('[','');
			tex = tex.replace(']','');
			tex = tex.replace('~','');
			tex = tex.replace(';','');
			tex = tex.replace('`','');
			tex = tex.replace('"','');
			var len = tex.length;
			var count1 = (count-(len));
			$("#reachedatgoal").val(tex);
			if(len > count){
				tex = tex.substring(0,count1);
				return false;
			}
			$("#limit1").val(count1);
		}
		function update2(){
			var count = 160;
			var tex = $("#checkstatus").val();
			tex = tex.replace('{','');
			tex = tex.replace('}','');
			tex = tex.replace('[','');
			tex = tex.replace(']','');
			tex = tex.replace('~','');
			tex = tex.replace(';','');
			tex = tex.replace('`','');
			tex = tex.replace('"','');
			var len = tex.length;
			var count1 = (count-(len));
			$("#checkstatus").val(tex);
			if(len > count){
				tex = tex.substring(0,count1);
				return false;
			}
			$("#limit2").val(count1);
		}
	
	/*window.onload = function(){
		var count=1;
		//Check File API support
		if(window.File && window.FileList && window.FileReader){
			$('#files').live("change", function(event) {
				var files = event.target.files; //FileList object
				var output = document.getElementById("result");
				var check=$('img').hasClass('thumbnail');
				if(check==true){
					$('.thumbnail').remove();
				}
				for(var i = 0; i< files.length; i++){
					var file = files[i];
					if(file.type.match('image.*')){
						var picReader = new FileReader();
						picReader.addEventListener("load",function(event){
							var picFile = event.target;
							var div = document.createElement("div");
							div.style.cssText = 'width:80px;float: left;';
							div.innerHTML = "<img style='height:70px!important;width: 70px!important;' class='thumbnail' src='" + picFile.result + "'" +"title='preview image'/>";
							output.insertBefore(div,null);
						});
						$('#clear, #result').show();
						picReader.readAsDataURL(file);
					}else{
						alert("You can only upload image file.");
						$(this).val("");
					}
				}
			});
		}
	}*/
	function validation(){
		if($('#email_address').prop('checked')==true){
			var email_address_input=$('#email_address_input').val();
			if(email_address_input == ''){
				alert( "Please enter your notify Email Address" );
				return false;
			}
		}
		if($('#mobile_number').prop('checked')==true){
			var mobile_number_input=$('#mobile_number_input').val();
			if(mobile_number_input == ''){
				alert( "Please enter your notify Mobile Number" );
				return false;
			}else{
                           var phone =(/^[+0-9]+$/);

                           if(!mobile_number_input.match(phone)){
                              alert("Please enter correct phone number with NO spaces, dashes, or parentheses.");
                              return false;  
                           }
                       }
		}
		if($('#mms').prop('checked')==true){
			var pk_file=$('#files').val();
			if(pk_file == ''){
				alert( "Please Upload a image" );
				return false;
			}
		}
		if($('#sms').prop('checked')==true){
			var sms_msg=document.getElementById("reachedatgoal").value;
			if(sms_msg==''){
				alert( "Please enter a reach at goal" );
				return false;
			}
		}
		if($('#startdate').val()==''){
			alert( "Please select start date" );
			return false;
		}
		if($('#enddate').val()==''){
			alert( "Please select end date" );
			return false;
		}
	}
	$('#mms').click(function(){
		$('#upload').show();
	});
	$('#sms').click(function(){
		$('#upload').hide();
	});
	jQuery(function(){
		jQuery("#program_name").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter program name."
		});jQuery("#SmsloyaltyGroupId").validate({
			expression: "if (VAL > 0) return true; else return false;",
			message: "Please select opt-in list."
		});jQuery("#reachgoal").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter reach goal after."
		});jQuery("#addpoints").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter add points message."
		});jQuery("#reachedatgoal").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter reach goal message(reward offer)."
		});jQuery("#checkstatus").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter check status message(status keyword)."
		});jQuery("#codestatus").validate({
			expression: "if (VAL) return true; else return false;",
			message: "Please enter status keyword."
		});
	});
	$('#notify_punch_code').click(function(){
		if($('#notify_punch_code').prop('checked')==true){
			$('#notify_punch_code_show').show();
		}else if($('#notify_punch_code').prop('checked')==false){
			$('#notify_punch_code_show').hide();
		}
	});
	$('#email_address').click(function(){
		if($('#email_address').prop('checked')==true){
			$('#email_address_show').show();
		}else if($('#email_address').prop('checked')==false){
			$('#email_address_show').hide();
		}
	});
	$('#mobile_number').click(function(){
		if($('#mobile_number').prop('checked')==true){
			$('#mobile_number_show').show();
		}else if($('#mobile_number').prop('checked')==false){
			$('#mobile_number_show').hide();
		}
	});
</script>
