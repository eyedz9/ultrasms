<style>
.ValidationErrors{
color:red;
}
</style>
<script>
$(document).ready(function (){
	$('#GroupMsgType1').click(function (){
		$('#text_to_voice').hide();
		$('#audio_path').show();
	});
	$('#GroupMsgType0').click(function (){
		$('#text_to_voice').show();
		$('#audio_path').hide();
	});	
});
function check_image(){
	if($('#mms').prop('checked')==true){
		$('#check_img_validation').val(2);
	}
}		
</script> 
<script>
jQuery(function(){
	jQuery("#groupname").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter groupname"
	});jQuery("#username").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter username"
	});jQuery("#keyword").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter keyword"
	});jQuery("#message5").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter message"
	});jQuery("#systemmessage").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter systemmessage"
	});;jQuery("#username").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter username"
	});

$('#notify_signup').click(function(){
		if($('#notify_signup').prop('checked')==true){
			$('#notify_signup_show').show();
		}else if($('#notify_signup').prop('checked')==false){
			$('#notify_signup_show').hide();
		}
	});

$('#grouptype').change(function(){
   if($('#grouptype').val() == 2){
	 $('#real_estate_show').show();
   }else {
	 $('#real_estate_show').hide();
   }
   if($('#grouptype').val() == 3){
	 $('#vehicle_show').show();
   }else {
	 $('#vehicle_show').hide();
   }
});
});		

