<style>
.titleName {
  border-radius: 28px !important;
  display: inline-block;
  height: 39px;
  line-height: 15px;
  padding: 11px;
  text-align: center;
  width: 39px;
  color: #ffffff !important;
}

.main-chtbx li {padding:10px 10px 10px 0 !important; display:block;
border-radius:10px !important;
}

textarea.form-control {
  /*box-shadow: 0 0 16px #ccc inset !important;*/
  box-shadow: 0px 10px 10px -10px rgba(0,0,0,0.52) inset !important;
}

.main-chat {width:92%;}
.msg-area .tag-btntagsmerge {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  padding: 5px 4px 0px !important;
  position: absolute;
  top: 30px;
}
.t-g { margin:0;background:#FFF;}
.t-g > h5 { float: left; font-size: 14px; margin: 2px 0 0;}
.t-g > ul { float: left; margin: 0; padding: 0;}
.t-g li { float: left; list-style: outside none none; margin: 0 ;}
#loaderdiv img {   width: 70px;}
#loaderdivfav img {   width: 70px;}
.loader-div {   margin-top: 20%;}
#loaderdivfav {   margin-top:50%;}
#chatmsg {
    padding: 0px 0 0;
}
.portlet.light > .portlet-title > .caption .white {
    color: grey;
}

.portlet.light > .portlet-title > .caption .red {
    color: red;
}

.inbx-list .lft-img .star-img .red {
    color: red;
}

.btn-group-vertical > .btn, .btn-group > .btn {
float:none;
    position: relative;
}
</style>

<?php if(!empty($firstcontact)){?>
<script>
$(document).ready(function(){
	chat("CONTACT",<?php echo $firstcontact['Contact']['id']; ?>);
});
</script>
<?php } ?>


<script>

function playSound(msgid){
	msgsoundplay(msgid);
	var filename = '<?php echo SITE_URL ?>/audio/filling-your-inbox';
	document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
	$("#sound").empty();
}
function msgsoundplay(id){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/msgsoundplay/"+id,
		type: "POST",
		dataType: "html",
		success: function(response) {
		}
	});
}
function insertAtCaret(areaId,text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}
function message(){
	$.ajax({
		type : 'POST',
		url : '<?php echo SITE_URL ?>/chats/messages',
		success : function(response) {
			$('.contactmsg').empty();
			$('.contactmsg').removeClass('badge badge-danger');
			var json_obj = JSON.parse(response);
			$(json_obj).each(function (index, value) {
				if(json_obj[index].notify==0 && json_obj[index].route=='inbox'){
					refreshswithout_click();
				}
				if(json_obj[index].route=='inbox'){
					$('#contactlatest'+json_obj[index].id).html(json_obj[index].msg);
					$('#contactmsg'+json_obj[index].id).html(json_obj[index].msgcount);
					$('#contactmsg'+json_obj[index].id).addClass('badge badge-danger');
 					$('#contact_list'+json_obj[index].id).css("background","#ffffff");
                                        $('#scroll-area2').animate({scrollTop: $('#scroll-area2').prop("scrollHeight")}, 500);
					
					if(json_obj[index].msgsound==0){
						playSound(json_obj[index].msgid);
                                                <?php if(API_TYPE==0){?>
                                                      getnewcredits();
                                                <?php } ?>
                 			}
                        
				}else if(json_obj[index].route=='outbox'){
					$('#contactlatest'+json_obj[index].id).html(json_obj[index].msg);
                               
				}
			});
			getnewmsg();
			setTimeout(function(){ message(); }, 5000);
		}
	});
}
$(document).ready(function(){
	$('textarea[maxlength]').live('keyup change', function() {
		var str = $(this).val()
		var mx = parseInt($(this).attr('maxlength'))
		if (str.length > mx) {
		$(this).val(str.substr(0, mx))
			return false;
		}
	});
	message();

});

function getnewcredits(){
        $.ajax({
		type : 'POST',
		url : '<?php echo SITE_URL ?>/chats/credits',
		success : function(response) {
			
			var json_obj = JSON.parse(response);
			$(json_obj).each(function (index, value) {

                        $("#smscreditbalance").html(json_obj[index].smsbalance);
							
		   });

		}
	});
}


