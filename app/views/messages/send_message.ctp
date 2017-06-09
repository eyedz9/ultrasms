<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">

var SPINTAX_PATTERN = /\{[^"\r\n\}]*\}/;
var spin = function (spun) {
	var match;
	while (match = spun.match(SPINTAX_PATTERN)) {
		match = match[0];
		var candidates = match.substring(1, match.length - 1).split("|");
		spun = spun.replace(match, candidates[Math.floor(Math.random() * candidates.length)])
	}
	return spun;
}
var spin_countVariations = function (spun) {
	spun = spun.replace(/[^{|}]+/g, '1');
	spun = spun.replace(/\{/g, '(');
	spun = spun.replace(/\|/g, '+');
	spun = spun.replace(/\}/g, ')');
	spun = spun.replace(/\)\(/g, ')*(');
	spun = spun.replace(/\)1/g, ')*1');
	spun = spun.replace(/1\(/g, '1*(');
	return eval(spun);
}
function spintext(){
$("#Preview1").val(spin($('#message1').val())+ ' STOP to End');
var str = $("#Preview1").val();
var strutf8decode = utf8_decode($("#Preview1").val());

//alert(strutf8decode.length);
//bootbox.alert("asasas");

if($('#mms').prop('checked')==true){	
   var credits = 2;
}else if (str.length != strutf8decode.length){
   var credits = Math.ceil(str.length/70);
}else{
   var credits = Math.ceil(str.length/160);
}
characters.value = '# of Characters: ' + str.length;
//var credits = Math.ceil(str.length/160);
if (document.forms[0].elements["groups"].checked){
		var selectedValues = [];   
		var groupcnt=0;
		var holdgroupcnt=0;
		$("#KeywordId :selected").each(function(){
			selectedValues.push($(this).val()); 
		});
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) {
			holdgroupcnt = parseInt(holdgroupcnt) + parseInt(groupcnt);
			groupcnt = document.getElementById(selectedValues[i]).value;
		}
		holdgroupcnt = (parseInt(holdgroupcnt) + parseInt(groupcnt)) * parseInt(credits);
		document.getElementById("showCredits").style.display = "block";
		if (document.getElementById("smsbalance").value < holdgroupcnt){
			document.getElementById("creditsused").style.color= "red";
			document.getElementById("creditsused").innerHTML= holdgroupcnt;
		}else{
			document.getElementById("creditsused").style.color= "white";
			document.getElementById("creditsused").innerHTML= holdgroupcnt;
		}
	}else{
		var selectedValues = [];   
		var contactcnt=0;
		$("#KeywordId1 :selected").each(function(){
		selectedValues.push($(this).val()); 
		});
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) {   
		}
		document.getElementById("showCredits").style.display = "block";
		if (document.getElementById("smsbalance").value < (i * parseInt(credits))){
			document.getElementById("creditsused").style.color= "red";
			document.getElementById("creditsused").innerHTML= i * parseInt(credits);
		}else{
			document.getElementById("creditsused").style.color= "white";
			document.getElementById("creditsused").innerHTML= i * parseInt(credits);
		}
	}
}
function spinvariations(){
alert('# of possible variations: ' + spin_countVariations($('#message1').val())); /* 9 */
}
function spintext2(){
	$("#Preview1").val(spin($('#message2').val())+ ' STOP to End');
	var str = $("#Preview1").val();
	characters.value = '# of Characters: ' + str.length;
	//var credits = Math.ceil(str.length/160);
        var credits = 2;
	if (document.forms[0].elements["groups"].checked){
		var selectedValues = [];   
		var groupcnt=0;
		var holdgroupcnt=0;
		$("#KeywordId :selected").each(function(){
		selectedValues.push($(this).val()); 
		});  
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) { 
		holdgroupcnt = parseInt(holdgroupcnt) + parseInt(groupcnt);
		groupcnt = document.getElementById(selectedValues[i]).value; 
		}
		holdgroupcnt = (parseInt(holdgroupcnt) + parseInt(groupcnt)) * parseInt(credits);    
		document.getElementById("showCredits").style.display = "block"; 
		if (document.getElementById("smsbalance").value < holdgroupcnt){
		document.getElementById("creditsused").style.color= "red";
		document.getElementById("creditsused").innerHTML= holdgroupcnt ;
		}else{
		document.getElementById("creditsused").style.color= "white";
		document.getElementById("creditsused").innerHTML= holdgroupcnt;
		}
	}else{
		var selectedValues = [];   
		var contactcnt=0;
		$("#KeywordId1 :selected").each(function(){
		selectedValues.push($(this).val()); 
		});
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) {
		}
		document.getElementById("showCredits").style.display = "block";
		if (document.getElementById("smsbalance").value < (i * parseInt(credits))){
		document.getElementById("creditsused").style.color= "red";
		document.getElementById("creditsused").innerHTML= i * parseInt(credits);
		}else{
		document.getElementById("creditsused").style.color= "white";
		document.getElementById("creditsused").innerHTML= i * parseInt(credits);
		}
	}
}

function spinvariations2(){
alert('# of possible variations: ' + spin_countVariations($('#message2').val())); 
}

function checkrotate(){

	var rotate_number = $('#rotate_number').val();
	if(rotate_number==0){
	$('#rotate_number').val(1);
	}else{
	$('#rotate_number').val(0);
	}


}

function checkalphasender(){

	var alphasenderid = $('#alphasenderid').val();
	if(alphasenderid==0){
	$('#alphasenderid').val(1);
	}else{
	$('#alphasenderid').val(0);
	}

}
</script>
<script>
jQuery(function(){
	jQuery("#lastName").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter value"
	});

