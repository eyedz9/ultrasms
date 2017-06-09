<!--<style type="text/css">
	
.nyroModalCont{
 
    width:44%!important;

}
.nmReposition{
 left: 932.5px !important;

}

@media only screen and ( max-width:767px ){

.nyroModalCont{ width:70%!important;}
}

@media only screen and ( max-width:600px ){

.nyroModalCont{ width:100%!important;}
}

</style>-->
<script>
function selectcountry(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/plivos/searchcountry",
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});
}
function assignthisnumber(numbertoassign){
	$("body").css("cursor", "progress");
	var sms = $('#sms').val();
	var voice = $('#voice').val();
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/plivos/assignthisnumber",
	data: {number:numbertoassign,sms:sms,voice:voice,country:$('#country_code').val()},
		success: function(result) {
			if(result=='sucess'){
				window.location = '<?php echo SITE_URL;?>/users/profile';
			}
		}
	});
}

</script>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			<!--<i class="fa fa-users"></i>-->Choose a Phone Number
		</div>
	</div>
<div class="portlet-body">
<div class="form-body">
<div class="form-group">
     <div><?php echo $this->Session->Flash();?></div>      
 <div class="portlet light portlet-fit ">
      
      
       <div class="row">
        <!--<div class="col-md-12 ">-->
         <div class="table-scrollable">
				<table class="table table-striped table-bordered table-hover">
				<?php
				if(!empty($AvailablePhoneNumbers)){
					foreach($AvailablePhoneNumbers as $phone){ ?>
					<form method="POST">
						<tr><td><label><?php echo $phone['number']; ?>(<span style="color:red;"><?php echo ucfirst($phone['type']); ?>)</label>
						<label style="color:green;">
						
						<?php if((isset($phone['voice_enabled'])) && ($phone['voice_enabled']==1)){ ?>
						
						(VOICE)
						<input type="hidden" name="voice" id="voice" value="1">
						<?php }else{ ?>
						<input type="hidden" name="voice" id="voice" value="0">
						<?php } ?>
						<?php if((isset($phone['sms_enabled'])) && ($phone['sms_enabled']==1)){ ?>
						
						(SMS)
						<input type="hidden" name="sms" id="sms" value="1">
						<?php }else{ ?>
						<input type="hidden" name="sms" id="sms" value="0">
						<?php } ?>
						</label>
						<input type="hidden" name="PhoneNumber" value="<?php echo $phone['number']; ?>">
						<input type="hidden" id="country_code" name="country_code" value="<?php echo $country_code; ?>"></td>
						<td style="vertical-align:middle"><input type="button" style="margin-left:20px;" class="btn blue btn-outline btn-sm'" name="button" value="Get this number"  onClick="assignthisnumber(<?php echo $phone['number']; ?>)"/></td></tr>
						
						
					</form>
					<?php } ?>
				<?php }else{ ?>
					<tr><a style="color:#12759e;font-size:20px;margin-left:0px;" href="#null" onClick="selectcountry()">Search Again</a></tr>
				<?php } ?>
			</table>
			<!--</div>		-->
		</div>		
	</div>

</div>
</div>
</div>
</div>
</div>

