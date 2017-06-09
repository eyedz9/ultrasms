<style>

.flag-sec label { width: 100%;}
.flag-sec #image { margin: 0 12px 0 0;}
.cntry-slct {   width: auto; }

.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 300px;
    min-width: 500px;
    padding: 10px;
    position: relative;
}

</style>
<script>
	function selectcountry(){
		var country=$('#TwilioCountry').val();
		if((country=='US') ||  (country=='CA')){
			$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/searchbycode",
			data: {country:$('#TwilioCountry').val(),numbertype:$('#TwilioType').val()},
			success: function(result) {
			$('.nyroModalLink').html(result);
			}});
		}else{
			$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/twilios/searchnumber",
			data: {country:$('#TwilioCountry').val(),numbertype:$('#TwilioType').val()},
			success: function(result) {
			$('.nyroModalLink').html(result);
			}});
		} 	
	}
</script>
<script type="text/javascript">
	function change(data){
		if(data == 'AU'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Australia.png'>");
			$("#selcttype").html('<label>Select Type</label><select  class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'AT'){ 
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Austria.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'EE'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Estonia.png'>");
			$("#selcttype").html('<label>Select Type</label><select  class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'FI'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Finland.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'HK'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Hong Kong.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType" name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'IE'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Ireland.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'LT'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Lithuania.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'NO'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Norway.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'PL'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Poland.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'ES'){
		$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Spain.png'>");
		$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option></select>');
		}else if(data == 'SE'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Sweden.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');

		}else if(data == 'CH'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Switzerland.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'BE'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Belgium.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'CA'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Canada.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><?php if(ALLOWTOLLFREENUMBERS==0){ ?><option value="TollFree">TollFree</option><?php } ?></select>');
		}else if(data == 'GB'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Great Britain.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><option value="Mobile">Mobile</option></select>');
		}else if(data == 'US'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/United States.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><?php if(ALLOWTOLLFREENUMBERS==0){ ?><option value="TollFree">TollFree</option><?php } ?></select>');
		}else if(data == 'DE'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Germany.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Mobile">Mobile</option></select>');
		}else if(data == 'MX'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Mexico.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'PR'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/PuertoRico.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local </option></select>');
		}else if(data == 'DK'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Denmark.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'IL'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Israel.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'HU'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/Hungary.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType"  name="data[Twilio][type]"><option value="Mobile">Mobile (SMS Number)</option></select>');
		}else if(data == 'FR'){
			$("#image").html("<img style='width:45px !important' src='<?php echo SITE_URL; ?>/flags/France.png'>");
			$("#selcttype").html('<label>Select Type</label><select class="form-control" id="TwilioType" name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>');
		}
	}
	
</script>
<style>
	.feildbox img{
	width:30px!important;
	}
</style>
<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			<!--<i class="fa fa-users"></i>-->
			Phone Number Search
		</div>
	</div>
<div class="portlet-body">
 <?php $active = $this->Session->read('User.active');
$usercountry = $this->Session->read('User.user_country');
?>
<input type="hidden" id="usercountry" value="<?php echo $usercountry?>"/>

<?php if ($active==0){?>
					<h3>Oops! You need to activate your account to use this feature.</h3><br>
					<?php $payment=PAYMENT_GATEWAY;
					if($payment=='1' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
						Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>	'users', 'action' =>'activation/'.$payment))?>.<br />
					<?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
						Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('Credit Card', array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
					<?php } ?> 

	<?php   }else{ ?>

	
		<div id="validationMessages" style="display:none"></div>
		<div class="form-body">
			<div class="form-group flag-sec">
				<p><!--Please select country to search number.--></p>
				<label>Country</label>
				<?php
				//$Option=array('US'=>'United States','GB'=>'United Kingdom','CA'=>'Canada','AU'=>'Australia');		//$Option=array('AU'=>'Australia','AT'=>'Austria','BH'=>'Bahrain','BE'=>'Belgium','BR'=>'Brazil','BG'=>'Bulgaria','CA'=>'Canada','CY'=>'Cyprus','CZ'=>'Czech Republic','DK'=>'Denmark','DO'=>' Dominican Republic','SV'=>'El Salvador','EE'=>'Estonia','FI'=>'Finland','FR'=>'France','GR'=>'Greece','HK'=>'Hong Kong','IE'=>'Ireland','IL'=>'Israel','IT'=>'Italy','JP'=>'Japan','LV'=>'Latvia','LT'=>'Lithuania','LU'=>'Luxembourg','MT'=>'Malta','MX'=>'Mexico','NL'=>'Netherlands','NZ'=>'New Zealand','NO'=>'Norway','PE'=>'Peru','PL'=>'Poland','PT'=>' Portugal','PR'=>'Puerto Rico','RO'=>'Romania','SK'=>'Slovakia','ZA'=>'South Africa','ES'=>'Spain','SE'=>'Sweden','CH'=>'Switzerland','GB'=>'United Kingdom','US'=>'United States');
				/*$Option=array('AU'=>'Australia','AT'=>'Austria','EE'=>'Estonia','FI'=>'Finland','HK'=>'Hong Kong','IE'=>'Ireland','LT'=>'Lithuania','NO'=>'Norway','PL'=>'Poland','ES'=>'Spain','SE'=>'Sweden','CH'=>'Switzerland','BE'=>'Belgium','CA'=>'Canada','GB'=>'United Kingdom','US'=>'United States');
				echo $this->Form->input('Twilio.country', array(
                                'class'=>'form-control input-xlarge',				
                                'label'=>false,
				'type'=>'select',
				'options' => $Option));*/
				?>
				<div id='image' style='display:inline-block;float:left;' ></div>
				<select  class="form-control input-large" id="TwilioCountry"  onchange="change(this.value)">
<?php if ($usercountry == 'Australia') { ?>
					<option value='AU' style='background: url("<?php echo SITE_URL; ?>/flags/Australia.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>Australia</option>

<?php } else if ($usercountry == 'Austria') { ?>
					 <option value='AT' style='background: url("<?php echo SITE_URL; ?>/flags/Austria.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					 Austria</option>

<?php } else if ($usercountry == 'Belgium') { ?>
					 <option value='BE' style='background: url("<?php echo SITE_URL; ?>/flags/Belgium.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Belgium</option>

<?php } else if ($usercountry == 'Canada') { ?>
					<option value='CA' style='background: url("<?php echo SITE_URL; ?>/flags/Canada.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Canada</option>


<?php } else if ($usercountry == 'Denmark') { ?>
					<option value='DK' style='background: url("<?php echo SITE_URL; ?>/flags/Denmark.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Denmark</option>

<?php } else if ($usercountry == 'Estonia') { ?>
					 <option value='EE' style='background: url("<?php echo SITE_URL; ?>/flags/Estonia.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Estonia</option>

<?php } else if ($usercountry == 'Finland') { ?>
					<option value='FI' style='background: url("<?php echo SITE_URL; ?>/flags/Finland.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Finland</option>

<?php } else if ($usercountry == 'France') { ?>
					<option value='FR' style='background: url("<?php echo SITE_URL; ?>/flags/France.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					France</option>

<?php } else if ($usercountry == 'Germany') { ?>
					<option value='DE' style='background: url("<?php echo SITE_URL; ?>/flags/Germany.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Germany</option>

<?php } else if ($usercountry == 'Hong Kong') { ?>
					<option value='HK' style='background: url("<?php echo SITE_URL; ?>/flags/Hong Kong.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>Hong Kong</option>

<?php } else if ($usercountry == 'Hungary') { ?>
					<option value='HU' style='background: url("<?php echo SITE_URL; ?>/flags/Hungary.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Hungary</option>

<?php } else if ($usercountry == 'Ireland') { ?>

					<option value='IE' style='background: url("<?php echo SITE_URL; ?>/flags/Ireland.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>Ireland</option>

<?php } else if ($usercountry == 'Israel') { ?>
					<option value='IL' style='background: url("<?php echo SITE_URL; ?>/flags/Israel.png") 0 0 no-repeat;padding-left: 50px; height: 30px'>
					Israel</option>


<?php } else if ($usercountry == 'Lithuania') { ?>
					<option value='LT' style='background: url("<?php echo SITE_URL; ?>/flags/Lithuania.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Lithuania</option>


<?php } else if ($usercountry == 'Mexico') { ?>
					<option value='MX' style='background: url("<?php echo SITE_URL; ?>/flags/Mexico.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Mexico</option>

<?php } else if ($usercountry == 'Netherlands') { ?>
					<option value='NL' style='background: url("<?php echo SITE_URL; ?>/flags/Netherlands.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Netherlands</option>


<?php } else if ($usercountry == 'Norway') { ?>
					<option value='NO' style='background: url("<?php echo SITE_URL; ?>/flags/Norway.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Norway</option>


<?php } else if ($usercountry == 'Poland') { ?>
					<option value='PL' style='background: url("<?php echo SITE_URL; ?>/flags/Poland.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Poland</option>


<?php } else if ($usercountry == 'Puerto Rico') { ?>
					<option value='PR' style='background: url("<?php echo SITE_URL; ?>/flags/PuertoRico.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Puerto Rico</option>


<?php } else if ($usercountry == 'Spain') { ?>
					<option value='ES' style='background: url("<?php echo SITE_URL; ?>/flags/Spain.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Spain</option>


<?php } else if ($usercountry == 'Sweden') { ?>
					<option value='SE' style='background: url("<?php echo SITE_URL; ?>/flags/Sweden.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Sweden</option>


<?php } else if ($usercountry == 'Switzerland') { ?>
					<option value='CH' style='background: url("<?php echo SITE_URL; ?>/flags/Switzerland.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>Switzerland</option>


<?php } else if ($usercountry == 'United Kingdom') { ?>
					<option value='GB' style='background: url("<?php echo SITE_URL; ?>/flags/Great Britain.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>United Kingdom</option>


<?php } else if ($usercountry == 'United States') { ?>
					<option value='US' style='background: url("<?php echo SITE_URL; ?>/flags/United States.png") 0 0 no-repeat;padding-left: 50px; 
					height: 30px'>United States</option>
<?php } ?>
		</select>
			</div> 

			<div class="form-group" id="selcttype"></div> 
<?php if ($usercountry == 'Australia') { ?>

<label>Select Type</label><select  class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if ($usercountry == 'Austria') { ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Estonia'){ ?>

<label>Select Type</label><select  class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Finland'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Hong Kong'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType" name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Ireland'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Lithuania'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Netherlands'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Norway'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Poland'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Spain'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option></select>

<?php } else if($usercountry == 'Sweden'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Switzerland'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Belgium'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Canada'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><?php if(ALLOWTOLLFREENUMBERS==0){ ?><option value="TollFree">TollFree</option><?php } ?></select>

<?php } else if($usercountry == 'United Kingdom'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><option value="Mobile">Mobile</option></select>

<?php } else if($usercountry == 'United States'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local</option><?php if(ALLOWTOLLFREENUMBERS==0){ ?><option value="TollFree">TollFree</option><?php } ?></select>

<?php } else if($usercountry == 'Germany'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Mobile">Mobile</option></select>

<?php } else if(data == 'Mexico'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Puerto Rico'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local </option></select>

<?php } else if($usercountry == 'Denmark'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Israel'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'Hungary'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType"  name="data[Twilio][type]"><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } else if($usercountry == 'France'){ ?>

<label>Select Type</label><select class="form-control input-large" id="TwilioType" name="data[Twilio][type]"><option value="Local">Local (Voice Number)</option><option value="Mobile">Mobile (SMS Number)</option></select>

<?php } ?>


			<!-- <div class="feildbox">
			<label>Search Capability</label>
				SMS<input type="checkbox" name="sms" value="SMS" id="sms" checked />
				MMS<input type="checkbox" name="mms" value="MMS" id="mms" checked />
				Voice<input type="checkbox" name="voice" value="Voice" id="voice" checked />
			</div>  -->
<br/>
			<div class="form-group">
			<?php echo $this->Form->button('Search',array('div'=>false,'class'=>'btn blue ','onClick'=>'selectcountry()'));?>
			</div>
			<?php echo $this->Form->end(); ?>		
		</div>
	</div>
<?php } ?>	
</div>