$(document).ready(function(){
	if($('#sms').prop('checked')==true){
		$('#textmsg').show();
	}
	$('#mms').click(function(){
		$('#textmsg').hide();
		$('#upload').show();
	});
	$('#sms').click(function(){
		$('#upload').hide();
		$('#textmsg').show();
	});
});
var count = "107";
function update(){
	var tex = $("#message").val();

	var msg = $("#GroupAutoMessage").val();
	var count1 = (107-(msg.length));
	tex = tex.replace('{','');
	tex = tex.replace('}','');
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace("'","");
	tex = tex.replace('"','');
	var len = tex.length;
	$("#message").val(tex);
	if(len > count){
		tex = tex.substring(0,count1);
		//$("#message").val(tex);
		//$("#Preview").val(tex+msg);
		return false;
	}
	$("#limit").val(count-len);
}
function update2(){
	var tex = $("#message2").val();
	var msg = $("#GroupAutoMessage").val();
	var count1 = (107-(msg.length));
	tex = tex.replace('{','');
	tex = tex.replace('}','');
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace("'","");
	tex = tex.replace('"','');
	var len = tex.length;
	$("#message2").val(tex);
	if(len > count){
		tex = tex.substring(0,count1);
		//$("#message").val(tex);
		//$("#Preview").val(tex+msg);
		return false;
	}
	$("#limit3").val(count-len);
}
function update1(){
	var tex = $("#groupname").val();
	tex = tex.replace('{','');
	tex = tex.replace('`','');
	tex = tex.replace('}','');
	//tex = tex.replace(':','');
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace("'","");
	tex = tex.replace('"','');
	$("#groupname").val(tex);
}
window.onload = function(){
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
				//Only pics
				// if(!file.type.match(‘image’))
				if(file.type.match('image.*')){
				// continue;
				var picReader = new FileReader();
				picReader.addEventListener("load",function(event){
					var picFile = event.target;
					var div = document.createElement("div");
					div.style.cssText = 'width:80px;float: left;margin-left:5px;';
					div.innerHTML = "<img style='height:70px!important;width: 70px!important;' class='thumbnail' src='" + picFile.result + "'" +
					"title='preview image'/>";
					output.insertBefore(div,null);
				});
				//Read the image
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
function ValidateForm(form){
	if($('#mms').prop('checked')==true){
		var check=$('img').hasClass('thumbnail');
		var mms_image=document.getElementById("check_img_validation").value;
		if(mms_image==0 && check==false){
			alert( "Please Upload a image" );
			return false;
		}
		var message2=$('#message2').val();
	}
	if($('#sms').prop('checked')==true ){
		var sms_msg=document.getElementById("message").value;
		if(sms_msg==''){
			alert( "Please enter a message" );
			return false;
		}
	}
if($('#notify_signup').prop('checked')==true){
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

if($('#grouptype').val() == 2){
var address=$('#propertyaddress').val();
			if(address == ''){
				alert( "Please enter the property address" );
				return false;
			}
var price=$('#propertyprice').val();
			if(price == ''){
				alert( "Please enter the property price" );
				return false;
			}

}

if($('#grouptype').val() == 3){
var vehicle=$('#vehicleyear').val();
			if(vehicle== ''){
				alert( "Please enter the vehicle year" );
				return false;
			}
var make=$('#vehiclemake').val();
			if(make== ''){
				alert( "Please enter the vehicle make" );
				return false;
			}
var model=$('#vehiclemodel').val();
			if(model== ''){
				alert( "Please enter the vehicle model" );
				return false;
			}
var mileage=$('#vehiclemileage').val();
			if(mileage== ''){
				alert( "Please enter the vehicle mileage" );
				return false;
			}
var price=$('#vehicleprice').val();
			if(price== ''){
				alert( "Please enter the vehicle price" );
				return false;
			}


}

}
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Groups</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
				<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo SITE_URL;?>/groups/index">Groups</a>
				</li>
			</ul>  			
		</div>	
<?php
$alphasender = $users['User']['alphasender'];
$company = $users['User']['company_name'];
$placeholder =$company." Mobile Rewards: Thanks for joining! 4 msgs/month. T&C/Privacy @ http://mysite.com";

if ($alphasender == 1){
   $helptext = "<ol style='padding-left:10px'><li>Include your business or program name.</li><br/><li>Include number of messages that users will receive per month (Example: 'Get 5 msgs/month').</li><br/><li>Include a link to your T&C/Privacy Policy or a number where they can reach you at.</li><br/><li>If you are going to be sending messages from an alphanumeric sender ID, then you must offer your users the ability to opt out by writing to your support team, calling your support phone line, or texting STOP to your number. We recommend that you provide your users with a clear description in your terms of services.</li></ol>";

}else{
   $helptext = "<ol style='padding-left:10px'><li>Include your business or program name.</li><br/><li>Include number of messages that users will receive per month (Example: 'Get 5 msgs/month').</li><br/><li>Include a link to your T&C/Privacy Policy or a number where they can reach you at.</li><br/></ol>";
  
}
?>		
		<div class="clearfix"></div>
			<?php echo $this->Session->flash(); ?>	
		<div class="portlet mt-element-ribbon light portlet-fit">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
Create Group Form</div>				
<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-users font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"></span>
					</div>
				</div>
				<div class="portlet-body form">
				<?php echo $this->Form->create('Group',array('action'=> 'add','enctype'=>'multipart/form-data','onsubmit'=>' return ValidateForm(this)'));?>
				<div class="form-body">
					<div class="form-group">
						<label>Group Name <span for="exampleInputPassword1">*</span></label>
						<!--<div class="input-group"> -->                                                 
							<?php echo $this->Form->input('Group.group_name',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'groupname','onKeyup'=>'return update1()'))?>
							<!--<span class="input-group-addon">
								<i class="fa fa-users font-red"></i>
							</span>
						</div>-->
					</div>
					<input  type="hidden" value="<?php echo $user_id; ?>" name="data[Group][user_id]" />
					<div class="form-group">
						<label>Keyword <span for="exampleInputPassword1">*</span></label><!--<a rel="tooltip" title="Keyword that subscriber will text in to your number to join your contact list" class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Keyword that subscriber will text in to your number to join your contact list"   data-original-title="Keyword" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
						<!--<div class="input-group">-->
							<?php echo $this->Form->input('Group.keyword',array('div'=>false,'label'=>false, 'class' => 'form-control','value'=>'','id'=>'keyword'))?>
							<!--<span class="input-group-addon">
								<a rel="tooltip" title="Keyword that subscriber will text in to your number to join your contact list." class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>
							</span>-->
                                                       <!-- <span class="input-group-addon">
								<i class="fa fa-key font-red"></i>
							</span>
						</div>-->
					</div>
					<div class="form-group">
						<label>Group Type&nbsp;&nbsp;<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="<b>Coupon</b> - User can continually text in the keyword to receive the auto-reply message. e.g. You may update the message weekly or monthly with a new coupon code and user can text in to receive it. <br/><b>Join</b> - User can only text in the keyword once to be added to the group. <br/><b>Property (Real Estate)</b> - Perfect for realtors. Enter in all the property details, and those details will be sent back to the prospective buyer when they text in the keyword. <br/><b>Vehicle</b> - Perfect for car dealers. Enter in all the vehicle details, and those details will be sent back to the prospective buyer when they text in the keyword." data-original-title="Group Type" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></label>
						<?php	
							if (API_TYPE != 2){
                                                           $Option=array('0'=>'Coupon','1'=>'Join','2'=>'Property (Real Estate)','3'=>'Vehicle');
                                                        }else{
                                                           $Option=array('0'=>'Coupon','1'=>'Join');
                                                        }
							echo $this->Form->input('Group.group_type', array(
							'class'=>'form-control',
                                                 	'label'=>false,
                                                        'id'=>'grouptype',
							'type'=>'select',  'options' => $Option, 'default'=>'1'));
						?>
					</div>
<div id="real_estate_show" style="display:none;border:1px dotted #34495e;padding:15px;margin-bottom:10px;background-color:#eef1f5;">
<div style="text-align:left"><i aria-hidden="true" class="fa fa-home" style="font-size:30px;padding-bottom:15px;"></i>&nbsp;&nbsp;<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="Great for realtors! Create a group with detailed property information and when someone texts in a keyword for this property, it will return all the property details below in a text message back to the prospective buyer.<br/><br/><b>NOTE:</b> 1 credit is charged for each 160 character segment. Example - If your property information + the auto-reply message is 400 characters, 3 credits will be deducted from your account." data-original-title="Property Information" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></div>
							
								<label>Address</label>
						
								<div class="input" id="Address" >                                                   
									<?php echo $this->Form->input('Group.propertyaddress',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'propertyaddress','placeholder'=>'Address'))?>
								</div>
<label>Price</label>
						
								<div class="input" id="Price" >                                                   
									<?php echo $this->Form->input('Group.propertyprice',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'propertyprice','placeholder'=>'Price'))?>
								</div>