function getnewmsg(){
	var contact_id = $('#contact_id').val();
	var group_id = $('#group_id').val();
	if(contact_id > 0){
		var type = $('#type').val();
		if(type=='CONTACT'){
			$.ajax({
				url: "<?php  echo SITE_URL ?>/chats/chatmsg/CONTACT/"+contact_id,
				type: "POST",
				dataType: "html",
				success: function(response) {
					if(response==0){
						window.location.reload(true); 
					}else{
						$('#chatmsg').html(response);
                          
					}
				}
			});
		}
	}else if(group_id > 0){
		var type = $('#type').val();
		if(type=='GROUP'){
			$.ajax({
				url: "<?php  echo SITE_URL ?>/chats/groupchatmsg/GROUP/"+group_id,
				type: "POST",
				dataType: "html",
				success: function(response) {
					if(response==0){
						window.location.reload(true); 
					}else{
						$('#chatmsg').html(response);
					}
				}
			});
		}
	}
}
function chat(type,id){
	height = 0;
	if(type=='CONTACT'){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/chat/CONTACT/"+id,
			type: "POST",
			dataType: "html",
			beforeSend: function(){
				$('#loaderdiv').show();
				$('#chatdiv').hide();
				$('#divimages').hide();
				$('#submitpage').hide();
				$('#templates').hide();
			},
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
                                        typemsg();
					$('.groupheading').removeClass('groupheadingactive');
					$('.contactheading').removeClass('contactheadingactive');
					$('#contactheading'+id).addClass('contactheadingactive');
					$('.media').removeClass('active');
					$('#contact_list'+id).addClass('active');
					$('#loaderdiv').hide();
					$('#chatdiv').html(response);
					$('#chatdiv').show();
					$('#submitpage').show();
					$('#templates').show();
					$('#contact_id').val(id);
					$('#type').val(type);
					$('#divimages').empty();
					$('#divimages').hide();
					$('#group_id').val(0);
					$('#message').val('');
					$('#characters').html(0);
					$('#partscredits').html(0);
					$('#mms').html(0);
					$('#subscribers').html(1);
					$('#credits').html(0);
					$('#scroll-area2').animate({scrollTop: $('#scroll-area2').prop("scrollHeight")}, 500);
				}
			}
		});
	}else if(type=='GROUP'){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/groupchat/GROUP/"+id,
			type: "POST",
			dataType: "html",
			beforeSend: function(){
				$('#loaderdiv').show();
				$('#chatdiv').hide();
				$('#submitpage').hide();
				$('#templates').hide();
				$('#divimages').hide();
			},
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					typemsg();
					$('.contactheading').removeClass('contactheadingactive');
					$('.groupheading').removeClass('groupheadingactive');
					$('#groupheading'+id).addClass('groupheadingactive');
					$('.media').removeClass('active');
					$('#group_list'+id).addClass('active');
					$('#loaderdiv').hide();
					$('#chatdiv').html(response);
					$('#submitpage').show();
					$('#chatdiv').show();
					$('#templates').show();
					$('#group_id').val(id);
					$('#divimages').empty();
					$('#divimages').hide();
					$('#type').val(type);
					$('#contact_id').val(0);
					$('#message').val('');
					$('#characters').html(0);
					$('#partscredits').html(0);
					$('#mms').html(0);
					$('#subscribers').html(0);
					$('#credits').html(0);
					$('#scroll-area2').animate({scrollTop: $('#scroll-area2').prop("scrollHeight")}, 500);
				}
			}
		});
	}
}
function groupcounts(id){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/groupcounts/"+id,
		type: "POST",
		dataType: "html",
		success: function(response) {
			$('#subscribers').html(response);
		}
	});
}
function favorites(order){
	if(order==0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorites/"+order,
			type: "POST",
			dataType: "html",
			beforeSend: function(){
				$('#contactlists').hide();
				$('#loaderdivfav').show();
			},
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$('#loaderdivfav').hide();
					$('#contactlists').show();
					$('#contactlists').html(response);
					$('#taggroups').removeClass("active");
					$('#calendar3color').removeClass("active");
					$("#favorites").attr("onclick", "return favorites(1);");
					$('#favorites').empty();
					$('#favorites').html('<i class="fa fa-heart red" aria-hidden="true"></i>');
				}
			}
		});
	}else if(order==1){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorites/"+order,
			type: "POST",
			dataType: "html",
			beforeSend: function(){
				$('#contactlists').hide();
				$('#loaderdivfav').show();
			},
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$('#loaderdivfav').hide();
					$('#contactlists').show();
					$('#contactlists').html(response);
					$('#taggroups').removeClass("active");
					$('#calendar3color').removeClass("active");
					$("#favorites").attr("onclick", "return favorites(0);");
					$('#favorites').empty();
					$('#favorites').html('<i class="fa fa-heart white" aria-hidden="true"></i>');
				}
			}
		});
	}
}
function searchcontact(){
	var search = $('#search').val();
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/searchcontact",
		type: "POST",
		data: {search:search},
		dataType: "html",
		async: true,
		success: function(response) {
			$('#contactlists').html(response);
		}
	});
}
function favoriteassign(contact_id,fav){
	if(fav==0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorite/"+contact_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefun"+contact_id).attr("onclick", "return favoriteassign("+contact_id+",1);");
					$("#favoriteassign"+contact_id).empty();
					$("#favoriteassign"+contact_id).html('<i class="fa fa-heart red" aria-hidden="true"></i>');
				}
			}
		});
	}else{
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorite/"+contact_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefun"+contact_id).attr("onclick", "return favoriteassign("+contact_id+",0);");
					$("#favoriteassign"+contact_id).empty();
					$("#favoriteassign"+contact_id).html('<i class="fa fa-heart white" aria-hidden="true"></i>');
				}
			}
		});
	}
}
function groupfavoriteassign(group_id,fav){
	if(fav==0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/groupfavorite/"+group_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#groupfavoritefun"+group_id).attr("onclick", "return groupfavoriteassign("+group_id+",1);");
					$("#groupfavoriteassign"+group_id).empty();
					$("#groupfavoriteassign"+group_id).html('<i class="fa fa-star red" aria-hidden="true"></i>');
				}
			}
		});
	}else{
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/groupfavorite/"+group_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#groupfavoritefun"+group_id).attr("onclick", "return groupfavoriteassign("+group_id+",0);");
					$("#groupfavoriteassign"+group_id).empty();
					$("#groupfavoriteassign"+group_id).html('<i class="fa fa-star white" aria-hidden="true"></i>');
				}
			}
		});
	}
}
function refreshswithout_click(){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/refresh",
		type: "POST",
		dataType: "html",
		success: function(response) {
			if(response==0){
				window.location.reload(true); 
			}else{
				$("#unrdMsg").show();
				$("#unrdMsg_1").hide();
				$('#inb-tags').hide();
				$('#loaderdivfav').hide();
				$('#contactlists').show();
				$('#contactlists').html(response);
				$('#cal-icn').removeClass("active");
				$('#taggroups').removeClass("active");
				$('#calendar3color').removeClass("active");
				$('#favorites').empty();
				$('#favorites').html('<i class="fa fa-heart white" aria-hidden="true"></i>');
			}
		}
	});
}
function refreshs(limit){
	if(limit == ''){ limit = 0; }
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/refresh/"+limit,
		type: "POST",
		dataType: "html",
		beforeSend: function(){
			$('#contactlists').hide();
			$('#loaderdivfav').show();
		},
		success: function(response) {
			if(response==0){
				window.location.reload(true); 
			}else{
				$('#loaderdivfav').hide();
				$('#contactlists').show();
				$('#contactlists').html(response);
				$('#cal-icn').removeClass("active");
				$('#taggroups').removeClass("active");
				$('#calendar3color').removeClass("active");
				$('#favorites').empty();
				$('#favorites').html('<i class="fa fa-heart white" aria-hidden="true"></i>');
				$('#contactlists').animate({ scrollTop: 0 }, 500);
				setTimeout(function(){ $('.scrollbar').css({ 'top': '0px' }); },100);
			}
		}
	});
}
function searchcontact(){
	var search = $('#search').val();
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/searchcontact",
		type: "POST",
		data: {search:search},
		dataType: "html",
		async: true,
		success: function(response) {
			$('#contactlists').html(response);
		}
	});
}
function merge(value){
	var message = $('#message').val();
	insertAtCaret('message',value);
	$("#btntagsmerge").trigger('click');
}
function typemsg(){
	var tex = $("#message").val();
	len = tex.length;
	var mms =  $('#mms').html();
	$("#characters").html(len);
	var strutf8decode = utf8_decode(tex);
	if (tex.length != strutf8decode.length){
		//unicode exists
		var credits = Math.ceil(tex.length/70);
	}else{
		var credits = Math.ceil(tex.length/160);
	}
	$("#credits").html(1 * parseInt(credits));
	$("#partscredits").html(parseInt(credits));
}
$(document).ready(function(){
	$("#btntagsmerge").click(function(){
		$(".tag-btntagsmerge").toggle();
	});
});
function popmessagepickwidget(value){
	$('#message').val(value);
}
function sendsms(){
	var contact_id = $('#contact_id').val();
	var group_id = $('#group_id').val();
	var message = $('#message').val();
	var sendernumber = $('#sendernumber').val();
	var type = $('#type').val();
	var buttontext =$("#sendsms").text();
	if(message !=''){
		$('#sendsms').hide();
		if(contact_id > 0){
			if(type=='CONTACT'){
				$.ajax({					
					url: "<?php  echo SITE_URL ?>/chats/sendsms/CONTACT/"+contact_id,
					type: "POST",
					data: {message:message,sendernumber:sendernumber},
					dataType: "html",
					async: true,
					success: function(response) {
						if(response !=1){
							if(response==4){
								alert('You do not have enough credits to send a message to this contact.');
								return false;
							}else{
								alert(response);
								return false;
							}
						}
						$('#sendsms').show();
						refreshswithout_click();
						$('#message').val('');
						$('#message').empty('');
						$('#message').html('');
						$('#characters').html(0);
						$('#partscredits').html(0);
						$('#mms').html(0);
						$('#credits').html(0);
						//refreshswithout_click();
						getmsg();
                                                
                                                <?php if(API_TYPE==0){?>
                                                      setTimeout(function(){ getnewcredits(); }, 3000);
                                                <?php }else { ?>
                                                      getnewcredits();
                                                <?php } ?>
					}
				});
			}
		}else if(group_id > 0){
			if(type=='GROUP'){
				$.ajax({
					url: "<?php  echo SITE_URL ?>/chats/sendsmsgroup/GROUP/"+group_id,
					type: "POST",
					data: {message:message,sendernumber:sendernumber},
					dataType: "html",
					async: true,
					success: function(response) {
						if(response !=1){
							if(response==3){
								alert("You do not have enough credits to send a message to these contacts");
								response
							}else if(response==4){
								alert("You do not have enough credits to send a message to these contacts");
								return false;
							}else{
								alert(response);
								return false;
							}
						}
						$('#sendsms').show();
						refreshswithout_click();
						$('#message').val('');
						$('#message').empty('');
						$('#message').html('');
						$('#characters').html(0);
						$('#partscredits').html(0);
						$('#mms').html(0);
						$('#credits').html(0);
						getmsg();
					}
				});
			}
		}
	}else{
		alert('Please enter your message');
		return true;
	}
	
}
function getmsg(){
	var contact_id = $('#contact_id').val();
	var group_id = $('#group_id').val();
	if(contact_id > 0){
		var type = $('#type').val();
		if(type=='CONTACT'){
			$.ajax({
				url: "<?php  echo SITE_URL ?>/chats/chatmsg/CONTACT/"+contact_id,
				type: "POST",
				dataType: "html",
				success: function(response) {
					if(response==0){
						window.location.reload(true); 
					}else{
						$('#chatmsg').html(response);
					}
					//$('#slimscrolldiv').animate({scrollTop: $('#slimscrolldiv').prop("scrollHeight")}, 500);
                                          $('#scroll-area2').animate({scrollTop: $('#scroll-area2').prop("scrollHeight")}, 500);
				}
			});
		}
	}else if(group_id > 0){
		var type = $('#type').val();
		if(type=='GROUP'){
			$.ajax({
				url: "<?php  echo SITE_URL ?>/chats/groupchatmsg/GROUP/"+group_id,
				type: "POST",
				dataType: "html",
				success: function(response) {
					if(response==0){
						window.location.reload(true); 
					}else{
						$('#chatmsg').html(response);
					}
					$('#slimscrolldiv').animate({scrollTop: $('#slimscrolldiv').prop("scrollHeight")}, 500);
				}
			});
		}
	}
}
function checkmsgtemplate(id){
	if(id > 0){
		$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/checktemplate/"+id,
		type: "POST",
		dataType: "html",
		success: function(response) {
				insertAtCaret('message',response);
			}
		});
	}
}
function checkshortlinks(id){
	if(id > 0){
		$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/checkshortlink/"+id,
		type: "POST",
		dataType: "html",
		success: function(response) {
				insertAtCaret('message',response);
			}
		});
	}
}
function utf8_decode(str) {
	var output = "";
	var i = c = c1 = c2 = 0;
	while ( i < str.length ) {
		c = str.charCodeAt(i);
		if (c < 128) {
			output += String.fromCharCode(c);
			i++;
		} else if((c > 191) && (c < 224)) {
			c2 = str.charCodeAt(i+1);
			output += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
			i += 2;
		} else {
			c2 = str.charCodeAt(i+1);
			c3 = str.charCodeAt(i+2);
			output += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
			i += 3;
		}
	}
	return output;
}
</script>
<div class="page-content-wrapper">
<!--<div class="pagbg">-->
    <div class="page-content">
			<h3 class="page-title">SMS Chat</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li><span>SMS Chat</span></li> 
				</ul>  
				<div class="page-toolbar">  
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								
<a class="nyroModal" href="<?php echo SITE_URL;?>/contacts/add/2" title="Add Contact"><i class="fa fa-plus-square-o"></i> Add Contact </a></li>
								
								<li><a href="<?php echo SITE_URL; ?>/contacts/upload" title="Import Contacts"><i class="fa fa-upload"></i> Import Contacts</a>
								  
							</li>							
						</ul>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
                        <?php echo $this->Session->flash(); ?>	
			<div class="row">

				<!--*********-->
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption font-red-sunglo">
								<i class="fa fa-commenting-o font-red-sunglo"></i>
								<span class="caption-subject bold uppercase"> SMS Chat</span>
							</div>
							<div class="tools">
								<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
								<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
								<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
							</div>
						</div>
						<div class="portlet-body">
