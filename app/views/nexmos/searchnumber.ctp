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
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/nexmos/searchcountry",
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});
}
function assignthisnumber(numbertoassign){
	$("body").css("cursor", "progress");
	var sms = $('#sms').val();
	var voice = $('#voice').val();
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/nexmos/assignthisnumber",
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
      <div class="portlet-title">
       <!--<div class="caption">
        <i class="icon-settings font-dark"></i>
        <span class="caption-subject font-red sbold uppercase">Phone Number</span>
       </div> -->
	   <div class="caption">
			<span class="caption-subject font-blue sbold uppercase"><b>Please allow around 10 seconds after clicking the "Get this number" button to assign the number.</b></span>
       </div>
      </div>
      
       <div class="row">
        <!--<div class="col-md-12 ">-->
         <div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">

				<?php
				if(!empty($AvailablePhoneNumbers->numbers)){
				foreach($AvailablePhoneNumbers->numbers as $phone){
							?>
				<form method="POST">
					<tr><td><label><?php echo $phone->msisdn ?>(<span style="color:red;"><?php echo ucfirst($phone->type); ?>)</label>
					<label style="color:green;">
					
					<?php if(isset($phone->features[0])){ ?>
					
					(<?php echo $phone->features[0];?>)
					<input type="hidden" name="voice" id="voice" value="<?php echo $phone->features[0];?>">
				    <?php }else{ ?>
					<input type="hidden" name="voice" id="voice" value="">
					<?php } ?>
                    <?php if(isset($phone->features[1])){?>
					
					(<?php echo $phone->features[1]; ?>)
					<input type="hidden" name="sms" id="sms" value="<?php echo $phone->features[1]; ?>">
					<?php }else{ ?>
					<input type="hidden" name="sms" id="sms" value="">
					<?php } ?>
					</label>
					<input type="hidden" name="PhoneNumber" value="<?php echo $phone->msisdn; ?>">
					<input type="hidden" id="country_code" name="country_code" value="<?php echo $country_code; ?>"></td>
					<td style="vertical-align:middle"><input type="button" style="margin-left:20px;" class="btn blue btn-outline btn-sm'" name="button" value="Get this number"  onClick="assignthisnumber(<?php echo $phone->msisdn; ?>)"/></td></tr>
					
					
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