<label>Bed</label>
						<?php	
							$Option1=array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10');
							echo $this->Form->input('Group.property_bed', array(
							'class'=>'form-control input-small',
                                                 	'label'=>false,
                                                        'id'=>'bed',
							'type'=>'select',  'options' => $Option1, 'default'=>'1'));
						?>
							
<label>Bath</label>
						<?php	
							$Option2=array('1'=>'1','1.5'=>'1.5','2'=>'2','2.5'=>'2.5','3'=>'3','3.5'=>'3.5','4'=>'4','4.5'=>'4.5','5'=>'5','5.5'=>'5.5','6'=>'6');
							echo $this->Form->input('Group.property_bath', array(
							'class'=>'form-control input-small',
                                                 	'label'=>false,
                                                        'id'=>'bath',
							'type'=>'select',  'options' => $Option2, 'default'=>'1'));
						?>

<label for="some21">Property Description</label>
								<?php echo $this->Form->textarea('Group.propertydesc',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'propertydesc','maxlength'=>'200','value'=>'','placeholder'=>'Include details such as style of home, upgrades, school district, taxes, etc...'))?>
<label>Property URL</label>
						
								<div class="input" id="PropertyURL" >                                                   
									<?php echo $this->Form->input('Group.propertyurl',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'propertyurl','maxlength'=>'200','placeholder'=>'Property URL'))?>
								</div>
						
                                        </div>

<!--***************************-->

<div id="vehicle_show" style="display:none;border:1px dotted #34495e;padding:15px;margin-bottom:10px;background-color:#eef1f5;">
<div style="text-align:left"><i aria-hidden="true" class="fa fa-car" style="font-size:30px;padding-bottom:15px;"></i>&nbsp;&nbsp;<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="Great for car dealers! Create a group with detailed vehicle information and when someone texts in a keyword for this vehicle, it will return all the vehicle details below in a text message back to the prospective buyer.<br/><br/><b>NOTE:</b> 1 credit is charged for each 160 character segment. Example - If your vehicle information + the auto-reply message is 400 characters, 3 credits will be deducted from your account." data-original-title="Vehicle Information" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></div>
							
								<label>Year</label>
						
								<div class="input" id="Address" >                                                   
									<?php echo $this->Form->input('Group.vehicleyear',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'vehicleyear','placeholder'=>'Year'))?>
								</div>
<label>Make</label>
						
								<div class="input" id="Make" >                                                   
									<?php echo $this->Form->input('Group.vehiclemake',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'vehiclemake','placeholder'=>'Make'))?>
								</div>
<label>Model</label>
								<div class="input" id="Model" >                                                   
									<?php echo $this->Form->input('Group.vehiclemodel',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'vehiclemodel','placeholder'=>'Model'))?>
								</div>
<label>Mileage</label>
								<div class="input" id="Mileage" >                                                   
									<?php echo $this->Form->input('Group.vehiclemileage',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'vehiclemileage','placeholder'=>'Mileage'))?>
								</div>
<label>Price</label>
						<div class="input" id="Price" >                                                   
									<?php echo $this->Form->input('Group.vehicleprice',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'vehicleprice','placeholder'=>'Price'))?>
								</div>