$('#alphasenderid').click(function(){
		if($('#alphasenderid').prop('checked')==true){
			$('#alphasenderid_show').show();
                        $('#message').val("");
                        $('#mandatory_message_show').hide();
                        $('#rotate_number').val(0);
                        $('#rotate_number').prop('disabled', true);
		}else if($('#alphasenderid').prop('checked')==false){
			$('#alphasenderid_show').hide();
                        $('#message').val("STOP to end");
                        $('#mandatory_message_show').show();
                        $('#alphasenderid_input').val("");
                        $('#rotate_number').prop('disabled', false);
                        
		}
	});

});		
</script>
<script type="text/javascript">
window.onload=function() {
	if (document.forms[0].elements["contacts"].checked){
	document.forms[0].elements["contacts"].onClick=showHidediv();
	}
	if (document.forms[0].elements["groups"].checked){
	document.forms[0].elements["groups"].onClick=showHide();
	}

}
$(document).ready(function (){



	$('textarea[maxlength]').live('keyup change', function() {
	var str = $(this).val()
	var mx = parseInt($(this).attr('maxlength'))
	if (str.length > mx) {
	$(this).val(str.substr(0, mx))
	return false;
	}
})
	if (document.forms[0].elements["groups"].checked){
	document.forms[0].elements["groups"].onClick=showHide();
	}
	if (document.forms[0].elements["contacts"].checked){
	document.forms[0].elements["contacts"].onClick=showHidediv();
	}
	var messageview= $('#message1').val();
	var id= $('#mobilespagesId').val();
	if(id>0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/messages/mobile_pages/"+id,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(messageview!=''){
					var data = response;
					if($('#sms').prop('checked')==true){
					$('#message1').val(data);
					}else{
					$('#message1').val(data);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(data);
					}
					$('#Preview1').val(data);
					return;
				}else{
					if($('#sms').prop('checked')==true){
					$('#message1').html(response);
					}else{
					$('#message1').html(response);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(response);
					}
					$('#Preview1').html(response);
				}
			}
		});
	}
});
function confirmmessage(id){
	var message= $('#message1').val();
	if(id>0){
		$.ajax({
		url: "<?php  echo SITE_URL ?>/messages/checktemplatedata/"+id,
		type: "POST",
		dataType: "html",
		success: function(response) {
				if(message!=''){
					var data = response;
					if($('#sms').prop('checked')==true){
					$('#message1').val(data);
					}else{
					$('#message1').val(data);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(data);
					}
					$('#Preview1').val(data);
					return;
				}else{
					if($('#sms').prop('checked')==true){
					$('#message1').html(response);
					}else{
					$('#message1').html(response);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(response);
					}
					$('#Preview1').html(response);
				}
			}
		});
	}
}
function confirmpagemessage(id){ 
	var messageview= $('#message1').val();
	if(id>0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/messages/mobile_pages/"+id,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(messageview!=''){
					var data = response;
					if($('#sms').prop('checked')==true){
					$('#message1').val(data);
					}else{
					$('#message1').val(data);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(data);
					}
					$('#Preview1').val(data);
					return;
				}else{
					if($('#sms').prop('checked')==true){
					$('#message1').val(response);
					}else{
					$('#message1').val(response);
					}
					if($('#mms').prop('checked')==true){
					$('#message2').val(response);
					}
					$('#Preview1').html(response);
				}
			}
		});
	}
}
function check_image(){
	if($('#mms').prop('checked')==true){
		$('#check_img_validation').val(2);
	}
}
function remove_image(){
	if($('#mms').prop('checked')==true){
		$('#check_img_validation').val(0);
	}
}
</script>
<script>
window.fbAsyncInit = function() {
	FB.init({
		appId      :'<?php echo FACEBOOK_APPID;?>', 
		status     : true, 
		cookie     : true, 
		xfbml      : true  
	});
};
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));
function Fblogin(){
	FB.login(function(response) {  
		if (response.authResponse) {
			$('#fbloginid').empty();	
			$('#fblogoutid').append("<table style='margin-top: 7px'><tr><td style='vertical-align:center;padding:0px'><img src='<?php echo SITE_URL?>/img/FBShare.png' title='Post this message to your Facebook wall.'/></td><td style='vertical-align:center;padding:0px'><input type='checkbox' onchange='logout()' value='0' name='data[Message][logout]' id='fblogout' checked></td></tr></table>");
			var opts = {
			message : document.getElementById('message1').value,
			};
		} else {  
		alert('User cancelled login or did not fully authorize.');  
		}  
	}, {scope: 'publish_actions,user_photos',display:'dialog'}); 
}	
function logout(){
	FB.logout(function(response) {  
		if (response.authResponse){  
			$('#fblogoutid').empty();	
			$('#fbloginid').append("<table style='margin-top: 7px'><tr><td style='vertical-align:center;padding:0px'><img src='<?php echo SITE_URL?>/img/FBShare.png' title='Post this message to your Facebook wall.'/></td><td style='vertical-align:center;padding:0px'><input type='checkbox' onchange='Fblogin()' value='1' name='data[Message][Fblogin]' id='fblog'></td></tr></table>");
			alert('Facebook logout sucessful. Message will not be shared on Facebook.');  
		}
	});	
}