<?php $active=$this->Session->read('User.active');
if ($active==0){?>
					<h3>Oops! You need to activate your account to use this feature.</h3><br>
					<?php $payment=PAYMENT_GATEWAY;
					if($payment=='1' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>	'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
						Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
					<?php } ?> 
<?php }else if(empty($numbers)){ ?>
<h3 style="margin-top:5px">You need to get a SMS enabled online number to use this feature.</h3><br>
				<b>Purchase Number to use this feature by </b>
					<?php  
					if(API_TYPE==0){
						echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
					}else if(API_TYPE==1){
						echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
					}else if(API_TYPE==3){
						echo $this->Html->link('Get Number', array('controller' =>'plivos', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
					}  
					?>

<?php }else{ ?>
							<div class="row">
 
        <div id="sound"></div>
								<!--****-->
								<div class="col-lg-4 col-md-4">
									<!--<div class="portlet box red inlstpr">-->
                                                                        <div class="portlet light grey-cararra inlstpr">
										<div class="portlet-title">
											<div class="caption">
												<span style="width:20px;background:none;cursor:pointer" title="Return Favorites" onclick="return favorites(0);" id="favorites"><i class="fa fa-heart white" aria-hidden="true"></i></span>&nbsp;&nbsp;
											</div>
											<div class="caption">
												<a href="#null" id="refreshpage" title="Refresh Contact List" onclick="return refreshs()"><i class="fa fa-refresh" aria-hidden="true" style="color:#E87E04  "></i></a>&nbsp;&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="Only 50 contacts will be returned in the list, with the contacts who sent in the most recent messages ordered at the top. However, you can still search by name or number from all contacts in your list.<br/><br/>If you hear a beep and the message didn't come from any of the 50 contacts displayed, just refresh your contact list to bring the new message to the top." data-original-title="Contact Information" class="popovers"><i class="fa fa-question-circle" style="font-size:18px;"></i></a>
											</div>
											<div class="tools">
												<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
												<!--<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>-->
												<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
											</div>
										</div>
										<div style="overflow:hidden;" class="portlet-body panel-body inbx-list zx">
											<!--<span class="sdb-srch">

												<input type="text" maxlength="12" placeholder="Search by Phone or Name..." id="search" onkeyup="return searchcontact()">
											</span>-->
<div class="chat-form" style="margin-top:-15px">
                                        <div class="input-cont">
                                            <input type="text" maxlength="12" placeholder="Search by Phone or Name..." id="search" class="form-control" onkeyup="return searchcontact()"> </div>
                                        <div class="btn-cont">
                                            <span class="arrow"> </span>
                                            <span class="btn blue icn-only" style="cursor:default">
                                                <i class="fa fa-search icon-white"></i>
                                            </span>
                                        </div>
                                    </div>

											<div class="col-md-12 col-sm-12 text-center loader-div" id="loaderdivfav" style="display:none;">
												<img src="<?php echo SITE_URL;?>/img/loader.gif" alt="Loader"/>
											</div>
											<div class="viewport" id="scroll-area" style="position: relative; width: auto; overflow: auto; height: 92.4%;" data-auto-scroll="true" data-slide-speed="200">
												<div id="contactlists" style="width: auto;" class="panel-body slimscroll np"> 
													<ul class="media-list">
														<?php  if(isset($contacts)){?>
															<?php if(!empty($contacts)){ ?>
																<?php 
																$i=1; 
																foreach($contacts as $contact){ 
																	if($i%2==0){	
																		$cont_color="white";
																		$date_black="black";
																	}else{
																		$cont_color="";	
																		$date_black="";
																	}
																?>
																<?php if(isset($contact['Contact']['id'])){ ?>
																	<li class="media border-dotted <?php echo $cont_color;?> " id="contact_list<?php echo $contact['Contact']['id'];?>">
																		<div class="lft-img"> 
																			<span class="pull-left">
																				<span style="width:20px;background:none" id="favoritefun<?php echo $contact['Contact']['id'];?>" href="#null" title="Mark/Unmark as Favorite" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)">
																					<?php if($contact['Contact']['favorite']==1){ ?>
																						<!--<span style="background:none" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)" class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-star blue" aria-hidden="true"></i></span>-->
<span class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-heart red" aria-hidden="true"></i></span>
																					<?php }else if($contact['Contact']['favorite']==0){ ?>
																						<!--<span style="background:none" onclick="return favoriteassign(<?php echo $contact['Contact']['id'];?>,<?php echo $contact['Contact']['favorite'];?>)" class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-star white" aria-hidden="true"></i></span>-->
<span class="star-img" id="favoriteassign<?php echo $contact['Contact']['id'];?>"><i class="fa fa-heart white" aria-hidden="true"></i></span>
																					<?php } ?>
																				</span>
																			<span style="position: relative;" onclick="return chat('CONTACT',<?php echo $contact['Contact']['id'];?>)">
																				<?php if($contact['Contact']['email'] !=''){ ?> 
																					<?php
																						$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $contact['Contact']['email'] ) ) ) . '?d=404';
																						$headers = @get_headers( $url );
																						if(preg_match( '|200|', $headers[0])){
																							echo '<img src="'.$url.'" style="width:39px;" class="media-object img-circle" alt="">';
																						}else if(trim($contact['Contact']['name']) !=''){
																							echo '<span class="titleName" style="background: '.$contact['Contact']['color'].' none repeat scroll 0 0;">'.strtoupper(substr(trim($contact['Contact']['name']),0,2)).'</span>';
																						}else{
																							echo '<span class="titleName" style="background: '.$contact['Contact']['color'].' none repeat scroll 0 0;">#</span>	';	
																						}
																					?>
																				<?php }else if(trim($contact['Contact']['name']) !=''){  ?>
																					<span class="titleName" style="background: <?php echo $contact['Contact']['color'];?> none repeat scroll 0 0;"><?php echo strtoupper(substr(trim($contact['Contact']['name']),0,2)); ?></span>	
																				<?php }else{ ?>
																					<span class="titleName" style="background: <?php echo $contact['Contact']['color'];?> none repeat scroll 0 0;">#</span>	
																				<?php } ?>
																				</span> 
																			</span> 
																		</div>
																		<div class="media-body text-left" onclick="return chat('CONTACT',<?php echo $contact['Contact']['id'];?>)">
																			<?php 
																				$phone = preg_replace("/[^0-9]/", "", $contact['Contact']['phone_number']);
																				$length = strlen($phone);
																				$number = $phone;
																				/*if($length==7){
																					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
																				}else if($length==8){
																					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
																				}else if($length==9){
																					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
																				}else if($length==10){
																					$number =  preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
																				}else if($length==11){
																					$number = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
																				}else if($length > 11){
																					$number = $phone;
																				}	*/															
																			?>
																			<p class="media-heading"> 
																				<span class="contactheading" id="contactheading<?php echo $contact['Contact']['id'];?>">
																					<?php
																					if(trim($contact['Contact']['name']) !=''){ 
																						echo ucfirst($contact['Contact']['name']); echo ' ('.$number.')';
																					}else{ 
																						echo $number;
																					}
																					?>
																				</span>
																				<br>
																				<span class="date-color <?php echo 	$date_black; ?>" id="<?php echo $contact['Contact']['id'];?>">
																					<?php if($contact['Contact']['lastmsg'] != "0000-00-00 00:00:00"){ ?>
																						<?php echo date('m-d-Y h:i A',  strtotime($contact['Contact']['lastmsg'])); ?>
																					<?php  }else{ ?>
																						<?php echo date('m-d-Y h:i A',  strtotime($contact['Contact']['created'])); ?>
																					<?php  } ?>
																				</span> 
																				<span class="contactmsg" id="contactmsg<?php echo $contact['Contact']['id'];?>"></span>
																			</p>
																			<?php if(isset($contact['Log'][0])){ ?>
																				<?php if($contact['Log'][0]['msg_type']=='text'){?>
																					<p class="media-text" id="contactlatest<?php echo $contact['Contact']['id'];?>"><?php echo ucfirst(substr($contact['Log'][0]['text_message'],0,20)); ?>....</p>
																				<?php }else{ ?>
																					<p class="media-text" id="contactlatest<?php echo $contact['Contact']['id'];?>">New Voicemail....</p>
																				<?php } ?>
																			<?php } ?>
																		</div>
																	</li>
																	<?php }else if(isset($contact['Group']['id'])){?>
																		<li class="media border-dotted <?php echo $cont_color;?>" id="group_list<?php echo $contact['Group']['id'];?>">
																			<div class="lft-img">
																				<span class="pull-left">
																					<a id="groupfavoritefun<?php echo $contact['Group']['id'];?>" href="#null" onclick="return groupfavoriteassign(<?php echo $contact['Group']['id'];?>,<?php echo $contact['Group']['favorite'];?>)">
																						<?php if($contact['Group']['favorite']==1){ ?>
																							<span class="star-img" id="groupfavoriteassign<?php echo $contact['Group']['id'];?>"><i class="fa fa-heart red" aria-hidden="true"></i></span>
																						<?php }else if($contact['Group']['favorite']==0){ ?>
																							<span class="star-img" id="groupfavoriteassign<?php echo $contact['Group']['id'];?>"><i class="fa fa-heart white" aria-hidden="true"></i></span>
																						<?php } ?>
																					</a>
																					<span style="position:relative;" onclick="return chat('GROUP',<?php echo $contact['Group']['id'];?>)">
																						<i class="fa fa-users" aria-hidden="true" style="font-size:20px;"></i>
																					</span>
																				</span>
																			</div>
																			<div class="media-body text-left" onclick="return chat('GROUP',<?php echo $contact['Group']['id'];?>)">
																				<p class="media-heading">
																					<span class="groupheading" id="groupheading<?php echo $contact['Group']['id'];?>"><?php echo ucfirst($contact['Group']['group_name']);?></span>
																				</p>
																				<?php if(isset($contact['GroupSmsBlast'][0])){?>
																					<p class="media-text"><?php echo ucfirst(substr($contact['GroupSmsBlast'][0]['msg'],0,20)); ?>....</p>
																				<?php } ?>
																			</div>
																		</li>	
																	<?php } ?>
																<?php $i++; } ?>
															<?php } ?>
														<?php } ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-8 col-sm-8 text-center loader-div" id="loaderdiv" style="display:none;">
									<img src="<?php echo SITE_URL;?>/img/loader.gif" alt="Loader"/>
								</div>

								<div class="col-lg-8 col-md-8">
									<div id="chatdiv" style="display:none;">
									</div>