<label for="some21">Vehicle Description</label>
								<?php echo $this->Form->textarea('Group.vehicledesc',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'vehicledesc','maxlength'=>'200','value'=>'','placeholder'=>'Include details such as type of interior, 2/4 door, upgrades, etc...'))?>

<label>Vehicle URL</label>
						
								<div class="input" id="VehicleURL" >                                                   
									<?php echo $this->Form->input('Group.vehicleurl',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'vehicleurl','maxlength'=>'200','placeholder'=>'Vehicle URL'))?>
								</div>
						
                                        </div>
<!--***************************************-->
<?php if($users['User']['birthdaywishes']==1){?>	
					<div class="form-group">
						<div class="radio-list"><label>Birthday SMS Wish&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="If you wish to capture birthdays for the purpose of sending out your birthday SMS wishes, this will enable/disable it for this group. If enabled here and under settings, a message will be sent back to the user to enter their birth date after subscribing to this group and also send them your birthday SMS wish." data-original-title="Birthday SMS Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a></label>
								On<input type="radio" style="margin-left:-8px" value="1" name="data[Message][bithday_enable]" />
								Off<input type="radio" style="margin-left:-7px" value="0" name="data[Message][bithday_enable]" checked />
						</div>
					</div>
<?php } ?>
					<?php if(API_TYPE==0){ ?>
					<div class="form-group">
						<div class="radio-list">
						   <?php		
							if((!empty($numbers_sms))||($users['User']['sms']==1)){ ?>
								SMS<input type="radio" style="margin-left:-8px" value="1" name="data[Message][msg_type]" id="sms" checked />
							<?php } ?>
							<?php if((!empty($numbers_mms))||($users['User']['mms']==1)){ ?>
								MMS<input type="radio" style="margin-left:-7px" value="2" name="data[Message][msg_type]" id="mms"/>	
							<?php } ?> 
						</div>
					</div>
					<?php if((empty($numbers_sms))&&($users['User']['sms']!=1)&&(empty($numbers_mms)) &&($users['User']['mms']!=1)){ ?>                                          
						<div class="form-group">
							<label>Message</label>&nbsp;<!--<a rel="tooltip" title="Include precise number of messages that users will receive per month (Example: 'Get 5 msgs/month')" class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->

<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="<?php echo $helptext?>" data-original-title="Recurring Marketing Compliance" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%CURRENTDATE%%</b> to output the current date in the auto-reply message that gets sent to your subscribers" data-original-title="Merge Tags" class="popovers"> <i class="fa fa-tags" style="font-size:18px"></i> </a>
								<?php echo $this->Form->textarea( 'Group.msg2',array('div'=>false,'label'=>false,'class' => 'form-control','id'=>'message','maxlength'=>'107','onKeyup'=>'return update()','value'=>'','placeholder'=>$placeholder))?>
								
								<div class="form-group">
								<label>Remaining Characters</label>
									<input type=text name=limit id=limit class="form-control input-xsmall" size=4 readonly value="107">
								</div>
								Special characters not allowed such as ~ { } [ ] ;									
						</div>
					<?php } ?>
					<?php }else{ ?>
					<div class="form-group">
						<input type="hidden" value="1" name="data[Message][msg_type]" id="sms" checked />
						<label>Message</label>&nbsp;<!--<a rel="tooltip" title="Include precise number of messages that users will receive per month (Example: 'Get 5 msgs/month')" class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="<?php echo $helptext?>" data-original-title="Recurring Marketing Compliance" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%CURRENTDATE%%</b> to output the current date in the auto-reply message that gets sent to your subscribers" data-original-title="Merge Tags" class="popovers"> <i class="fa fa-tags" style="font-size:18px"></i> </a>
							<?php echo $this->Form->textarea( 'Group.msg',array('div'=>false,'label'=>false,'class' =>'form-control','id'=>'message','maxlength'=>'107','onKeyup'=>'return update()','value'=>'','placeholder'=>$placeholder))?>
							
							<div class="form-group">
								<label>Remaining Characters</label>
									<input type=text name=limit id=limit class="form-control input-xsmall" size=4 readonly value="107">
							</div>
							Special characters not allowed such as ~ { } [ ] ;
					</div> 
					<?php } ?>
					<?php if(API_TYPE==0){ ?>											
						<div id='textmsg' style="display:none;">
							<div class="form-group">
								<label for="some21">Message<span class="required_star"></span></label>&nbsp;<!--<a rel="tooltip" title="Include precise number of messages that users will receive per month (Example: 'Get 5 msgs/month')" class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="<?php echo $helptext?>" data-original-title="Recurring Marketing Compliance" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i></a>  
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%CURRENTDATE%%</b> to output the current date in the auto-reply message that gets sent to your subscribers" data-original-title="Merge Tags" class="popovers"> <i class="fa fa-tags" style="font-size:18px"></i> </a>
								<?php echo $this->Form->textarea( 'Group.msg',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'message','maxlength'=>'107','onKeyup'=>'return update()','value'=>'','placeholder'=>$placeholder))?>
								
								<div class="form-group">
								<label>Remaining Characters</label>
									<input type=text name=limit id=limit class="form-control input-xsmall" size=4 readonly value="107">
								</div>
								Special characters not allowed such as ~ { } [ ] ;
							</div>
						</div>
						<div id='upload' style="display:none;" >
							<div class="form-group">
								<!--<label for="some21">Image <span class="required_star">*</span>&nbsp;
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please only upload a max of 10 images. This is the max number of images allowed by our SMS gateway." data-original-title="Images" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a> </label>-->
							
