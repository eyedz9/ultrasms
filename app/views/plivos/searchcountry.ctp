<style>

.flag-sec label { width: 100%;}
.flag-sec #image { margin: 0 12px 0 0;}
.cntry-slct {   width: auto; }

.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 300px;
    min-width: 450px;
    padding: 10px;
    position: relative;
}
</style>


<script>
function selectcountry(){
  var country=$('#PlivoCountry').val();
  var services=$('#services').val();
  	$.ajax({type: "POST",url:"<?php echo SITE_URL ?>/plivos/pattrenbuynumber",
    data: {country:$('#PlivoCountry').val(),services:services},
    success: function(result){
        $('.nyroModalLink').html(result);
    }});
  }
</script>
 <script type="text/javascript">
	function change(data){
		if(data == 'AU'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Australia.png'>");
		}else if(data == 'AT'){ 
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Austria.png'>");
		}
		else if(data == 'CA'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Canada.png'>");
		}
		else if(data == 'CH'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Switzerland.png'>");
		}
		else if(data == 'CR'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Costa Rica.png'>");
		}
		else if(data == 'DE'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Germany.png'>");
		}
		else if(data == 'DK'){
		$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Denmark.png'>");
		}
		else if(data == 'EE'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Estonia.png'>");
		}
		else if(data == 'ES'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Spain.png'>");
		}
		else if(data == 'FI'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Finland.png'>");
		}
		else if(data == 'GB'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Great Britain.png'>");
		}
		else if(data == 'HK'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Hong Kong.png'>");
		}
		else if(data == 'HU'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Hungary.png'>");
		}
		else if(data == 'IE'){
		$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Ireland.png'>");
		}
		else if(data == 'IL'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Israel.png'>");
		}
		else if(data == 'LT'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Lithuania.png'>");
		}
		else if(data == 'MX'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Mexico.png'>");
		}
		else if(data == 'MY'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Malaysia.png'>");
		}
		else if(data == 'NL'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Netherlands.png'>");
		}
		else if(data == 'NO'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Norway.png'>");
		}
		else if(data == 'PK'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Pakistan.png'>");
		}
		else if(data == 'PL'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Poland.png'>");
		}
		else if(data == 'RO'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Romania.png'>");
		}
		else if(data == 'RU'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Russia.png'>");
		}
		else if(data == 'SE'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Sweden.png'>");
		}
		else if(data == 'SK'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/Slovakia.png'>");
		}
		else if(data == 'US'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/United States.png'>");
		}
		else if(data == 'ZA'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/South Africa.png'>");
		}
		else if(data == 'FR'){
			$("#image").html("<img style='width:45px' src='<?php echo SITE_URL; ?>/flags/France.png'>");
		}
	}
	
 </script>
	<div class="portlet box blue-dark" >
		<div class="portlet-title">
			<div class="caption">
				<!--<i class="fa fa-users"></i>-->
				Phone Number Search
			</div>
		</div>
		<div class="portlet-body">
<?php $active=$this->Session->read('User.active');
$usercountry = $this->Session->read('User.user_country');
?>
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
					<label>Country</label>			
					<div id='image' style='display:inline-block;float: left;' ></div>
					<select  class="form-control input-large" id="PlivoCountry"  onchange="change(this.value)">
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

<!--*****************************-->
					   
					</select>
				</div> 
				<div class="form-group">
					<label>Select Features</label>
					<select  class="form-control input-large" name="data[Plivo][services]" id="services">
						<option value='sms'>SMS</option>
						<option value='voice'>Voice</option>
						<option value='voice,sms'>SMS & Voice</option>
					</select>
				</div>
				<div class="form-group">
					<?php echo $this->Form->button('Search',array('div'=>false,'class'=>'btn blue ','onClick'=>'selectcountry()'));?>
				</div>
				<?php echo $this->Form->end(); ?>		
			</div>
		</div>
<?php } ?>	
	</div>