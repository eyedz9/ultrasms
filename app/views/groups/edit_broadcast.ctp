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
					expression: "if (VAL) return true; else return false;",
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
	$('#VoiceMessageMp3').live("change", function(event) {
		var files = event.target.files; //FileList object
		for(var i = 0; i< files.length; i++){
			var file = files[i];
			if(file.type.match('audio.*')){
				check_image();
			}else{
				alert("You can only upload an audio file.");
				$(this).val("");
			return false;
		}
		}
	});	
		function validateForm(){	
			if($('#GroupMsgType0').prop('checked')==true){	
				var TextMessage=$('#VoiceMessageTextMessage').val();	
				 if(TextMessage==''){	 
				 alert('Please enter a message');	 
				  return false;	  
				 }	 
			 }else if($('#GroupMsgType1').prop('checked')==true){		
					var filevalidate=$('#file_validation').val();
					if(filevalidate==0){		
					 alert('Please upload a new audio file');
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
					<li>
						<a href="<?php echo SITE_URL;?>/groups/broadcast_list">Voice Broadcasts</a>
					</li>
				</ul>  			
			</div>			
			<div class="clearfix"></div>
			
			<div class="portlet mt-element-ribbon light portlet-fit  ">
			    <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
edit voice broadcast form
</div>
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-bullhorn font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"></span>
					</div>			                             
				</div>
					<?php echo $this->Session->flash(); ?>					
				<div class="portlet-body form">
					<?php echo $this->Form->create('Group',array('action'=> 'edit_broadcast','enctype'=>'multipart/form-data','onsubmit'=>'return validateForm()'));?>
					<input type="hidden" value="<?php echo $broad_cast['VoiceMessage']['id']; ?>" id="id" name="data[VoiceMessage][id]" />
					<input type="hidden" value="<?php echo $broad_cast['VoiceMessage']['message_type']; ?>" id="audio_file" />
					<input type="hidden" id="file_validation" value="0" >
					<div class="form-body">													
						<div class="form-group">
							<label>Select Group</label>
							<select class="form-control" id="group_id" name="data[VoiceMessage][group_id]" readonly>
							<?php foreach($group_ids as $value){?>
							<option value="<?php echo $value['Group']['id']; ?>" <?php if($value['Group']['id']==$broad_cast['VoiceMessage']['group_id']){ ?> selected <?php } ?> ><?php echo ucfirst($value['Group']['group_name']); ?>
								<?php }?>
								</select>
						</div>
						<fieldset>
						<div class="form-group">
							<label>Voice Broadcast Msg Type</label>
							</br>
							<!--<div class="radio-inline">-->
								<input type="radio" value="0" style="float:none" id="GroupMsgType0" <?php if( $broad_cast['VoiceMessage']['message_type']  ==0){ echo 'checked';}?> name="data[VoiceMessage][msg_type]">		
								<label for="GroupMsgType0">Text To Voice</label>
								<input type="radio" value="1" style="float:none" <?php if( $broad_cast['VoiceMessage']['message_type']  ==1){ echo 'checked';}?> id="GroupMsgType1" name="data[VoiceMessage][msg_type]">
								<label for="GroupMsgType1">MP3 Audio</label>
								<input type="hidden" id="file_validation" value="0" >
							<!--</div>-->
						</div>
						</fieldset>
							<?php if( $broad_cast['VoiceMessage']['message_type']  ==0){?>
						<div class="form-group" id="text_to_voice" style = "display:inline;">
							<?php echo $this->Form->textarea('VoiceMessage.text_message',array('div'=>false,'label'=>false,'class' => 'form-control','value'=>$broad_cast['VoiceMessage']['text_message']))?>
						</div>
							<?php }else{ ?>
						<div class="form-group" id="text_to_voice" style = "display:none;">
							<?php echo $this->Form->textarea('VoiceMessage.text_message',array('div'=>false,'label'=>false,'class' => 'form-control','value'=>$broad_cast['VoiceMessage']['text_message']))?>
						</div>
							<?php } ?>
							<?php if($broad_cast['VoiceMessage']['message_type']  ==1){?>	
						<div  id="audio_path" style = "display:inline;">								
							
<input type="file" id="VoiceMessage.mp3" name="data[VoiceMessage][mp3]" onclick="check_image()"/>
							<audio class="audio" controls="controls" style="height: 18px;margin-left: 5px;margin-top: 12px;">
								<source src="<?php echo SITE_URL ?>/voice/<?php echo $broad_cast['VoiceMessage']['audio']; ?>" type="audio/mpeg">
							</audio>
						</div>
							<?php }else{?>								
						<div class="form-group" id="audio_path" style = "display:none;">								
							<!--<?php echo $this->Form->input('VoiceMessage.mp3',array('div'=>false,'label'=>false,'type'=>'file'))?>-->
<input type="file" id="VoiceMessage.mp3" name="data[VoiceMessage][mp3]" onclick="check_image()"/>
							<audio class="audio" controls="controls" style="height: 18px;margin-left: 5px;margin-top: 12px;">
								<source src="<?php echo SITE_URL ?>/mp3/<?php echo $broad_cast['VoiceMessage']['audio']; ?>" type="audio/mpeg">
							</audio>
						</div>																	
					</div>
						<?php }	?>			
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
			});
			
			</script>