</script>
<?php
echo $this->Html->css('jquery-ui-1.8.16.custom');
echo $this->Html->script('jquery-ui-timepicker-addon');
?>
<script>
function updateslooce(){
	var tex = $("#message1").val();
	var msg = $("#Preview1").val();
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace('"','');
	var len = tex.length;
	var count1 = (148-(len));
	$("#message1").val(tex);
	if(len > 148){
		tex = tex.substring(0,count1);
		$("#Preview1").val(tex);
		return false;
	}
	$("#limit2").val(count1);
	$("#Preview1").val(tex);
	$("#Preview1").val(tex + " STOP to End");
	var str = $("#Preview1").val();
	characters.value = '# of Characters: ' + str.length;
}
var count = "1588";
function update(){
	var tex = $("#message1").val();
	var msg = $("#Preview1").val();
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace('"','');
	var len = tex.length;
	var count1 = (1588-(len));
	$("#message1").val(tex);
	if(len > count){
		tex = tex.substring(0,count1);
		$("#Preview1").val(tex);
		return false;
	}
	$("#limit2").val(count1);
	$("#Preview1").val(tex + " STOP to End");
	var str = $("#Preview1").val();
	characters.value = '# of Characters: ' + str.length;

        //var strutf8decode = utf8_decode($("#Preview1").val());

        //if (str.length != strutf8decode.length){
        //    alert("Your message contains a unicode character. Messages containing non-GSM(unicode) characters will be charged 1 credit for each 70 character segment.");
        //}


}
function update1(){
	var tex = $("#message2").val();
	var msg = $("#Preview1").val();
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace('"','');
	var len = tex.length;
	var count1 = (1588-(len)); 
	$("#message2").val(tex);
	if(len > count){
		tex = tex.substring(0,count1);
		$("#Preview1").val(tex);
		return false;
	}
	$("#limit3").val(count1);
	$("#Preview1").val(tex);
	$("#Preview1").val(tex + " STOP to End");
	var str = $("#Preview1").val();
	characters.value = '# of Characters: ' + str.length;
}
function showHide() {
    var ele = document.getElementById("showHideDiv");
    var ele1 = document.getElementById("showHideDiv1");
    var ele2 = document.getElementById("showHideDiv2");
    if(ele.style.display == "block") {
		ele.style.display = "block";
		ele1.style.display = "none";
		ele2.style.display = "block";
    }
    else {
		ele.style.display = "block";
		ele1.style.display = "none";
		ele2.style.display = "block";
    }
}
function showHidediv() {
    var ele = document.getElementById("showHideDiv");
	var ele1 = document.getElementById("showHideDiv1");
	var ele2 = document.getElementById("showHideDiv2");
    if(ele1.style.display == "block") {
		ele1.style.display = "block";
		ele2.style.display = "block";
		ele.style.display = "none";
			
    }
    else {
        ele.style.display = "none";
        ele2.style.display = "block";
        ele1.style.display = "block";
    }
}
function ValidateForm(form){

       if($('#alphasenderid').prop('checked')==true ){
          var alphasender = document.getElementById("alphasenderid_input").value;
          if (alphasender == ''){
             alert ("Please enter an alphanumeric sender ID");
             return false;
          }
        }
	var option1 = document.getElementById("groups").checked;
	var option2 = document.getElementById("contacts").checked;
	if(option1==false && option2==false){
		alert('Please pick a list, groups or contacts');
		return false;
	}
	var optionnew1 = document.getElementById("groups").value;
	var optionnew2 = document.getElementById("contacts").value;
	if(document.getElementById("KeywordId").value=='' && document.getElementById("KeywordId1").value=='')
	{
		alert( "Please select at least one from the list" );
		return false;
	}
	if($('#mms').prop('checked')==true){
		var mms_image=document.getElementById("check_img_validation").value;
		var pk_file=$('#pick_button').val();
		if(mms_image==0 && pk_file == ''){
			alert( "Please Upload a image" );
			return false;
		}
		var message2=$('#message2').val();	
	}
	if($('#sms').prop('checked')==true){
		var sms_msg=document.getElementById("message1").value;
		if(sms_msg==''){
			alert( "Please enter a message" );
			return false;
		}
	}
	if (document.getElementById("api_type").value==1 || document.getElementById("api_type").value==2 || document.getElementById("api_type").value==3){
		var sms_msg=document.getElementById("message1").value;
		if(sms_msg==''){
			alert( "Please enter a message" );
			return false;
		}
	}
	if (document.getElementById("api_type").value==0){
		if (document.forms[0].elements["sms"].checked){
			$("#Preview1").val(spin($('#message1').val())+ ' STOP to End');
		}else{
			$("#Preview1").val(spin($('#message2').val())+ ' STOP to End');
		}
	}else{
		$("#Preview1").val(spin($('#message1').val())+ ' STOP to End');
	}
	var str = $("#Preview1").val();
	characters.value = '# of Characters: ' + str.length;
        var strutf8decode = utf8_decode($("#Preview1").val());

//alert(strutf8decode.length);
//alert(str.length);

if($('#mms').prop('checked')==true){	
   var credits = 2;
   var mms = 1;
}else if (str.length != strutf8decode.length){
   var credits = Math.ceil(str.length/70);
   var unicode = 1;
}else{
   var credits = Math.ceil(str.length/160);
   var unicode = 0;
   var mms = 0;  
}

	if (document.forms[0].elements["groups"].checked){
			var selectedValues = [];   
			var groupcnt=0;
			var holdgroupcnt=0;
			$("#KeywordId :selected").each(function(){
			selectedValues.push($(this).val()); 
		});
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) {
			holdgroupcnt = parseInt(holdgroupcnt) + parseInt(groupcnt);
			groupcnt = document.getElementById(selectedValues[i]).value;
		}
		holdgroupcnt = (parseInt(holdgroupcnt) + parseInt(groupcnt)) * parseInt(credits);
		document.getElementById("showCredits").style.display = "block";

		if (document.getElementById("smsbalance").value < holdgroupcnt){
		document.getElementById("creditsused").style.color= "red";
		document.getElementById("creditsused").innerHTML= holdgroupcnt;
		
if (unicode == 0 && mms == 0){
alert('You do not have enough credits to send this message.');
}else if(mms == 1){
alert('You do not have enough credits to send this message. Keep in mind this message type is MMS and requires 2 credits per contact.');
}else if(unicode == 1){
alert('You do not have enough credits to send this message. This message contains unicode characters and any SMS that includes 1 or more non-GSM characters will be charged 1 credit for each 70 character segment.');
}
		return false;
		}else{
		document.getElementById("creditsused").style.color= "white";
		document.getElementById("creditsused").innerHTML= holdgroupcnt;

if (unicode == 1 && str.length > 70){
var r = confirm ("This message contains unicode characters and is over 70 characters in length. Any SMS that includes 1 or more non-GSM characters will be charged 1 credit for each 70 character segment. Do you still want to send?");

if (r == true) {
    return true;
} else {
    return false;
} 
}
		}
	}else{
		var selectedValues = [];   
		var contactcnt=0;
		$("#KeywordId1 :selected").each(function(){
			selectedValues.push($(this).val()); 
		});
		for (var i=0, iLen=selectedValues.length; i<iLen; i++) { 
		}
		document.getElementById("showCredits").style.display = "block";
		if (document.getElementById("smsbalance").value < (i * parseInt(credits))){
			document.getElementById("creditsused").style.color= "red";
			document.getElementById("creditsused").innerHTML= i * parseInt(credits);
			if (unicode == 0 && mms == 0){
alert('You do not have enough credits to send this message.');
}else if(mms == 1){
alert('You do not have enough credits to send this message. Keep in mind this message type is MMS and requires 2 credits per contact.');
}else if(unicode == 1){
alert('You do not have enough credits to send this message. This message contains unicode characters and any SMS that includes 1 or more non-GSM characters will be charged 1 credit for each 70 character segment.');
}
			return false;
		}else{
		document.getElementById("creditsused").style.color= "white";
		document.getElementById("creditsused").innerHTML= i * parseInt(credits);

if (unicode == 1 && str.length > 70){
var r = confirm ("This message contains unicode characters and is over 70 characters in length. Any SMS that includes 1 or more non-GSM characters will be charged 1 credit for each 70 character segment. Do you still want to send?");

if (r == true) {
    return true;
} else {
    return false;
} 
}
		}
	}



}


