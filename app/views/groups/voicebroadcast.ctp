<style>
.ValidationErrors{

color:red;
}
@media only screen and ( max-width:480px ){

.audio{ margin-top:60px !important;}
}
</style>
<script>
            jQuery(function(){
			 jQuery("#groupname").validate({
                    expression: "if (VAL > 0) return true; else return false;",
                    message: "Please enter groupname"
                });jQuery("#username").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter username"
                });jQuery("#keyword").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter keyword"
                });jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter message"
                });
				
            });	
</script>
<script type="text/javascript">
 $(document).ready(function (){
$('textarea[maxlength]').live('keyup change', function() {
  var str = $(this).val()
  var mx = parseInt($(this).attr('maxlength'))
  if (str.length > mx) {
     $(this).val(str.substr(0, mx))
     return false;
  }
  }
  )
});
</script>
<script>


var count = "123";

function update(){
var tex = $("#message").val();

//var msg = $("#GroupAutoMessage").val();

$count1=tex.length;

//var count1 = (123-(msg.length));

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




//var message = $("#Preview").val();
//var lenth = msg.length;


$("#message").val(tex);
if(len > count){
tex = tex.substring(0,count1);
//$("#message").val(tex);
//$("#Preview").val(tex+msg);
return false;
}

//var alert1=(count1-len)
//alert(alert1);

$("#limit").val(count-len);

//$("#Preview").val(tex+msg) ;
}
function update1(){


var tex = $("#groupname").val();
//alert(tex);


//tex = tex.replace(/[^a-zA-Z 0-9]+/g,'');
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
</script>

<?php
$groups[0]='Select group';


?>
	<div class="page-content-wrapper">
		<div class="page-content">              
				<h3 class="page-title"> Voice Broadcasts</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
								<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li><a href="<?php echo SITE_URL;?>/groups/broadcast_list">Voice Broadcasts</a></li>
					</ul>  			
				</div>			
				<div class="clearfix"></div>
				
				<div class="portlet mt-element-ribbon light portlet-fit  ">
				   <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create voice broadcast form
</div>
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-bullhorn font-red-sunglo"></i>
							<span class="caption-subject bold uppercase"> </span>
						</div>			                             
					</div>	
					<?php echo $this->Session->flash(); ?>						
					<div class="portlet-body form">
						<?php echo $this->Form->create('Group',array('action'=> 'voicebroadcast','enctype'=>'multipart/form-data','onsubmit'=>'return validateForm()'));?>
						<input type="hidden" value="<?php echo $group['Group']['message_type']; ?>" id="audio_file" />
					<div class="form-group">							
						<!--<table class="table table-bordered table-striped">
				<tbody>
					<tr>
						<td>
						<div class="alert alert-warning"><b>NOTE:</b></span> If you intend to send voice broadcasts to this group who have opted-in to receive SMS messages from you, make sure on your digital and print media where these contacts are subscribing to your SMS list, that there is some writing on there stating they will also be receiving voice calls as well. It's imperative that your subscribers know what they're signing up for.	
						</td>
					</tr>
				</tbody>
			</table>	-->
<div class="note note-warning"><b>NOTE:</b> If you intend to send voice broadcasts to this group who have opted-in to receive SMS messages from you, make sure on your digital and print media where these contacts are subscribing to your SMS list, that there is some writing on there stating they will also be receiving voice calls as well. It's imperative that your subscribers know what they're signing up for.	</div></div>		
						<div class="form-group">
							<label>Select Group</label>
							<?php echo $this->Form->input('Group.id',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'groupname','type'=>'select','options'=>$groups,'default'=>0))?>
						</div>
						<fieldset>
						<div class="form-group">
							<label>Voice Broadcast Msg Type</label>
							</br>
							<!--<div class="radio-inline">-->
								<input type="radio" value="0" style="float:none" id="GroupMsgType0" <?php if( $group['Group']['message_type']  ==0){ echo 'checked';}?> name="data[Group][msg_type]">		
								<label for="GroupMsgType0">Text To Voice</label>
								<input type="radio" value="1" style="float:none" <?php if( $group['Group']['message_type']  ==1){ echo 'checked';}?> id="GroupMsgType1" name="data[Group][msg_type]" >
								<label for="GroupMsgType1">MP3 Audio</label>
								<input type="hidden" id="file_validation" value="0" >
							<!--</div>-->
						</div>
						</fieldset>
							<?php if( $group['Group']['message_type']  ==0){?>
						<div class="form-group" id="text_to_voice" style = "display:inline;">
							<?php echo $this->Form->textarea('Group.text_message',array('div'=>false,'label'=>false,'class'=>'form-control'))?>
						</div>
							<?php }else{ ?>
						<div class="form-group" id="text_to_voice" style = "display:none;">
							<?php echo $this->Form->textarea('Group.text_message',array('div'=>false,'label'=>false,'class'=>'form-control'))?>
						</div>
							<?php } ?>
							<?php if( $group['Group']['message_type']  ==1){?>	
						<div id="audio_path" style = "display:none;">								
							<!--<?php echo $this->Form->input('Group.mp3',array('div'=>false,'label'=>false,'type'=>'file'))?>-->
<input type="file" id="Group.mp3" name="data[Group][mp3]" onclick="check_image()"/>
							<audio class="audio" controls="controls" style="height: 18px;margin-left: 5px;margin-top: 12px;">
								<source src="<?php echo SITE_URL ?>/mp3/<?php echo $group['Group']['audio_name']; ?>" type="audio/mpeg">
							</audio>
						</div>
							<?php }else{?>								
						<div class="form-group" id="audio_path" style = "display:none;">								
							<!--<?php echo $this->Form->input('Group.mp3',array('div'=>false,'label'=>false,'type'=>'file'))?>-->
<input type="file" id="Group.mp3" name="data[Group][mp3]" onclick="check_image()"/>
							<audio class="audio" controls="controls" style="height: 18px;margin-left: 5px;margin-top: 12px;">
								<source src="<?php echo SITE_URL ?>/mp3/<?php echo $group['Group']['audio_name']; ?>" type="audio/mpeg">
							</audio>
						</div>																	
					</div>
					<?php }	?>	<br/>		
					<div class="form-actions">                                        
						<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
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
			

$('#GroupMp3').live("change", function(event) {


var files = event.target.files; //FileList object

for(var i = 0; i< files.length; i++)
{
var file = files[i];



if(file.type.match('audio.*')){


check_image();

}else{
alert("You can only add an audio file");
$(this).val("");
return false;
}
}



});		
			
			
	});
			
		function validateForm(){
		
	if($('#GroupMsgType0').prop('checked')==true){
 
	
	var TextMessage=$('#GroupTextMessage').val();
	
	 if(TextMessage==''){
	 
	 alert('Please enter a message');
	 
	  return false;
	  
	 }
	 
	 }else if($('#GroupMsgType1').prop('checked')==true){
		
		var filevalidate=$('#file_validation').val();
		if(filevalidate==0){
		
		 alert('Please upload your audio file');
		   return false;
		}
		}
		
	}
			
			
function check_image(){

 if($('#GroupMsgType1').prop('checked')==true){
 
 $('#file_validation').val(2);
 
 }
}			
			</script>