</div>
<div class="col-lg-12 col-md-12">
									<!--/ Top Stats -->
									<!-- Type your message -->
									<div id="divimages" style="display:none;"></div>
									<div class="col-sm-12" id="submitpage" style="display:none;">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 padd msg-area">
												<div class="valign-top panel panel-default table-layout text-box">
													<!-- panel heading -->
													<div class="panel-heading portlet box red inlstpr">
														<div class="text-right" >
<ul class="msg-chr" style="float:left">
<li class="btn ">
																<select class="btn vbn" name="number" id="sendernumber" style="margin-top:8px;font-size:17px">
																<?php foreach($numbers as $number){ ?>
																	<option value="<?php echo $number['number'];?>">SEND FROM: <b><?php echo $number['number_details'] ?></b></option>
																<?php } ?>
																</select>
																</li>
																<!--<li>Characters: <span id="characters">0</span></li>
																<li>SMS parts: <span id="partscredits">0</span>/10</li>
																<li>Credits: <span id="credits">0</span></li>-->
																
															</ul>
															<div class="btn-group icntgsbtns" style="width:70%">
																<a href="<?php echo SITE_URL;?>/chats/msgtemplates" class="btn blue-hoki btn-sm nyroModal" title="Message Templates" style="color:white;top:3px;z-index:0;box-shadow:2px 2px 2px -1px rgba(0, 0, 0, 0.52) inset !important"><i class="fa fa-clone" aria-hidden="true"></i>
 Insert Template
																	<!--<button class="btn btn-default" type="button">
																		<i class="fa fa-indent"></i>
																	</button>-->
																</a>&nbsp;
																<a href="<?php echo SITE_URL;?>/chats/shortlinks" class="btn blue-hoki btn-sm nyroModal" title="Short Links" style="color:white;top:3px;z-index:0;box-shadow:2px 2px 2px -1px rgba(0, 0, 0, 0.52) inset !important"><i class="fa fa-link" aria-hidden="true"></i>
 Insert Short Link
																	<!--<button class="btn btn-default" type="button">
																		<i class="fa fa-unlink"></i>
																	</button>-->
																</a>&nbsp;
																<a class="btn blue-hoki btn-sm usractns" id="btntagsmerge" href="javascript:void(0)" title="Merge Tags" style="color:white;top:3px;z-index:0;box-shadow:2px 2px 2px -1px rgba(0, 0, 0, 0.52) inset !important"><i class="fa fa-tags"></i> Insert Tags
																	<!--<button class="btn btn-default" type="button">
																		<i class="fa fa-tags"></i>
																	</button>-->
																</a>&nbsp;
																<input onchange="popmessagepickwidget(event.fpfile.url)" data-fp-container="modal" data-fp-mimetypes="image/*" data-fp-apikey=<?php echo FILEPICKERAPIKEY; ?> id="filepicker" style="display: none;" type="filepicker"/>