function CalcCredits(){
}
function popmessagepickwidget(value){
	if($('#sms').prop('checked')==true){
		$('#message1').val(value);
		$('#Preview1').val(value);
		$('#pick_button').val('');
	}else if($('#mms').prop('checked')==true){
		$('#message3').val(value);
		$('#Preview1').val();
		$('#resultpick').html('<div style="width: 80px;"><img style="height:70px!important;width: 70px!important;" class="thumbnail" title="preview" src='+value+'></div>');
		$('#pick_button').val('set');
	}
}
function popmessagepickwidgetnexmo(value){
$('#message1').val(value);
$('#Preview1').val(value);
$('#pick_button').val('');
}
</script>
<script>
$(document).ready(function(){
	if($('#sms').prop('checked')==true){
		$('#textmsg').show();
		$('#pickfile').show();
	}
	$('#mms').click(function(){
		$('#textmsg').hide();
		$('#upload').show();
		$('#pickfile').hide();
	});
	$('#sms').click(function(){
		$('#upload').hide();
		$('#textmsg').show();
		$('#pickfile').show();
	});
});
/*window.onload = function(){
	var count=1;
	if(window.File && window.FileList && window.FileReader)
	{
		$('#files').live("change", function(event) {
			var files = event.target.files; 
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
					div.innerHTML = "<img style='height:70px!important;width: 70px!important;' class='thumbnail' src='" + picFile.result + "'" +
					"title='preview image'/>";
					output.insertBefore(div,null);
					});
					$('#clear, #result').show();
					picReader.readAsDataURL(file);
					check_image();
				}else{
					alert("You can only upload image file.");
					$(this).val("");
				}
			}
		});
	}
}*/

function twitterlogin(){
window.location.href = "<?php echo SITE_URL?>/messages/send_message_twitter";
}
</script>

<style>

.feildbox img {
    border: none;
    width: auto;
}
.ValidationErrors{
color:red;
}
.showmessage{
  float: right;
    height: 445px;
    margin-right: 0;
    width: 273px;
}

@media only screen and ( max-width:480px ){

.picklist{ margin-top:450px !important;}
}
</style>

