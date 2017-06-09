<script>
function searchNumber(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/nexmos/searchnumber",
    data: {
	pattren:$('#NexmoPattren').val(),
	country:$('#country').val(),
	feature:$('#feature').val()
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
		<div id="validationMessages" style="display:none"></div>
		<div class="login-left">
			<div class="form-group" >
				<p>Please enter pattern to search for number (Leave blank to bring back any number) </p>
				<label style="margin-top:7px">Pattern code (like 1815 or 1630): <span class="required_star"></span></label>
				<?php echo $this->Form->input('Nexmo.pattren',array('div'=>false,'label'=>false, 'class' => 'form-control'))?>
			</div>
				<input type="hidden" name="country" id="country" value="<?php echo $country;?>">
				<input type="hidden" name="feature" id="feature" value="<?php echo $feature;?>">
			<div class="form-group">
				<?php echo $this->Form->button('SEND',array('div'=>false,'class'=>'btn blue','onClick'=>'searchNumber()'));?>
			</div>
		</div>		
	</div>
</div>