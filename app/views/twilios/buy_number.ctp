<script>
function searchNumber(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/searchnumber",
	data: {
	zip:$('#TwilioAreacode').val(),
	country:$('#country').val(),
	numbertype:$('#numbertype').val()
	},
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});
}
</script>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			<!--<i class="fa fa-users"></i>-->Available Phone # Search
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<div class="form-group">
					<!--Javascript validation goes Here---------->
						<div id="validationMessages" style="display:none"></div>
						<!--Javascript validation goes Here---------->
					<?php //echo $this->Form->create('',array('controller'=>'twilios','action' => 'searchnumber'));?>
				<div class="login-left">
					<div class="form-group">
						<p>Please enter zip code to search for number (optional) </p>
						<label>Zip code (like 49855): <span class="required_star"></span></label>
						<?php echo $this->Form->input('Twilio.areacode',array('div'=>false,'label'=>false, 'class' => 'form-control'))?>
					</div>
					<input type="hidden" name="country" id="country" value="<?php echo $country;?>">
					<input type="hidden" name="numbertype" id="numbertype" value="<?php echo $numbertype;?>">
					<!-- <input type="hidden" name="SMS" id="SMS" value="<?php //echo $SMS;?>">
					<input type="hidden" name="MMS" id="MMS" value="<?php //echo $MMS;?>">
					<input type="hidden" name="Voice" id="Voice" value="<?php //echo $Voice;?>"> -->
					<div class="form-group">
							<?php echo $this->Form->button('SEND',array('div'=>false,'class'=>'btn blue','onClick'=>'searchNumber()'));?>
					</div>
				</div>		
			</div>
		</div>		
	</div>		
</div>


