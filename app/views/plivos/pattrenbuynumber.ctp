<script>
function searchNumber(){
	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/plivos/searchnumber",
    data: {
	pattren:$('#PlivoPattren').val(),
	country:$('#country').val(),
	services:$('#services').val()
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
				<label style="margin-top:7px">Pattern code (like 815 or 630): <span class="required_star"></span></label>
				<?php echo $this->Form->input('Plivo.pattren',array('div'=>false,'label'=>false, 'class' => 'form-control'))?>
			</div>
				<input type="hidden" name="country" id="country" value="<?php echo $country;?>">
				<input type="hidden" name="services" id="services" value="<?php echo $services;?>">
			<div class="form-group">
				<?php echo $this->Form->button('SEND',array('div'=>false,'class'=>'btn blue','onClick'=>'searchNumber()'));?>
			</div>
		</div>		
	</div>
</div>