<!--<button style="width:50px;margin-top:2px;margin-bottom:2px; margin-left:100px;float:right" class="btn btn-success btn-sm " type="button" id="sendsms" onclick="return sendsms()">Send</button>-->
<div class="col-lg-2 col-md-2 col-sm-3 save pull-right" id="templates" style="display:none;padding-left:30px">
									
<button class="btn btn-success btn-sm" type="button" id="sendsms" onclick="return sendsms()" style="margin-bottom:2px;margin-top:2px;"><i class="fa fa-paper-plane" aria-hidden="true" style="font-size:20px"></i>&nbsp;<font style="font-size:16px">Send</font></button>


								</div>
															</div>
															<!--<ul class="msg-chr">
																<li>Characters: <span id="characters">0</span></li>
																<li>SMS parts: <span id="partscredits">0</span>/10</li>
																<li>Credits: <span id="credits">0</span></li>
																<li class="btn ">
																<select class="btn vbn" name="number" id="sendernumber">
																<?php foreach($numbers as $number){ ?>
																	<option value="<?php echo $number['number'];?>">Sending as:<b><?php echo $number['number_details'] ?></b></option>
																<?php } ?>
																</select>
																</li>
															</ul>-->
														</div>
														<!--/ panel toolbar -->
													</div>
													<input type="hidden" name="type" id="type" value=""/>
													<input type="hidden" name="group_id" id="group_id" value="0"/>
													<input type="hidden" name="contact_id" id="contact_id" value="0"/>													
													<div class="col-lg-12 tag-btntagsmerge redusrsbtns" >
														<div class="col-xs-12 t-g">
														<!--h5>Merge Fields</h5-->
														<ul>
															<li><a href="#null" onclick="return merge('[Name]')">[Name]</a></li>
															<li><a href="#null" onclick="return merge('[Email]')">[Email]</a></li>
															<li><a href="#null" onclick="return merge('[Phone]')">[Phone]</a></li>
															<li><a href="#null" onclick="return merge('[Birthday]')">[Birthday]</a></li>
														</ul>
														</div>
													</div>
													<textarea id="message" class="form-control" maxlength="1600" placeholder="Type your message" rows="3" style="min-height:121px; font-weight:400;" onkeyup="return typemsg()"> </textarea>