<!--<input type="file" id="files" name="data[Group][image][]"  multiple onclick="check_image()"/>-->
                                                        <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>
                                                                <!--<span class="fileinput-exists"> Change </span>-->
                                                                <input type="hidden" value="" name="..."><input type="file" id="files" name="data[Group][image][]" multiple onclick="check_image()" > </span>
                                                            <!--<a data-dismiss="fileinput" class="btn red fileinput-exists" href="javascript:;"> Remove </a>-->
                                                        </span>&nbsp;
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please only upload a max of 10 images. This is the max number of images allowed by our SMS gateway." data-original-title="Images" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>

								<input type="hidden" id="check_img_validation" value="0" />
								<br>
								<div id="result" style="margin-top:10px"></div>
							</div>
							
							<div class="form-group" style="margin-top:115px">
<div style="margin-top:-15px;padding-bottom:10px;">(Upload multiple images to return image galleries. Perfect for real estate, automotive, and other image-driven niches)</div>
								<label for="some21">Message<span class="required_star"></span></label>&nbsp;<!--<a rel="tooltip" title="Include precise number of messages that users will receive per month (Example: 'Get 5 msgs/month')" class="ico" href="#" title="help"><img style="border: medium none; width: auto;" src="<?php echo SITE_URL?>/img/help.png" alt="help"/></a> -->
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="<?php echo $helptext?>" data-original-title="Recurring Marketing Compliance" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a> 
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%CURRENTDATE%%</b> to output the current date in the auto-reply message that gets sent to your subscribers" data-original-title="Merge Tags" class="popovers"> <i class="fa fa-tags" style="font-size:18px"></i> </a>
								<?php echo $this->Form->textarea( 'Group.msg1',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'message2','maxlength'=>'107','onKeyup'=>'return update2()','value'=>'','placeholder'=>$placeholder))?>
								
								<div class="form-group">
								<label>Remaining Characters</label>
									<input type=text name=limit3 id=limit3 class="form-control input-xsmall" size=4 readonly value="107">
								</div>
								Special characters not allowed such as ~ { } [ ] ;
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label>Mandatory Appended Message</label>
						
							<?php echo $this->Form->input('Group.auto_message', array('type' => 'textarea', 'rows'=>'2', 'class'=>'form-control','escape' => false,'label'=>false,'value'=>'STOP to end. HELP for help. Msg&Data rates may apply','readonly'=>true,'style'=>'color:#808080')) ?>
						</div>

                                        <div class="form-group">
							<label>Notify Sign-up&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Have the option to be notified via SMS whenever someone subscribes to this group. The notification will include the number that subscribed and the group they subscribed to." data-original-title="Notify Sign-up" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="radio-list" >				
								<input name="data[Group][notify_signup]" type="checkbox" id="notify_signup" />
							</div>
					</div>
					<div id="notify_signup_show" style="display:none;">
							
							<div class="form-group">
								<label>Mobile Number&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This is the number that will be notified via SMS whenever someone subscribes/texts in the keyword for this group." data-original-title="Mobile Number" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
						
								<div class="input" id="mobile_number_show" >                                                   
									<?php echo $this->Form->input('Group.mobile_number_input',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'mobile_number_input','placeholder'=>'Mobile number with country code. US example: 12025248725'))?>
								</div>
							</div>
						
                                        </div>
					</div>
                                        
 
					<div class="form-actions">                                      
						<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
						<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'groups','action' => 'index'),array('class'=>'btn default')); ?>
					</div>
				<?php echo $this->Form->end(); ?>
            </div>
        </div>          
	</div>
</div>