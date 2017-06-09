<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js" type="text/javascript"></script>
<?php
echo $this->Html->css('dhtmlgoodies_calendar');
echo $this->Html->script('dhtmlgoodies_calendarnew1.js');
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
				<div class="clearfix"></div>
					<?php echo $this->Session->flash(); ?>	
				<div class="portlet mt-element-ribbon light portlet-fit ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
edit loyalty program form
</div>
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-gift font-red-sunglo"></i>
							<span class="caption-subject bold uppercase"> </span>
						</div>
					</div>
					<div class="portlet-body form">
						<?php echo $this->Form->create(array('controller' => 'smsloyalty','action'=> 'edit/'.$id,'enctype'=>'multipart/form-data','onsubmit'=>'return validation()'));?>
						<?php echo $this->Form->input('Smsloyalty.id',array('type'=>'hidden','value'=>$loyalty['Smsloyalty']['id'])); ?>
						<div class="form-group">
							<label>Program Name<span class="required_star"></span></label>    
							<div class="input"> 							
								<?php echo $this->Form->input('Smsloyalty.program_name',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'program_name','Placeholder'=>'Program Name','value'=>$loyalty['Smsloyalty']['program_name']))?>
							</div>
						</div>                                          
						<div class="form-group">
							<label>Opt-in List<span class="required_star"></span></label>
							<?php $groups[0]='Please select list';
							echo $this->Form->input('Smsloyalty.group_id', array('class'=>'form-control','label'=>false,'default'=>$loyalty['Smsloyalty']['group_id'],'type'=>'select','options' => $groups));
							?>
						</div>
						<div class="form-group">
							<label>Start Date<span class="required_star"></span></label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.start',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'startdate1','Placeholder'=>'Select Start Date','value'=>date('m/d/Y',strtotime($loyalty['Smsloyalty']['startdate'])),'onclick'=>"displayCalendar(startdate1,'mm/dd/yyyy',this)"));?>
							</div>
						</div> 
						<div class="form-group">
							<label>End Date<span class="required_star"></span></label>
							<div class="input">												
								<?php echo $this->Form->input('Smsloyalty.end',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'enddate1','Placeholder'=>'Select End Date','value'=>date('m/d/Y',strtotime($loyalty['Smsloyalty']['enddate'])),'onclick'=>"displayCalendar(enddate1,'mm/dd/yyyy',this)"));?>
						   </div>
						</div> 
						<div class="form-group">
							<label>Reach Goal After(# of visits or purchases)<span class="required_star"></span></label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.reachgoal',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'reachgoal','value'=>$loyalty['Smsloyalty']['reachgoal']))?>
							</div>
						</div>
						<div class="form-group">
							<label>Notify Punch Code</label>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Specify how you want to be notified with the punch code that is generated automatically daily." data-original-title="Notify Punch Code" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							
							<div class="radio-list" >				
								<input name="data[Smsloyalty][notify_punch_code]" type="checkbox" id="notify_punch_code" <?php if($loyalty['Smsloyalty']['notify_punch_code']==1){ echo "checked";} ?>/>
							</div>
						</div>
						<div id="notify_punch_code_show" <?php if($loyalty['Smsloyalty']['notify_punch_code']==1){ ?>style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
							<div class="form-group">
								<label>My Email Address(registered on your account)&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will send the punch code to the email address that is registered on your account." data-original-title="My Email Address" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][my_email_address]" type="checkbox" id="my_email_address" <?php if($loyalty['Smsloyalty']['my_email_address']==1){ echo "checked";} ?>/>
								</div>
							</div>
							<div class="form-group">
								<label>This Email Address&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will send the punch code to the email address you have entered below." data-original-title="This Email Address" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][email_address]" type="checkbox" id="email_address" <?php if($loyalty['Smsloyalty']['email_address']==1){ echo "checked";} ?>/>
								</div>
								<div class="input" id="email_address_show" <?php if($loyalty['Smsloyalty']['email_address']==1){ ?>style="display:block" <?php }else{ ?> style="display:none;" <?php } ?>>                                                   
									<!--<?php echo $this->Form->input('Smsloyalty.email_address_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'email_address_input','placeholder'=>'This Email Address','value'=>$loyalty['Smsloyalty']['email_address_input']));?>-->

<?php echo $this->Form->text('Smsloyalty.email_address_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'email_address_input','placeholder'=>'This Email Address','type'=>'email','value'=>$loyalty['Smsloyalty']['email_address_input']));?>
								</div>
							</div>
							<div class="form-group">
								<label>Mobile Number&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This will text the punch code to the number you enter below. Be aware that the punch code is generated between midnight and 2am server time, which will be the time you get the notification." data-original-title="Mobile Number" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
								<div class="radio-list" >				
									<input name="data[Smsloyalty][mobile_number]" type="checkbox" id="mobile_number" <?php if($loyalty['Smsloyalty']['mobile_number']==1){ echo "checked";} ?>/>
								</div>
								<div class="input" id="mobile_number_show" <?php if($loyalty['Smsloyalty']['mobile_number']==1){ ?>style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>                                                   
									<?php echo $this->Form->input('Smsloyalty.mobile_number_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'mobile_number_input','placeholder'=>'Mobile number with country code. US example: 12025248725','value'=>$loyalty['Smsloyalty']['mobile_number_input']))?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Punch Code(automatically generated daily)&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This is the code your customers will text in to get a punch added on their virtual card. Each day a new punch code will be generated automatically to prevent abuse of system." data-original-title="Punch Code" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="input">                                                   
								<?php echo $this->Form->input('Smsloyalty.coupancode',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'reachgoal','readonly'=>'readonly','style'=>'background-color:#eeeeee','value'=>$loyalty['Smsloyalty']['coupancode']))?>
							</div>
						</div> 
						<div class="form-group">
							<label>Status Keyword<span class="required_star"></span>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Keyword that user can text in to check their current status in the program." data-original-title="Status Keyword" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="input">                                                   
							  <?php echo $this->Form->input('Smsloyalty.codestatus',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'codestatus','value'=>$loyalty['Smsloyalty']['codestatus']))?>
							</div>
						</div> 
						<div class="form-group">
							<label>Add Points Message(Punch Code)<span class="required_star"></span>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message that is sent after user texts in a punch code to add a point to their account." data-original-title="Punch Code Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
								<?php echo $this->Form->input('Smsloyalty.addpoints',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'addpoints','maxlength'=>'160','value'=>$loyalty['Smsloyalty']['addpoints'],'onKeyup'=>'return update()'))?>
						</div>
						<div class="form-group">
							<label>Remaining Characters</label>
							<input type="text"  name="limit" id="limit" class="form-control input-xsmall" size=4 readonly value="160">
						</div>
						<?php if(API_TYPE==0){ ?>
						<div class="form-group">
							<div class="inline" >
								<?php if((!empty($numbers_sms))||($users['User']['sms']==1)){ ?>
						SMS <input type="radio" value="1" name="data[Smsloyalty][type]" id="sms" <?php if($loyalty['Smsloyalty']['type']==1){ echo "checked"; } ?> />
					<?php } ?>
					<?php if((!empty($numbers_mms))||($users['User']['mms']==1)){ ?>
						MMS <input type="radio" value="2" name="data[Smsloyalty][type]" id="mms" <?php if($loyalty['Smsloyalty']['type']==2){ echo "checked"; } ?>/>	
					<?php } ?>					  
							</div>
						</div>
						<div id='upload' <?php if($loyalty['Smsloyalty']['type']==2){?> style="display:block;" <?php }else{ ?>style="display:none;" <?php } ?> >
					<div class="feildbox" style="margin-bottom:0px">
						<!--<label for="some21">Image<span class="required_star"></span>&nbsp;
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please upload your reward offer image. Only images allowed." data-original-title="Image" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label>  
						<input type="file" id="files" name="data[Smsloyalty][image]" />
						<div id="resultpick"></div>
						<output id="result"/><br>-->


<!--*************************-->
<div data-provides="fileinput" class="fileinput fileinput-new">

                                                        <div style="width: 200px; height: 150px;" class="fileinput-new thumbnail">
                                                            <img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=new+image"> </div>
                                                        <div style="max-width: 200px; max-height: 150px; line-height: 10px;" class="fileinput-preview fileinput-exists thumbnail"></div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="hidden" value="" name="..."><input type="file" id="files" name="data[Smsloyalty][image]" onclick="check_image()" > </span>
                                                            <a data-dismiss="fileinput" class="btn red fileinput-exists" href="javascript:;"> Remove </a>
                                                        </div>
                                                    </div>
<!--**************************-->
						<?php if($loyalty['Smsloyalty']['image'] !=''){?>
							<!--<img src="<?php echo SITE_URL;?>/mms/<?php echo $loyalty['Smsloyalty']['image']; ?>" class="thumbnail" style="height:70px!important;width:70px!important;float: left;" />-->
<div style="max-width: 200px; max-height: 150px; line-height: 10px;" class="fileinput-exists thumbnail" ><img src="<?php echo SITE_URL;?>/mms/<?php echo $loyalty['Smsloyalty']['image']; ?>" class="thumbnail" style="max-width: 190px; max-height: 140px;margin-bottom:0;border:0;padding:0" /></div>
						<?php } ?>
					</div>
				</div>
						<?php } ?>
						<div class="form-group" >
							<label>Reach Goal Message(Reward Offer)<span class="required_star"></span>&nbsp;<!--<a rel="tooltip" title="Message sent after user has collected required number of points to reach reward goal. Redeem link will be appended." class="ico" style="font-weight:normal" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message sent after user has collected required number of points to reach reward goal. Redeem link will be appended to the message." data-original-title="Reward Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label>
								<?php echo $this->Form->input('Smsloyalty.reachedatgoal',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'reachedatgoal','maxlength'=>'160','value'=>$loyalty['Smsloyalty']['reachedatgoal'],'onKeyup'=>'return update1()'))?>
						</div>
						<div class="form-group">
							<label>Remaining Characters</label>
							<input type="text"  name="limit1" id="limit1" class="form-control input-xsmall" size=4 readonly value="160">
						</div>
						<div class="form-group">
						   <label>Check Status Message(Status Keyword)<span class="required_star"></span>&nbsp;<!--<a rel="tooltip" title="Message that is sent after user texts in status keyword to check their status." class="ico" style="font-weight:normal" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Message that is sent after user texts in status keyword to check their status." data-original-title="Status Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>

</label>
							<?php echo $this->Form->input('Smsloyalty.checkstatus',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'checkstatus','maxlength'=>'160','value'=>$loyalty['Smsloyalty']['checkstatus'],'onKeyup'=>'return update2()'))?>
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
	$(document).ready(function (){
		$('textarea[maxlength]').live('keyup change', function() {
			var str = $(this).val()
			var mx = parseInt($(this).attr('maxlength'))
			if (str.length > mx) {
				$(this).val(str.substr(0, mx))
			return false;
			}
		});
		
		
		
		$("#SmsloyaltyGroupId option").not(":selected").attr("disabled", "disabled");

			$("#SmsloyaltyGroupId option").not(":selected").attr("disabled", "");
	});

	window.onload = function(){
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
	}
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
	/*function validation(){
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
		if($('#startdate1').val()==''){
			alert( "Please select start date" );
			return false;
		}
		if($('#enddate1').val()==''){
			alert( "Please select end date" );
			return false;
		}
	}*/
	$('#mms').click(function(){
		$('#upload').show();
	});
	$('#sms').click(function(){
		$('#upload').hide();
	});
	/* <![CDATA[ */
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
/* ]]> */			
</script>
