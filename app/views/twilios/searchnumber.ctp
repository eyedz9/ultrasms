<script>
	function selectcountry(){
	  $.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/searchcountry",
		success: function(result) {
			$('.nyroModalLink').html(result);
		}});	
	}
	function assignthisnumber(numbertoassign){
		voice=$("#voice").val();
		sms=$("#sms").val();
		mms=$("#mms").val();
		 $.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/assignthisnumber",
			data: {number:numbertoassign,voice:voice,mms:mms,sms:sms},
			success: function(result) {
			if(result=='success'){
			   window.location = '<?php echo SITE_URL;?>/users/profile';
			   }
			}});	
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
						<!--<div class="portlet-title">
							<div class="caption">
								<i class="icon-settings font-dark"></i>
								<span class="caption-subject font-red sbold uppercase">Phone Number</span>
							</div>
						</div>-->
						<!--<div class="portlet-body">-->
							<div class="row">
								<!--<div class="col-md-12">-->
								   <div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
											<?php if(!empty($AvailablePhoneNumbers)){?>
											<?php foreach($AvailablePhoneNumbers as $number):?>
											<form method="POST">
												<tr>
													<td>
														<label><?php echo $number->FriendlyName ?></label>
														<label>Capabilities:</label>
														<label style="color:green;">
															<?php if($number->Capabilities->Voice=='true'){echo '(Voice)';?>
															<input type="hidden" value="1" name="voice" id="voice"/><?php }?>
															<?php if($number->Capabilities->SMS=='true'){echo '(SMS)';?>
															<input type="hidden" value="1" name="sms" id="sms" /><?php }?>
															<?php if($number->Capabilities->MMS=='true'){echo '(MMS)';?>
															<input type="hidden" value="1" name="mms" id="mms" /><?php }?>
														</label>
														<input type="hidden" name="PhoneNumber" value="<?php echo $number->PhoneNumber ?>">
													</td>
													<td style="vertical-align:middle">
														<input type="button" style="margin-left:20px;" class="btn blue btn-outline 	btn-sm" name="button" value="Get this number"  onClick="assignthisnumber(<?php echo $number->PhoneNumber ?>)"/>
													</td>
												</tr>
											</form>
											<?php endforeach; ?>
											<?php }else{?>
											<tr>
												<a style="color:#12759e;font-size:20px;margin-left:0px;" href="#null" onClick="selectcountry()">Search Again</a>
											</tr>
											<?php } ?>
										</table>
									</div>
								<!--</div>		-->
							</div>
						<!--</div>		-->
					</div>		
				</div>
			</div>
		</div>
	</div>