<ul class="msg-chr" style="background:#3598dc;width:100%;text-align:center;">
<li>Characters: <span id="characters" style="color:yellow;font-weight:bold">0</span></li>&nbsp;
																<li>SMS parts: <span id="partscredits" style="color:yellow;font-weight:bold">0</span>/10</li>
																<li>Credits: <span id="credits" style="color:yellow;font-weight:bold">0</span></li>&nbsp;


															</div>
</ul>
												</div> 
											</div>                                    

										</div>
									</div>
									<!--/ Type your message --> 
								<!--</div>-->

								<!--<div class="col-lg-2 col-md-2 col-sm-3 save pull-right" id="templates" style="display:none;">
									


<button class="btn btn-success btn-block " type="button" id="sendsms" onclick="return sendsms()"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;Send</button>


								</div>-->
							</div>
						</div>
					</div>
				</div>
<?php } ?>
			</div>
		</div>
	
	</div>
</div>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.8-fix/jquery.nicescroll.min.js"></script>

  <script type="text/javascript">    
    $(document).ready(function() {
      $('#scroll-area').niceScroll({
        autohidemode: 'false',     // Do not hide scrollbar when mouse out
        cursorborderradius: '0px', // Scroll cursor radius
        background: '#E5E9E7',     // The scrollbar rail color
		opacity: '0.4',
        cursorwidth: '10px',       // Scroll cursor width
        cursorcolor: '#999999',     // Scroll cursor color
		autohidemode:'scroll'
      });

    $('#scroll-area2').niceScroll({
        autohidemode: 'false',     // Do not hide scrollbar when mouse out
        cursorborderradius: '0px', // Scroll cursor radius
        background: '#E5E9E7',     // The scrollbar rail color
		opacity: '0.4',
        cursorwidth: '10px',       // Scroll cursor width
        cursorcolor: '#999999',     // Scroll cursor color
		autohidemode:'scroll'
      });

    });
  </script>-->
  


	
