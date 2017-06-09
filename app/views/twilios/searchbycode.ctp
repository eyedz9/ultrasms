<script>
function searchzipNumber(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/buyNumber",
    data: {country:$('#country').val(),numbertype:$('#numbertype').val()},
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});
}
function searchareaNumber(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/areabuynumber",
    data: {country:$('#country').val(),numbertype:$('#numbertype').val()},
    success: function(result) {
        $('.nyroModalLink').html(result);
    }});
}
</script>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			<!--<i class="fa fa-users"></i>-->Select Option for Phone # Search
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<div class="form-group">
				<div id="validationMessages" style="display:none"></div>
				<div class="login-left">
					<div class="form-group">
						<p>Please select option to search for number</p>
						<div class="form-group">
							<input type="hidden" name="country" id="country" value="<?php echo $country;?>">
							<input type="hidden" name="numbertype" id="numbertype" value="<?php echo $numbertype;?>">
							<!-- <input type="hidden" name="SMS" id="SMS" value="<?php// echo $SMS;?>">
							<input type="hidden" name="MMS" id="MMS" value="<?php //echo $MMS;?>">
							<input type="hidden" name="Voice" id="Voice" value="<?php //echo $Voice;?>"> -->
							<?php /*if($numbertype == 'Local' && $country == 'US') { ?>
							<label>Zip code<span class="required_star"></span></label>
							<input type="button" style="cursor:pointer" class="btn blue " value="Click Here" id="zip" name="data[areacode][id]" value="1" onClick='searchzipNumber()'/>
						</div><?php }*/ ?>
						<div class="form-group">
							<label>Area code <span class="required_star"></span></label>
							<input type="button" class="btn green " style="cursor:pointer" value="Click Here" id="area" name="data[areacode][id]" value="2" onClick='searchareaNumber()' />
						</div>
					</div>	
					<?php echo $this->Form->end(); ?>		
				</div>
			</div>
		</div>
	</div>
</div>