<div class="page-content-wrapper">
	<div class="page-content">    
	<?php  $group_sms_id=$this->Session->read('groupsmsid');?>
		<h3 class="page-title"> <?php __('Send Messages');?>
			<small></small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span><?php __('Send Messages');?></span>
				</li>
			</ul>  
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
					<?php
					$navigation = array(
						'Send Message' => '/messages/send_message',
						'View Group Scheduled Messages' => '/messages/schedule_message',
						'View Contacts Scheduled Messages' => '/messages/singlemessages',
					);
					if(isset($group_sms_id)){
						$navigation = array(
						'Send Message' => '/messages/send_message',
						'View Group Scheduled Messages' => '/messages/schedule_message',
						'View Contacts Scheduled Messages' => '/messages/singlemessages',
						'View Sent Statistics' => '/logs/sentstatistics/'.$group_sms_id,
						);
						$this->Session->delete('groupsmsid',$group_sms_id);     
								}
						$matchingLinks = array();
						foreach ($navigation as $link) {
								if (preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base)))) {
										$matchingLinks[strlen($link)] = $link;
								}
						}
						krsort($matchingLinks);
						$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
						$out = array();
						foreach ($navigation as $title => $link){
						if ($title=='View Sent Statistics'){
								$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'),array('style' => 'background:#e34b00;color:#fff'))).'</li>';}
							else{
								$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
							}
						}
						echo join("\n", $out);
						$sms_balance=$this->Session->read('User.sms_balance');
						$assigned_number=$this->Session->read('User.assigned_number');
						$active=$this->Session->read('User.active');
						$pay_activation_fees_active=$this->Session->read('User.pay_activation_fees_active');
						?>			
					</ul>
				</div>
			</div>				
		</div>
<?php
$alphasender = $users['User']['alphasender'];
?>	
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<?php 
			if($active == 1 && $assigned_number !=0 && $sms_balance > 0){ 
			if((empty($numbers_sms))&&($users['User']['sms']==0)){
		?>
		<div class="portlet light">
<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-comments-o font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> <?php __('Send Messages');?></span>
				</div>
			</div>
			<div class="portlet-body form">
				<h3 style="margin-top:5px">
				You need to get a SMS enabled online number to use this feature.</h3>
				<br>
				<b>Purchase Number to use this feature by </b>
				<?php
				if(API_TYPE==0){
				echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}else if(API_TYPE==1){
				echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}else if(API_TYPE==3){
				echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}?>
			</div>
		</div>
		<?php }else{ ?>
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-comments-o font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> <?php __('Send Messages');?></span>
				</div>
			</div>
			<div class="portlet-body form">
				<input type="hidden" id="smsbalance" value="<?php echo $sms_balance?>"/>
				<input type="hidden" id="api_type" value="<?php echo API_TYPE?>"/>
				<div id="showCredits" style="display:block">
					 <!--<table class="table table-bordered table-striped" id="user" >
						 <tbody>
							<tr>
								<td style="width:100%">
									<span class="text-muted"> Credits Available: <font style="color: #83c03a; font-size:16px"><?php echo $sms_balance?></font> Credits Needed: </span>
									<span class="text-muted" id="creditsused" style="font-size:16px"></span>
								</td>
							</tr>
						</tbody>
					</table>-->
<!--<div class="note note-info"><span class="text-muted"> Credits Available: <font style="color: #83c03a; font-size:16px"><?php echo $sms_balance?></font> Credits Needed: </span>
<span class="text-muted" id="creditsused" style="font-size:16px"></span>
</div>-->

<div class="tiles" style="margin-right:0px">
<div class="tile double selected bg-green">
                                   
                                    <div class="tile-body">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Primary Number </div>
                                        <div class="number" style="font-size:17px"> <?php echo $loggedUser['User']['assigned_number']?> </div>
                                    </div>
</div>
<div class="tile double selected bg-red-sunglo">
                                
                                    <div class="tile-body">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Credits Available </div>
                                        <div class="number" style="font-size:17px"> <?php echo $sms_balance ;?> </div>
                                    </div>
</div>
<div class="tile double selected bg-blue-hoki">
                                
                                    <div class="tile-body">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Credits Needed </div>
                                        <div class="number" style="font-size:17px"> <span id="creditsused"></span> </div>
                                    </div>
</div>
<div class="tile double selected bg-grey-cascade">
                                 
                                    <div class="tile-body">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Local Time </div>
                                        <div class="number" style="font-size:14px"> <?php echo date('Y-m-d h:i:s A')?> </div>
                                    </div>
</div>
</div>
				</div>
				<!--<table class="table table-bordered table-striped" >
					<tbody>
						<tr>
							<td>
								<span class="text-muted"  id="timezone"> Local Time:  <?php echo date('Y-m-d h:i:s A')?> </span>
							</td>
						</tr>
					</tbody>
				</table>-->
				<?php echo $this->Form->create('Message',array('action'=> 'send_message','name'=>'formname','enctype'=>'multipart/form-data','onsubmit'=>' return ValidateForm(this)'));?>
					<div class="form-body">
							<div class="showmessage" >
								<div style="float: right; margin-top: 0px; margin-right: 26px; width: 223px;"
								class="feildbox"><label>Preview</label>
									<?php echo $this->Form->input('Preview', array('type' => 'textarea', 'cols' => '15','rows'=>'12', 'class'=>'form-control','escape' => false,'label'=>false,'div'=>false,'id'=>"Preview1",'readonly'=>true,'style'=>'font-size:20px','maxlength'=>'160','data-role'=>'none')); ?>
									<input type="text" id="characters" value="# of Characters: " class="form-control" size="25" readonly style="margin-top:5px"/>
								</div>
							</div>
							<div class="form-group">


<?php if ($alphasender == 1){?>
<div class="form-group">
							<label>Send from Alphanumeric Sender ID&nbsp;<a href="javascript:;" data-container="body" data-html="true" data-trigger="hover" data-content="You can send from an alphanumeric sender ID, however you must offer your users the ability to opt out by writing to your support team, calling your support phone line, or texting STOP to your actual number.<br/><br/>We recommend that you provide your users with a clear description in your terms of services or when they intially sign-up." data-original-title="Alphanumeric Sender ID" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="radio-list" >				
								<input name="data[Message][alphasenderid]" value="0" type="checkbox" id="alphasenderid" onclick="checkalphasender()"/>
							</div>
					</div>
					<div id="alphasenderid_show" style="display:none;width:50%">
							
							<div class="form-group">
								<label>Sender ID&nbsp;<a href="javascript:;" data-container="body" data-html="true" data-trigger="hover" data-content="You may use any combination of 1 to 11 letters(A-Z/a-z) and numbers(0-9). 1 letter and no more than 11 alphanumeric characters may be used." data-original-title="Character Requirements" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								</label>
						
								                                                  
									<?php echo $this->Form->input('Message.alphasenderid_input',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'alphasenderid_input','maxlength'=>'11','placeholder'=>'Aphanumeric Sender ID','pattern'=>'[a-zA-Z0-9]+', 'title'=>'Alphanumeric Sender ID should only contain any combination of 1 to 11 letters(A-Z/a-z) and numbers(0-9)'))?>
								
							</div>
						
                                        </div>
<?php } ?>


								<div class="picklist">
									<label for="some21">Pick List&nbsp;<span class="required_star"></span></label>	
										<?php if($groupid!= '') { ?>
										<input type="radio" id="groups" name="data[pick][id]" value="1" checked onclick="return showHide();" /> Groups
										<?php }else{ ?>
										<input type="radio" id="groups" name="data[pick][id]" value="1" onclick="return showHide();" /> Groups
										<?php } ?>
										
										<?php if($contactid != '') { ?>
										<input type="radio" id="contacts" name="data[pick][id]" value="2" checked onclick="return showHidediv();" /> Contacts
										<?php }else{ ?>
										<input type="radio" id="contacts" name="data[pick][id]" value="2" onclick="return showHidediv();" /> Contacts
										<?php } ?>
								</div>		
							</div>
							<div id="showHideDiv" style="display:none;" class="form-group" style="width:200px;margin-top:5px">	
								<label for="some21">Groups<span class="required_star"></span></label>	
								<select id="KeywordId" class="form-control input-xlarge" multiple="multiple" name="data[Keyword][id][]" >
									<?php
									foreach( $Group as $Groups){ 
									if($Groups['Group']['keyword']!='?')
									{
									?>
									<option <?php if($Groups['Group']['id']==$groupid){?>selected <?php }?>value="<?php echo $Groups['Group']['id']; ?>">		<?php echo ucwords($Groups['Group']['group_name']).'('.$Groups['Group']['totalsubscriber'].')'; ?></option>
									<?php }} ?>
								</select>
									<?php
									foreach( $Group as $Groups){ 
									if($Groups['Group']['keyword']!='?')
									{
									?>
									<input type="hidden" id="<?php echo $Groups['Group']['id']; ?>" value="<?php echo $Groups['Group']['totalsubscriber']; ?>"/>
									<?php }} ?>
								<input type="hidden" id="holdcount" value="0"/>
							</div>
							<div id="showHideDiv1" style="display:none;">
								<label for="some21">Name|Group Name|Phone Number<span class="required_star"></span></label>	
								<select id="KeywordId1" class="form-control input-xlarge" multiple="multiple"
									  name="data[Contact][phone][]" >
									<?php
									foreach( $contactnumber as $contactnumber){ 
									if($contactnumber['Contact']['name']==''){
									$name= "";
									}else{
									$name=$contactnumber['Contact']['name'];
									}
									?>
									<option <?php if($contactnumber['Contact']['id']==$contactid){?>selected <?php }?> style="text-align:left;" value="<?php echo $contactnumber['Contact']['id'];?>"><?php echo $name; ?> <b>|</b> <?php echo $contactnumber['Group']['group_name']; ?> <b>|</b> <?php echo $contactnumber['Contact']['phone_number']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group" id="showHideDiv2" style="display:none;margin-top:5px">
								<label>Schedule Delivery <!--<a rel="tooltip" title="Please schedule your messages to be sent from 8:00AM - 9:00PM local time to adhere to the applicable guidelines published by the CTIA and the Mobile Marketing Association." class="ico" href="#" title="help" style="font-weight:normal"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please schedule your messages to be sent from 8:00AM - 9:00PM local time to adhere to the applicable guidelines published by the CTIA and the Mobile Marketing Association" data-original-title="Schedule Time" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>

</label>
								<?php 
								echo $this->Form->input('User.shedule',array('div'=>false,'label'=>false, 'class' => 'form-control input-medium','id'=>'sent_on','value'=>'Select Time'))
								?>								
								(To send now, don't select a date)
							</div> 
							<div class="form-group">
								<label>Message Template</label>
								<?php
								$Smstemplate[0]='Please select';
								echo $this->Form->input('Smstemplate.id', array(
								'class'=>'form-control input-xlarge',
								'label'=>false,
								'default'=>0,
								'type'=>'select',
								'onchange'=>'confirmmessage(this.value)',
								'options' => $Smstemplate));
								?>
							</div>
							<div class="form-group">
								<label>Mobile Splash Page</label>
								<select id="mobilespagesId" class="form-control input-xlarge"  name="data[mobilespages][id]" onchange="confirmpagemessage(this.value)">
									<?php
									$mobilespages[0]="Please Select";	
									foreach($mobilespages as $row => $value){
									$selected = '';
									if($row == $mobilepageid){
									$selected = ' selected="selected"';
									}?>
									<option "<?php echo  $selected; ?> " value="<?php echo  $row; ?>"><?php echo  $value; ?></option>
									<?php }
									?>
								</select>
							</div>
							<div class="form-group" id="socialsharing">
							<?php if (FBTWITTERSHARING == 0){?>
							<?php if (FACEBOOK_APPID != ''){?>
								<div class="checkbox-list">
									<label class="checkbox-inline">
									<span>
									<img src="<?php echo SITE_URL?>/img/FBShare.png" title="Post this message to your Facebook wall."/>
									</span>
									</label>
									<label class="checkbox-inline" style="margin-left:0px;padding-left:0px">
										<span>
											<input style="margin: -5px 0px 0px;" type="checkbox" onchange="Fblogin()" value="1" name='data[Message][Fblogin]' id="fblog">
										</span>
									</label>
									<label class="checkbox-inline" style="margin-left:0px;padding-left:0px">
										<span>
											<div id="fblogoutid">
											</div>
										</span>
									</label>
									<label class="checkbox-inline" style="margin-left:0px;padding-left:0px">
										<span>
											<div id="fblogoutidtol" >
<!--												<a rel="tooltip" title="Checking this box will post the message to your Facebook wall after clicking send. If you are sending multiple MMS images, then only the 1st image will be posted to your wall." class="ico" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i>
										</a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Checking this box will post the message to your Facebook wall after clicking send. If you are sending multiple MMS images, then only the 1st image will be posted to your wall" data-original-title="Facebook Post" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
											</div>	
										</span>
									</label>
								</div>
									<?php } ?>
									<?php } ?>
							</div>
							<?php if(API_TYPE==0){ ?>	
							<div class="form-group">
								<div class="radio-list">
									<?php
									if((!empty($numbers_sms))||($users['User']['sms']==1)){ ?>
									SMS<input type="radio" value="1" name="data[Message][msg_type]" id="sms" checked />
									<?php } ?>
									<?php if((!empty($numbers_mms))||($users['User']['mms']==1)){ ?>
									MMS<input type="radio" value="2" name="data[Message][msg_type]" id="mms"/>	
									<?php } ?>
								</div>
							</div>
							<?php if (FILEPICKERON == 'Yes'){?>
							<div id="pickfile" style="display:none;margin-bottom:10px">
								<input onchange="popmessagepickwidget(event.fpfile.url)" data-fp-container="modal" 	data-fp-mimetypes="*/*" data-fp-apikey=<?php echo FILEPICKERAPIKEY?> type="filepicker">	
								<input type="hidden" name="data[Message][pick_button]" value="" id="pick_button" />
							</div>
							<?php } ?>
							<?php } else if(API_TYPE==2){?>
							<?php if (FILEPICKERON == 'Yes'){?>
<div style="margin-bottom:10px">
							<input onchange="popmessagepickwidgetnexmo(event.fpfile.url)" data-fp-container="modal" data-fp-mimetypes="*/*" data-fp-apikey=<?php echo FILEPICKERAPIKEY?> type="filepicker">	
							<input type="hidden" name="data[Message][pick_button]" value="" id="pick_button" />
</div>
							<?php } ?>
							<?php 
							if(isset($short_url)){
							$short_url_msg = $short_url;
							}else{
							$short_url_msg = '';
							}	
							?>
							<div class="form-group">
								<label for="some21">Message<span class="required_star"></span></label>  
								<?php echo $this->Form->textarea( 'Keyword.message',array('div'=>false,'label'=>false,'class' => 'form-control input-xlarge','rows'=>'6','id'=>'message1','maxlength'=>'148','onKeyup'=>'return updateslooce()','value'=>$short_url_msg))?>
							</div>
							<div  class="form-group" style="margin-bottom:5px">Remaining Characters
								<input type=text name=limit2 id=limit2   class="form-control input-xsmall" size=4 readonly value="148">
							</div>
								Special characters not allowed such as ~ [ ] ; "
							<?php } else{?>
							<?php if (FILEPICKERON == 'Yes'){?>
<div style="margin-bottom:10px">
							<input onchange="popmessagepickwidgetnexmo(event.fpfile.url)" data-fp-container="modal" data-fp-mimetypes="*/*" data-fp-apikey=<?php echo FILEPICKERAPIKEY?> type="filepicker">	
							<input type="hidden" name="data[Message][pick_button]" value="" id="pick_button" />
</div>
							<?php } ?>
							<?php 
							if(isset($short_url)){
								$short_url_msg = $short_url;
							}else{
								$short_url_msg = '';
							}	
							?>
							<div class="form-group">
								<label for="some21">Message<span class="required_star"></span></label>&nbsp;<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="1 credit is charged for each 160 character segment. If you have a message that is 300 characters, and you are sending to 10 people, 20 credits will be deducted (2 credits for each person).<br/><br/><b>NOTE:</b> Messages containing non-GSM(unicode) characters will be charged 1 credit for each 70 character segment." data-original-title="Credits" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%Name%%</b> to output the name of the subscriber in the message if a name exists for one" data-original-title="Merge Tags" class="popovers"><i class="fa fa-tags" style="font-size:18px"></i></a>
								<?php echo $this->Form->textarea( 'Keyword.message',array('div'=>false,'label'=>false,'class' => 'form-control input-xlarge','rows'=>'6','id'=>'message1','maxlength'=>'1588','onKeyup'=>'return update()','value'=>$short_url_msg))?>
								<?php if(API_TYPE !=2){?>
								<input type="button" value="Variations" onclick="spinvariations();" class="btn blue btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px"/>&nbsp;<input type="button" value="Spin {|}" onclick="spintext();" class="btn purple btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px"/>&nbsp;<?php echo $this->Html->link($this->Html->image('note-error.png'), array('action' => 'spinhelp'), array('escape' =>false, 'class' => 'nyroModal','style'=>'margin-top:10px')); ?>
								<?php } ?>
							</div>
							<div  class="form-group" style="margin-bottom:5px">Remaining Characters
								<input type=text name=limit2 id=limit2   class="form-control input-xsmall" size=4 readonly value="1588">
							</div>
								Special characters not allowed such as ~ [ ] ; "
								<?php } ?>
								<input type="hidden" name="data[Message][pick_file]" id="message3" value=""/>
								<?php if(API_TYPE==0){ ?>
								<?php 
								if(isset($short_url)){
								$short_url_msg = $short_url;
								}else{
								$short_url_msg = '';
								}	
								?>
							<div id='textmsg' style="display:none;">
								<div class="form-group">
									<label for="some21">Message<span class="required_star"></span>&nbsp;<!--<a rel="tooltip" title="1 credit is charged for each 160 character segment. If you have a message that is 300 characters, and you are sending to 10 people, 20 credits will be deducted (2 credits for each person). NOTE: Messages containing unicode characters will be charged for each 70 character segment." class="ico" href="#" title="help" style="font-weight:normal"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="1 credit is charged for each 160 character segment. If you have a message that is 300 characters, and you are sending to 10 people, 20 credits will be deducted (2 credits for each person).<br/><br/><b>NOTE:</b> Messages containing non-GSM(unicode) characters will be charged 1 credit for each 70 character segment." data-original-title="Credits" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%Name%%</b> to output the name of the subscriber in the message if a name exists for one" data-original-title="Merge Tags" class="popovers"><i class="fa fa-tags" style="font-size:18px"></i></a>

</label>  
									<?php echo $this->Form->textarea( 'Keyword.message',array('div'=>false,'label'=>false,'class' => 'form-control input-xlarge','rows'=>'6','id'=>'message1','maxlength'=>'1588','onKeyup'=>'return update()','value'=>$short_url_msg))?>
									<?php if(API_TYPE !=2){?>	
									<input type="button" value="Variations" onclick="spinvariations();" class="btn blue btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px" />
									&nbsp;<input type="button" value="Spin {|}" onclick="spintext();" class="btn purple btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px"/>&nbsp;<?php echo $this->Html->link($this->Html->image('note-error.png'), array('action' => 'spinhelp'), array('escape' =>false, 'class' => 'nyroModal','style'=>'margin-top:10px')); ?>
									<?php } ?>
								</div>
								<div  class="form-group" style="margin-bottom:5px">Remaining Characters
								<input type=text name=limit2 id=limit2   class="form-control input-xsmall" size=4 readonly value="1588">
								</div>
								Special characters not allowed such as ~ [ ] ; "
							</div>
							<div id='upload' style="display:none;" >
								<div class="form-group" >
										<!--<label for="some21">Image<span class="required_star"></span>&nbsp;
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please be aware that mobile carriers are much more restrictive when sending images to many numbers from 1 long code number in rapid succession since there is much more bandwidth to factor in. We limit you to sending only 1 image" data-original-title="Images" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label>  
										<input type="file" id="files" name="data[Message][image][]" onclick="check_image()"/>
										<br>
										<div id="resultpick"></div>
										<output id="result" style="margin-top:-15px;padding-top:0px"/><br>
										<input type="hidden" id="check_img_validation" value="0" />-->

<!--********************-->
<div data-provides="fileinput" class="fileinput fileinput-new">
<input type="hidden" id="check_img_validation" value="0" />
                                                        <div style="width: 200px; height: 150px;" class="fileinput-new thumbnail">
                                                            <img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"> </div>
                                                        <div style="max-width: 200px; max-height: 150px; line-height: 10px;" class="fileinput-preview fileinput-exists thumbnail"></div>&nbsp;
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please be aware that mobile carriers are much more restrictive when sending images to many numbers in rapid succession from 1 long code number since there is much more bandwidth to factor in." data-original-title="Images" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="hidden" value="" name="..."><input type="file" id="files" name="data[Message][image][]" onclick="check_image()" > </span>
                                                            <a onclick="remove_image()" data-dismiss="fileinput" class="btn red fileinput-exists" href="javascript:;"> Remove </a>
                                                        </div>
</div>

<!--***********************-->
								</div>
								<div class="form-group" style="margin-top:0px">
									<label for="some21">Message<span class="required_star"></span>&nbsp;<!--<a rel="tooltip" title="1 credit is charged for each 160 character segment. If you have a message that is 300 characters, and you are sending to 10 people, 20 credits will be deducted(2 credits for each person)." class="ico" href="#" title="help" style="font-weight:normal"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="2 credits will be charged for each contact when sending MMS messages." data-original-title="Credits" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Use <b>%%Name%%</b> to output the name of the subscriber in the message if a name exists for one" data-original-title="Merge Tags" class="popovers"><i class="fa fa-tags" style="font-size:18px"></i></a>

</label>  </label>  
									<?php echo $this->Form->textarea( 'Message.msg',array('div'=>false,'label'=>false,'class' => 'form-control input-xlarge','rows'=>'6','id'=>'message2','maxlength'=>'1588','onKeyup'=>'return update1()','value'=>$short_url_msg))?>
									<?php if(API_TYPE !=2){?>
									<input type="button" value="Variations" onclick="spinvariations2();" class="btn blue btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px;"/>&nbsp;<input type="button" value="Spin {|}" onclick="spintext2();" class="btn purple btn-outline" style="padding-top:1px;padding-bottom:1px; margin-top:10px;"/>&nbsp;<?php echo $this->Html->link($this->Html->image('note-error.png'), array('action' => 'spinhelp'), array('escape' =>false, 'class' => 'nyroModal','style'=>'margin-top:10px')); ?>
									<?php } ?>
								</div>
								<div  class="form-group" style="margin-bottom:5px">Remaining Characters
								<input type=text name=limit3 id=limit3   class="form-control input-xsmall" size=4 readonly value="1588">
								</div>Special characters not allowed such as ~ [ ] ; "
							</div>
							<?php } ?>
                                                        
                                                        <div id="mandatory_message_show">
							<div class="form-group" style="margin-top:10px">
									<label>Mandatory Appended Message</label>
									<?php echo $this->Form->input('Message.systemmsg',array('div'=>false,'label'=>false, 'class' => 'form-control input-xlarge','id'=>'message','maxlength'=>'1588','rows'=>'1','cols'=>'32','value'=>'STOP to end','readonly'=>'readonly','style'=>'color:#808080'))?>
							</div>
                                                        
							<?php if(API_TYPE !=2){?>	
							<div class="form-group">
								<input id="rotate_number" type="checkbox" value="0" name="data[User][rotate_number]" onclick="checkrotate()">
								<!--<font style="font-weight: bold">-->
									Rotate through your long codes&nbsp;
									<!--<a rel="tooltip" title="Useful if you have a large number of opt-in contacts(>500), you can spread your workload across multiple numbers in your account. If checked, please include your business name in the message." class="ico" href="#" title="help" style="font-weight:normal"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-html="true" data-container="body" data-trigger="hover" data-content="Useful if you have a large number of opt-in contacts(>500), you can spread your workload across multiple numbers in your account.<br/><br/><b>NOTE:</b> If checked, please include your business name in the message so your subscribers know who the message is coming from." data-original-title="Longcodes Rotate" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								<!--</font>    -->
							</div>	
                                                        </div>
                                                <div class="form-group" style="margin-top:10px">
						<label>Sending Throttle</label>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Per carrier restriction, the fastest(default setting) you can send SMS on 1 long code is 1 SMS/second. However, you can slow that down to exercise extra caution. The slowest setting will send at rate of 10 SMS per minute or 1 SMS every 6 seconds." data-original-title="Sending Throttle" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i></a>
						<?php	
							$Option=array('1'=>'1 SMS Every 1 Second Per Long Code','2'=>'1 SMS Every 2 Seconds Per Long Code','3'=>'1 SMS Every 3 Seconds Per Long Code','4'=>'1 SMS Every 4 Seconds Per Long Code','5'=>'1 SMS Every 5 Seconds Per Long Code','6'=>'1 SMS Every 6 Seconds Per Long Code');
							echo $this->Form->input('User.throttle', array(
							'class'=>'form-control input-xlarge',
							'label'=>false,
							'type'=>'select',  'options' => $Option, 'default'=>'1'));
						?>
					        </div>
							<?php } ?>	
						<div class="form-actions">
							<?php echo $this->Form->submit('Save/Send Message',array('div'=>false,'class'=>'btn blue'));?>
						</div>


                               
		
					</div>	
				<?php echo $this->Form->end(); ?>
			</div>	
		</div>
		<?php }}else{ ?>
		<div class="portlet light"><div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="fa fa-comments-o font-red-sunglo"></i>
					<span class="caption-subject bold uppercase"> <?php __('Send Messages');?></span>
				</div>
			</div>
			
			<div class="portlet-body form">
				<?php 
					if($active==1 && $assigned_number ==0){?>
					<h3 style="margin-top:5px">
				You need to get an online number to use this feature.</h3>
				<br>
				Purchase Number to use this feature by <b>
				<?php
				if(API_TYPE==0){
				 echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				 }else if(API_TYPE==1){
				 echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				 }else if(API_TYPE==3){
				 echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
				}
				 ?>
				</b><br />
				<?php }else if($active==0 || $assigned_number ==0){?>
				<h3>	Oops! You need to activate your account to use this feature.</h3>
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
				<h3>SMS balance is 0. Please purchase additional SMS credits to allow sending SMS messages.</h3>
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
				<?php }?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>     

<script type="text/javascript">



$('#sent_on').datetimepicker({
     minDate: 0,
    showSecond: false,
    //showMinute: false,
	dateFormat: 'dd-mm-yy',
	//timeFormat: 'hh',
	timeFormat: 'hh:mm',
	stepHour: 1,
	stepMinute: 5,
	stepSecond: 10
});

function utf8_decode(str) {
  var output = "";
  var i = c = c1 = c2 = 0;
  while ( i < str.length ) {
    c = str.charCodeAt(i);
    if (c < 128) {
      output += String.fromCharCode(c);
      i++;
    }
    else if((c > 191) && (c < 224)) {
      c2 = str.charCodeAt(i+1);
      output += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
      i += 2;
    }
    else {
      c2 = str.charCodeAt(i+1);
      c3 = str.charCodeAt(i+2);
      output += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    }
  }
  return output;
}
</script>