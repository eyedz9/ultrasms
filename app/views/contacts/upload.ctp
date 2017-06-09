	
<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#group_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Group Name"
                });
				jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Group Text"
                })
            });
            /* ]]> */	

var count = "158";


function update(){
var tex = $("#message").val();
tex = tex.replace('{','');
tex = tex.replace('}','');
tex = tex.replace(':','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace('`','');
tex = tex.replace("'","");
tex = tex.replace('"','');

var len = tex.length;
var message = $("#Preview").val();
var lenth = message.length;
$("#message").val(tex);
if(lenth > count){
tex = tex.substring(0,count);
$("#message").val(tex);
$("#Preview").val(tex);
return false;
}
$("#message").val(tex);
$("#limit").val(count-lenth);
$("#Preview").val(tex) ;
}
function update1(){


var tex = $("#group_name").val();
//alert(tex);


//tex = tex.replace(/[^a-zA-Z 0-9]+/g,'');
tex = tex.replace('{','');
tex = tex.replace('`','');
tex = tex.replace('}','');
tex = tex.replace(':','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace("'","");
tex = tex.replace('"','');





$("#group_name").val(tex);

}
function confirmmessage(){

if ($('#check').attr('checked') && $('#check1').attr('checked'))
{

form.submit();

}
else if($('#check1').attr('checked'))
{
var a=confirm('Click "Yes" or "OK" if you have a header in your csv file. You will need to check the box.');


if(a==true){
 

//window.location ="<?php echo SITE_URL ?>/contacts/importcontact";
return false;

}
else
{
form.submit();
}

}

else 
 {
 
 
document.getElementById('span').innerHTML="Before continuing, you must verify your contacts are opt-in";
return false;
 }
/*else
{

var a=confirm('Click "Yes" or "OK" if you have a header in your csv file. You will need to check the box.');


if(a==true){
 

return false;

}
else
{
return true;
}
}*/

}


</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title">Contacts</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li><span>Import Contacts </span></li>
				</ul>  			
			</div>			
			<div class="clearfix"></div>
			
	         <div class="portlet light ">
			 <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="fa fa-upload font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">Import Contacts</span>
                                    </div>
                                
                                </div>
                          <?php echo $this->Session->flash(); ?>	
                                <div class="portlet-body form">
                                  <?php echo $this->Form->create('Contact',array('action'=> 'show_next','name'=>'loginForm','id'=>'loginForm','enctype'=>'multipart/form-data','method'=>'post','onsubmit'=>'return confirmmessage();'));?>
                                        <div class="form-body">
                                          
                                            <div class="form-group">
											
                                                <label for="exampleInputFile1">Import Contacts</label>
                                                <input id="contact" type="file" name="data[contact]">
                                                <p class="help-block">		<label>Please upload CSV file only - names in 1st column, phone # in 2nd</label>
												<label>NO spaces, dashes, or parentheses in phone numbers</label>
												<br/><br/><label><font style="color:red">Include country code in the number</font></label>
												<br/><label>US Example: 12025248725</label></br>
												<label>UK Example: 447481340516</label>	 </p>
                                            </div>
                                            <div class="form-group">
		
                                                <div class="checkbox-list">
                                                    <label>
                                                        <?php echo $this->Form->input('contact.header',array('div'=>false,'class'=>'inputbutton','label'=>false,'type'=>'checkbox','value'=>1,'id'=>'check'));?>&nbsp;Yes, this file contains headers. </label>
                                                    <label>
                                                        <?php echo $this->Form->input('contact.header1',array('div'=>false,'class'=>'inputbutton','label'=>false,'type'=>'checkbox','value'=>1,'id'=>'check1'));?>&nbsp;Yes, these imported contacts are 100% opt-in. <a href="javascript:;" data-container="body" data-trigger="hover" data-content="These contacts must be 100% opt-in and have already given permission to you to receive SMS notifications. This import utility is intended to be used when migrating over your list of opt-in contacts from another service or collected from your website which clearly states why you're collecting phone numbers. If importing contacts that are NOT opt-in, your account with us will be banned." data-original-title="Must Be 100% Opt-In" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a></label>
                                                    <?php if(API_TYPE==2){?>
<br/><div class="note note-info"><span class="text-muted">Each person imported will receive the group’s auto-reply message. So if you are importing 1000 contacts, 1000 auto-reply messages will be sent. Manually adding/importing numbers on a shortcode follows the same process as if the user had texted in the keyword themselves to join the list. </label></span></div>
                                                    <?} ?>    
                                                </div>
                                            </div>
                                         
                                         
                                        </div>
                                        <div class="form-actions">
                                            
											<?php echo $this->Form->submit('Next',array('div'=>false,'class'=>'btn blue'));?>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
		</div>
  </div> 
