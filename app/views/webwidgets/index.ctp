<?
echo $this->Html->css('colorPicker');
echo $this->Html->script('jquery.colorPicker');
?>
<script>
var SITE_URL = '<?=SITE_URL?>';
$(function() {    
       //use this method to add new colors to pallete
       //$.fn.colorPicker.addColors(['000', '000', 'fff', 'fff']);
       $('#bgColor').colorPicker();
       $('#fontColor').colorPicker();
      });
$(document).ready(function(){
    $("#autoResponse").click(function(e){
            if(this.checked){
                $("#msg").attr("disabled", false);
            }else{
                $("#msg").attr("disabled", true);
            }
        });
    $("#useDefaultBtn").click(function(){
        if(!this.checked){
            $("#btnPopUp").attr("disabled", false);
        }else{
            $("#btnPopUp").attr("disabled", true);
        }
    });
    $("#msg").keyup(function() {
        setCounter("#counter", $("#msg").val(), 160);
    });
 });
function setCounter(counter, string, maxLength) {
	var charsLeft = getAvailableLength(string, maxLength);
	$(counter).html(charsLeft);
	var color = ((charsLeft < 0) ? '#ff0000' : '#555555');
	$(counter).css('color', color);
} 
function getAvailableLength(str, maxLength) {
	return (maxLength - str.length - count2Symbols(str));
} 
function count2Symbols(stringBase) {
	var stringReplaced = stringBase.replace(/[~@#%\/\\=\+]/g, "")
	.replace(/\r\n/g, "")
	.replace(/\n/g, "");
	return stringBase.length - stringReplaced.length;
}
function trim (str, charlist) {   
    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');   
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');   
    return str.replace(re, '');   
} 
function checknumber (x) {
    var anum=/(^\d+$)|(^\d+\.\d+$)/
    if (anum.test(x)) {
        result = true;
    } else {
        result=false
    }
return (result)
}
function generatePopUpURL () {
    var file = trim($("#urlLogo").val());
    var tmp = file.split('.');
    var typeFile = tmp[(tmp.length-1)];
    if(trim($("#urlLogo").val())!='https://' && trim($("#urlLogo").val()) != ""){
        if((typeFile!='png' && typeFile!='jpg' && typeFile!='gif')){   
            alert('Please enter the URL of a valid jpg, gif, or png image.')        
            $("#urlLogo").focus();
            return false;
        }else{
            urlBase = '<?=SITE_URL?>/weblinks/subscribe?group=' + $("#group_id").val();
            urlParams = '';
            if(trim($("#urlLogo").val())!='https://' && trim($("#urlLogo").val()) != "")
                urlParams += '&ll=' + escape($("#urlLogo").val());
            else
                urlParams += '&ll=null';
            urlParams += '&bgc=' + $("#bgColor").val().replace("#", "");
            urlParams += '&ff=' + $("#fontFamily").val();
            urlParams += '&fs=' + $("#fontStyle").val();
            urlParams += '&fz=' + $("#fontSize").val();
            urlParams += '&fc=' + $("#fontColor").val().replace("#", "");
            urlParams += '&mt=' + escape($("#msgTop").val());
            urlParams += '&ms=' + escape($("#msgAfterSub").val());
            urlParams += '&rd=' + escape($("#redirectURL").val());
            
            if ($("#CollectEmail").prop("checked")) {
                urlParams += '&ce=1';
            } else {
                urlParams += '&ce=0';
            }

            if ($("#CollectBirthday").prop("checked")) {
                urlParams += '&cb=1';
            } else {
                urlParams += '&cb=0';
            }            

            if ($("#autoResponse").val("checked")) {
                urlParams += '&ar=1';
                urlParams += '&mar=' + escape($("#msg").val());
            } else {
                urlParams += '&ar=0';
            }
            return urlBase + urlParams;
        }
    }else{
			urlBase = '<?=SITE_URL?>/webwidgets/subscribe?group=' + $("#group_id").val();
			urlParams = '';
			if(trim($("#urlLogo").val())!='https://' && trim($("#urlLogo").val()) != "")
				urlParams += '&ll=' + escape($("#urlLogo").val());
			else
				urlParams += '&ll=null';
			urlParams += '&bgc=' + $("#bgColor").val().replace("#", "");
			urlParams += '&ff=' + $("#fontFamily").val();
			urlParams += '&fs=' + $("#fontStyle").val();
			urlParams += '&fz=' + $("#fontSize").val();
			urlParams += '&fc=' + $("#fontColor").val().replace("#", "");
			urlParams += '&mt=' + escape($("#msgTop").val());
			urlParams += '&ms=' + escape($("#msgAfterSub").val());
                        urlParams += '&rd=' + escape($("#redirectURL").val());

                        if ($("#CollectEmail").prop("checked")) {
                           urlParams += '&ce=1';
                        } else {
                           urlParams += '&ce=0';
                        }

                        if ($("#CollectBirthday").prop("checked")) {
                           urlParams += '&cb=1';
                        } else {
                           urlParams += '&cb=0';
                        }      

			if ($("#autoResponse").attr("checked")) {
				urlParams += '&ar=1';
				urlParams += '&mar=' + escape($("#msg").val());
			} else {
				urlParams += '&ar=0';
			}
			return urlBase + urlParams;
		}
	}  

function previewPopUp () {   
    var popUpWidth = trim($("#popUpWidth").attr("value"));
    var popUpHeight = trim($("#popUpHeight").attr("value"));       
    if (popUpWidth == "" || !checknumber(popUpWidth)) {
        alert('Please, set Pop-up / IFrame Width');
        generateEmptyCode();
        $("#popUpWidth").focus();
        return false;
    }    
    if (popUpHeight == "" || !checknumber(popUpHeight)) {
        alert('Please, set Pop-up / IFrame Height');
        generateEmptyCode();
        $("#popUpHeight").focus();
        return false;
    }
    if ($("#useScrollingPopUp").attr("checked")) {
        var popupScrolling = "yes";
    } else {
        var popupScrolling = "no";
    }
    var url = generatePopUpURL();
    if(!url)
        return;
    window.open(url, null,'location=no,menubar=no,scrollbars='+popupScrolling+',resizable=yes,status=no,toolbar=no,width='+popUpWidth+',height='+popUpHeight)  
} 
function generateEmptyCode () {
    $("#websitePopUpLink").attr("value", "");
    $("#embeddableSignupWidget").attr("value", "");
    $("#linkForEmailCampaign").attr("value", "");
    $("#widgetForFacebook").val("");
}
function generateCode () {
    if ($("#group_id").attr("value") == "-1") {
        alert('Please select a group');
        generateEmptyCode();
        $("#group_id").focus();
        return false;
    }
    var url = generatePopUpURL();
    if(!url)
        return;
    var popUpWidth = trim($("#popUpWidth").attr("value"));
    var popUpHeight = trim($("#popUpHeight").attr("value"));
    var embeddablePopUpWidth = trim($("#embeddablePopUpWidth").attr("value"));
    var embeddablePopUpHeight = trim($("#embeddablePopUpHeight").attr("value"));
    if (popUpWidth == "" || !checknumber(popUpWidth)) {
        alert('Please, set Pop-up width');
        generateEmptyCode();
        $("#popUpWidth").focus();
        return false;
    }
    if (popUpHeight == "" || !checknumber(popUpHeight)) {
        alert('Please, set Pop-up width');
        generateEmptyCode();
        $("#popUpHeight").focus();
        return false;
    }
    if (embeddablePopUpWidth == "" || !checknumber(embeddablePopUpWidth)) {
        alert('Please, set IFrame Width');
        generateEmptyCode();
        $("#embeddablePopUpWidth").focus();
        return false;
    }
    if (embeddablePopUpHeight == "" || !checknumber(embeddablePopUpHeight)) {
        alert('Please, set IFrame Height');
        generateEmptyCode();
        $("#embeddablePopUpHeight").focus();
        return false;
    }   
    if ($("#autoResponse").attr("checked")) {
        strMessageA = $("#msg").attr("value");
        if ( getAvailableLength(strMessageA, 160) < 0 ) {
            alert('Auto-response message too long');
            $("#msg").focus();
            return false;
        }else if(trim(strMessageA)==""){
            alert('Auto-response message is empty');
            $("#msg").focus();
            return false;
        }
    }   
    if ($("#useScrollingPopUp").attr("checked")) {
        popupScrolling = "yes";
    } else {
        popupScrolling = "no";
    }
    
    if ($("#useScrolling").attr("checked")) {
        iframeScrolling = "auto";
    } else {
        iframeScrolling = "no";
    }
    
    var imageurl='';
    var file = trim($("#btnPopUp").attr("value"));
    var tmp = file.split('.');
    var typeFile = tmp[(tmp.length-1)];
    if ($("#useDefaultBtn").attr("checked")) {
        $("#btnPopUp").attr("value", "https://");
        imageurl = "<?=SITE_URL?>/img/joinlist.gif";
    } else if (trim($("#btnPopUp").attr("value")) == "" || trim($("#btnPopUp").attr("value")) == "https://") {
        alert('Please enter URL for button image (or select default image)');
        generateEmptyCode();
        $("#btnPopUp").focus();
        return false;
    }
    else if(typeFile!='png' && typeFile!='jpg' && typeFile!='gif'){
        alert('Please enter the URL of a valid jpg, gif, or png image.')
        generateEmptyCode();
        $("#btnPopUp").focus();
        return false;
    } 
    else {
        imageurl = trim($("#btnPopUp").attr("value"));
    }


    websitePopUpLinkCode = '<a href="#" onclick="window.open(\'' + url + '\',null,\'location=no,menubar=no,scrollbars=' + popupScrolling +
    ',resizable=yes,status=no,toolbar=no,width=' + popUpWidth +
',height=' + popUpHeight + '\');"><img src="'+imageurl+'" border=\'0\'></a>';
    $("#websitePopUpLink").html(websitePopUpLinkCode);
    
    embeddableSignupWidgetCode = '<iframe src="' + url + '" width="' + embeddablePopUpWidth + '" height="' + embeddablePopUpHeight + '" align="left" scrolling="' + iframeScrolling + '"></iframe>';
    $("#embeddableSignupWidget").html( embeddableSignupWidgetCode);

    linkForEmailCampaignCode = '<a href="' + url + '" target="_blank"><img src="'+imageurl+'" border=\'0\'></a>';
    $("#linkForEmailCampaign").html(linkForEmailCampaignCode);

    $("#signupLink").text(url);

    shortenLinkForClick = 'https://bit.ly/' + url;

    $("#shortenLink").show();
    $("#shortenLink a").attr("href", shortenLinkForClick);
   /* var formData=$("#designForm").serialize();
	$.ajax({
		type: 'POST',
            url: '/webwidgets/facebookwidget/'+ $("#group_id").val(),
            data: formData,
            success: function(data){
			//alert(data);
			$("#widgetForFacebook").val(data);
			 }, 
            error: function(message){
               alert('error');
            }
	});*/
    
  /*  if ($("#msg").val().length) {
        $.getJSON(
            '/webwidgets/index/validate-autoresponse',
            { 'message' : $("#msg").val() },
            function(results) {
                if (results.error) {
                    alert(results.error);
                    generateEmptyCode();
                    $("#msg").focus();
                }
            }
        );
    }*/
}


function addOption (oListbox, text, value, isDefaultSelected, isSelected)
{
  var oOption = document.createElement("option");
  oOption.appendChild(document.createTextNode(text));
  oOption.setAttribute("value", value);

  if (isDefaultSelected) oOption.defaultSelected = true;
  else if (isSelected) oOption.selected = true;

  oListbox.appendChild(oOption);
}


function addGroupAjax(form)
{
	$('.myClass').html('');
    var groupName = form.groupName.value;
    var groupNote = form.note.value;
    if(groupName==""){
    	$('.myClass').html('Group name must be 1-12 characters');
        return;
    }
    if(groupName.length>12){
    	$('.myClass').html('Group name must be 1-12 characters');
        return;
    }
    $.getJSON(
        '/contacts/Groups/addgroupajax',
        { groupName: groupName,groupNote:groupNote},
        function(results) {
            if (results.result == 'success') {
            	$('.myClass').html(results.message);
                form.groupName.value = "";
                form.note.value = ""; 
                
                var objSel = document.getElementById('group_id');
                addOption(objSel, groupName, results.insertID,true,true)
            } else {
            	$('.myClass').html(results.errors);
            }
        }
    );
}
function showIBox()
{
	$('.myClass').html('');
	iBox.show(document.getElementById('inner_content').innerHTML,'Add a Group')
}
function openiframe(id){


   document.getElementById('welcomeDiv').style.display = "block";


}
</script>
<style>
div.controlset {
    PADDING-RIGHT: 0px; DISPLAY: block; PADDING-LEFT: 0px; FLOAT: left; PADDING-BOTTOM: 0.25em; WIDTH: 100%; PADDING-TOP: 0.25em
}
div.controlset INPUT {
    DISPLAY: inline; FLOAT: left
}
div.controlset DIV {
    DISPLAY: inline; FLOAT: left
}
div.controlset LABEL {
    WIDTH: 100px
}

div.color_picker {
  height: 16px;
  width: 16px;
  padding: 0 !important;
  border: 1px solid #ccc;
  background: url('/skins/base/images/arrow.png') no-repeat top right;
  cursor: pointer;
  line-height: 16px;
}

div#_color_selector {
  width: 110px;
  position: absolute;
  border: 1px solid #598FEF;
  background-color: #EFEFEF;
  padding: 2px;
}
  div#_color_custom {width: 100%; float:left }
  div#_color_custom label {font-size: 95%; color: #2F2F2F; margin: 5px 2px; width: 25%}
  div#_color_custom input {margin: 5px 2px; padding: 0; font-size: 95%; border: 1px solid #000; width: 65%; }

div._color_swatch {
  height: 12px;
  width: 12px;
  border: 1px solid #000;
  margin: 2px;
  float: left;
  cursor: pointer;
  line-height: 12px;
}
.add
{
background:transparent url(<?php echo SITE_URL;?>/img/add.jpg) no-repeat scroll 0 0;
border:medium none;
font-size:0;
height:35px;
margin:0;
text-indent:-9999em;
width:100px;
cursor: pointer;
margin-left:62%;
}
.ezbuttons{
  background: url("<?php echo SITE_URL;?>/img/previewwidget.png") no-repeat scroll 0 0 transparent;
   
    border:medium none;
font-size:0;
height:35px;
margin:0;
text-indent:-9999em;
width:120px;
cursor: pointer;
box-shadow: 0 0px 0px rgba(0, 0, 0, 0.098), 0 0px 0 rgba(255, 255, 255, 0.6) inset;
}
.ezbuttons:hover{
  background: url("<?php echo SITE_URL;?>/img/previewwidget.png") no-repeat scroll 0 0 transparent;
   
    border:medium none;
font-size:0;
height:35px;
margin:0;
text-indent:-9999em;
width:120px;
cursor: pointer;
box-shadow: 0 0px 0px rgba(0, 0, 0, 0.098), 0 0px 0 rgba(255, 255, 255, 0.6) inset;
}
.ezbuttons1{
  background: url("<?php echo SITE_URL;?>/img/createwidget.png") no-repeat scroll 0 0 transparent;
   
   border:medium none;
font-size:0;
height:35px;
margin:0;
text-indent:-9999em;
width:120px;
cursor: pointer;
box-shadow: 0 0px 0px rgba(0, 0, 0, 0.098), 0 0px 0 rgba(255, 255, 255, 0.6) inset;
}
.ezbuttons1:hover{
  background: url("<?php echo SITE_URL;?>/img/createwidget.png") no-repeat scroll 0 0 transparent;
   
   border:medium none;
font-size:0;
height:35px;
margin:0;
text-indent:-9999em;
width:120px;
cursor: pointer;
box-shadow: 0 0px 0px rgba(0, 0, 0, 0.098), 0 0px 0 rgba(255, 255, 255, 0.6) inset;
}
</style>
		<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> Create Web Sign-up Widget</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<span>Web Sign-up Widget </span>
						</li>
					</ul>
					<!--<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn btn-fit-height grey-salt dropdown-toggle" type="button"> Actions
								<i class="fa fa-angle-down"></i>
							</button>
							<ul role="menu" class="dropdown-menu pull-right">
								<li>
									<a title="Add Responder" href="#">
										<i class=""></i>Add
									</a>
								</li>
							</ul>
						</div>
					</div>	-->					
				</div>			
				<div class="clearfix"></div>
				<?php echo $this->Session->flash(); ?>
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-newspaper-o font-red-sunglo"></i>
							<span class="caption-subject bold uppercase">Create Web Sign-up Widget</span>
						</div>                       
					</div>
					<div class="portlet-body form">

<div class="mt-element-step">
                                        
                                        <div class="row step-line">
                                            
                                            <div class="col-md-3 mt-step-col first done">
                                                <div class="mt-step-number bg-white">1</div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Group</div>
                                                <div class="mt-step-content font-grey-cascade">Select a group for your widget</div>
                                            </div>
                                            <div class="col-md-3 mt-step-col error">
                                                <div class="mt-step-number bg-white">2</div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Design</div>
                                                <div class="mt-step-content font-grey-cascade">Design your widget</div>
                                            </div>
                                            <div class="col-md-3 mt-step-col active">
                                                <div class="mt-step-number bg-white">3</div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Widget Type</div>
                                                <div class="mt-step-content font-grey-cascade">Select the type of widget</div>
                                            </div>
                                            <div class="col-md-3 mt-step-col last">
                                                <div class="mt-step-number bg-white">4</div>
                                                <div class="mt-step-title uppercase font-grey-cascade">Create</div>
                                                <div class="mt-step-content font-grey-cascade">Create your widget</div>
                                            </div>
                                        </div>
                                    </div>
						<form id="designForm">
							<div class="form-body">                                          
								<div class="inline">
									<p class="instruction-header"><strong>Step One</strong> - Select A Group For Your Widget</p>
									<label><b>Assign A Group</b></label>
									<?php
									echo $this->Form->input('Contacts.group_id', array(
									'div'=>false,
									'label'=>false,
									'class'=>'form-control',
									'id'=>'group_id',
									'options' => array($groups)));
									?>
								</div></br>	
								<div class="form-group">
								<label><strong>Step Two</strong> - Design Your Widget</label>
								<span class="help-block">
									In this section you'll first set some universal options, and then
									you'll have a chance to set options specific to the type of widget that
									you'll be using.
								</span>
								</div> 
								<div class="form-group">
									<label><strong>Set Background Color</strong>&nbsp;
										<!--<a class="" title="Choose the background color of the widget. If you are using the embeddable widget this color will appear in the background on your page. If you are using the pop-up widget, this color will appear in the background of the pop-up	window" style="cursor: pointer"><b>Set Background Color</b></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Choose the background color of the widget. If you are using the embeddable widget this color will appear in the background on your page. If you are using the pop-up widget, this color will appear in the background of the pop-up	window" data-original-title="Background Color" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
									</label>
									<div class="controlset">
										<input style="display: none;" name="bgColor" id="bgColor" value="#ffffff" type="text">
									</div>
								</div> 
								<div class="form-group">
									<label><strong>Optional Logo URL</strong>&nbsp;
										<!--<a class="" title="This would be the URL generated from the logo you upload directly below. After uploading your logo, just copy and paste that URL in this field." style="cursor: pointer"><b>Optional Logo URL</b></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This would be the URL generated from the logo you upload directly below. After uploading your logo, just copy and paste that URL in this field" data-original-title="Uploaded Logo" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
									</label>
										<?php  if($imagename!=''){?>
									<div class="input">
										<i class="fa fa-bell-o font-green"></i>                                                
										<input class="form-control" name="urlLogo" id="urlLogo" value="<?php echo $imagename; ?>" type="text">
										 <?php }else{ ?>
										 <input class="form-control" name="urlLogo" id="urlLogo" value="https://" type="text">
										 <?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label for="exampleInputFile1"><b>Upload Logo</b></label></br>
									<span class="btn blue  btn-outline">
										<a href="#null" onclick="return openiframe();">Click Here</a>
									</span>									
										<div id="welcomeDiv" style="float:left;display:none;width:100%">
											<iframe src="<?php echo SITE_URL;?>/webwidgets/upload" style="width:100%;height:auto;border:0"></iframe>
										</div>
								</div>	
								<div class="form-group">
									<label><b>Font</b></label>
									<select  name="fontFamily" id="fontFamily" class="form-control input-medium">
										<option value="A">Arial</option>
										<option value="H">Helvetica</option>
										<option value="T">Times New Roman</option>
										<option value="V">Verdana</option>
									</select>
								</div>
								<div class="form-group">
									<label><b>Font Style</b></label>
									<select  name="fontStyle" id="fontStyle" class="form-control input-medium">
										  <option value="N" selected="selected">Normal</option>
											<option value="B">Bold</option>
											<option value="I">Italic</option>
											<option value="BI">Bold+Italic</option>
									</select>
								</div>
								<div class="form-group">
									<label><b>Font Size</b></label>
									<select  name="fontSize" id="fontSize" class="form-control input-medium">
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13" selected="selected">13</option>
										<option value="14">14</option>
										<option value="16">16</option>
										<option value="18">18</option>
										<option value="20">20</option>
										<option value="24">24</option>
									</select>
								</div>
								<div class="form-group">
									<label><b>Font Color</b></label>
										<div class="controlset">
											<input style="display: none;" name="fontColor" id="fontColor" value="#000000" type="text">
										</div>
								</div>											
								<div class="form-group">
									<label><strong>Signup Widget Message:</strong>&nbsp
<!--<a class="" title="Add a sentence or two to explain to your customers or members what your text messaging list is all about." style="cursor: pointer"><b>Signup Widget Message:</b></a>--></label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Add a sentence or two to explain to your customers or members what your text messaging list is all about" data-original-title="Signup Widget Message" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
								   <textarea class="form-control" name="msgTop" id="msgTop"></textarea>
								</div>
								<div class="form-group">
									<label><strong>Successful Signup Message:</strong>&nbsp;
<!--<a class="" title="This message will appear after a customer or member has successfully been added to your text messaging list" style="cursor: pointer"><b>Successful Signup Message:</b></a>-->
</label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="This message will appear after a customer or member has successfully been added to your text messaging list" data-original-title="Successful Signup Message" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
									<textarea class="form-control" name="msgAfterSub" id="msgAfterSub"></textarea>
								</div>
<!--*****-->
<div class="form-group">
									<label><strong>Redirect URL:</strong>&nbsp;
</label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="After successful sign-up, user will be redirected to the URL you provide below if you provide one." data-original-title="Redirect After Successful Signup" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
									<textarea class="form-control" name="redirectURL" id="redirectURL" placeholder="http://myurl.com"></textarea>
								</div>
<!--*****-->

<!--*****-->
<div class="form-group">
									<label><strong>Collect Email:</strong>&nbsp;
</label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Check this box if you want to collect email address on the sign-up form." data-original-title="Collect Email Address" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i></a>
									<input name="CollectEmail" id="CollectEmail" style="vertical-align: top;" class="form-control" type="checkbox">&nbsp;&nbsp;

<label><strong>Collect Birthday:</strong>&nbsp;
</label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Check this box if you want to collect birthday on the sign-up form." data-original-title="Collect Birthday" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i></a>
									<input name="CollectBirthday" id="CollectBirthday" style="vertical-align: top;" class="form-control" type="checkbox">
								</div>
<!--*****-->

								<div class="form-group">
									<label><strong><em>Optional - </em>
										<a class="" href="javascript:void(0);" onclick="$('#autoreply').toggle('slow');" >Click To Setup A Custom Auto-Reply Message</a>&nbsp;</strong>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="If you enable this feature we will automatically send a message of your choice to each person who successfully joins your list. If not enabled, the autoresponder of the group will be sent instead" data-original-title="Custom Auto-Reply Message" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
									</label>
								   <div fade="1" id="autoreply" style="width: 100%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; display: none; margin-top: 10px; line-height: 14px; font-size: 12px; height: auto;">	
										<div class="form-group">
											<label><b>Enable Auto-Reply</b></label>
											<div class="input">					
												<input name="autoResponse" id="autoResponse" value="" style="vertical-align: top;" class="form-control" type="checkbox">
											</div>
										</div>
										<div class="form-group">
											<label><b>Auto-Reply Message</b></label>
												<div class="input">								  
													<textarea class="form-control" name="msg" id="msg"  disabled="disabled"></textarea>
												</div>
										</div>
										<div><span id="counter">160</span> <b>Remaining Characters</b></div>
									</div>
								</div>	
							</div>		
						</form>
						<div class="form-group">
							<label><strong>Step Three </strong>- Which Widget(s) Will You Be Using?
								<!--<a class="" title="Please Note: You may configure both the embeddable and pop-up widgets if you like. If you do not configure one (or both) of these, we will create the widget(s) with default options" style="cursor: pointer"><b>Step 3</b> <b>:- Which Widget(s) Will You Be Using?</b> </a>-->
							</label>
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Please Note: You may configure both the embeddable and pop-up widgets if you like. If you do not configure one (or both) of these, we will create the widget(s) with default options" data-original-title="Widget Type" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a><br><br>
							<div class="input">
								<span class="help-block">You May Skip This Step If You Are Using The Signup Link</span></br>
								<label>
									<a href="javascript:void(0);" onclick="$('#embed').toggle('slow');"><strong>Are You Using The Embeddable Widget?</strong> Click To Expand  Embeddable Options</a>
								</label>
								<div fade="1" id="embed" style="width: 100%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; display: none; margin-top: 10px; line-height: 14px; font-size: 12px;">				
									<div class="form-group">
										<label><b>Embeddable Widget Width</b></label>
										<div class="input">
											<input name="embeddablePopUpWidth" id="embeddablePopUpWidth" value="450" class="form-control" type="text">
										</div>
									</div>	
									<div class="form-group">
										<label><b>Embeddable Widget Height</b></label>
										<div class="input">
											<input name="embeddablePopUpHeight" id="embeddablePopUpHeight" value="300" class="form-control"  type="text">
										</div>
									</div>	
									<div class="form-group">
										<label><b>Allow Scrolling</b></label>
										<div class="input">
											<input name="useScrolling" id="useScrolling" value="" checked="checked" style="vertical-align: top;" type="checkbox">
										</div>
									</div>	
								</div>
								<br><br>
								 <a href="javascript:void(0);" onclick="$('#popup').toggle('slow');"><strong>Are You Using The Pop-Up Widget?</strong> Click To Expand Pop-Up Options</a>
								  <div fade="1" id="popup" style="width: 100%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; display: none; margin-top: 10px; line-height: 14px; font-size: 12px; height: auto; border-bottom:1px dotted grey; margin-bottom:10px;">						  
									<div class="form-group">
											<label><b>Use Our Default Image</b></label>
											<div class="input">
												<input name="useDefaultBtn" id="useDefaultBtn" value="" checked="checked" style="vertical-align: top;" type="checkbox">
												<img src="<?=SITE_URL?>/img/joinlist.gif" alt="Join Our Text Messaging List" width="150" height="58"> 
											</div>
									</div>
									<div class="form-group">
										<label><b>Use Your Own Image</b></label>
										<div class="input">
											<input class="form-control" name="btnPopUp" id="btnPopUp" disabled="disabled" value="https://" type="text">
										</div>
									</div>
									<div class="form-group">
										<label><b>Pop-Up Width</b></label>
										<div class="input">
											<input  name="popUpWidth" id="popUpWidth" value="450" class="form-control"  type="text">
										</div>
									</div>							
									<div class="form-group">
										<label><b>Pop-Up Height</b></label>
										<div class="input">
											<input  name="popUpHeight" id="popUpHeight" value="300"  class="form-control" type="text">
										</div>
									</div>							
									<div class="form-group">
										<label><b>Pop-Up Scrolling</b></label>
										<div class="input">
										   <input  name="useScrollingPopUp" id="useScrollingPopUp" value="" checked="checked" type="checkbox">
										</div>
									</div>							  
								</div>
							</div>													
							</div>		
							<button class="ezbuttons" id="generateCode" onclick="previewPopUp()"></button>
							<button class="ezbuttons1" id="preview" onclick="generateCode();"></button>
				
								</br>
								</br>
						<div class="form-group">
							<label><b>Your Customized Widgets</b></label>
							<div class="form-group">
									<label><b>Copy and paste the code below and you're ready to go!</b><br><br></label>
								<div class="form-group">
									<label><b>Embeddable Widget</b></label>
									<textarea class="form-control" id="embeddableSignupWidget" name="embeddableSignupWidget"></textarea>
								</div>
								<div class="form-group">
									<label><b>Pop-Up Widget</b></label>
									<textarea class="form-control" id="websitePopUpLink" name="websitePopUpLink"></textarea>
								</div>
								<div class="form-group">
									<label><b>Signup Link For Email Campaigns</b></label>
									<textarea class="form-control" id="linkForEmailCampaign" name="linkForEmailCampaign"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
							
