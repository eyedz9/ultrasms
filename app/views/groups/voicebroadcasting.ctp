<style>

/*.loginbox .inputbutton {
    background: none repeat scroll 0 0 #2E6F7B;
    border: medium none;
    border-radius: 4px 4px 4px 4px;
    color: #FFFFFF;
  float: none;
    font-size: 12px;
    font-weight: bold;
    padding: 5px 15px;
    text-transform: uppercase;
}*/
.ValidationErrors{

color:red;

}
</style>

<div>
<div class="clearfix"></div>
<div class="portlet box blue-dark">
<div class="portlet-title">
	<div class="caption">
		Send Broadcast
	</div>
</div>



<div class="portlet-body">
<!--h1>Send Broadcast</h1-->



<div class="loginbox">
	<div class="loginner">

<?php if($users['User']['active']==0){?>

	<h3>
          Oops! You need to activate your account to use this feature.</h3>
         <br>
         <?php 
         $payment=PAYMENT_GATEWAY;
         if($payment=='1' && PAY_ACTIVATION_FEES=='1'){
         ?>
           Activate account with PayPal by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />
         <?php }else if($payment=='2' && PAY_ACTIVATION_FEES=='1'){?>
           Activate account with Credit Card by <?php echo $this->Html->link('Clicking Here', array('controller' =>'users', 'action' =>'activation/'.$payment))?>.<br />

          <?php }else if($payment=='3' && PAY_ACTIVATION_FEES=='1'){ ?>
           Activate account with <b><?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'activation/1'))?></b> or <b><?php echo $this->Html->link('Credit Card',  array('controller' =>'users', 'action' =>'activation/2'))?></b><br />
         <?php } 
}else {


	if((!empty($users)) || (!empty($usernumber))){ ?>
		<?php echo $this->Form->create('Group',array('action'=> 'voicebroadcasting','enctype'=>'multipart/form-data'));?>

  <input type="hidden" value="<?php echo $group_id?>" id="groupid" name="data[Group][id]">
  
 
		<div class="feildbox form-group">
		<label>Caller ID(Send from)</label>
	
	<select id="number" class="form-control txt" name="data[Group][number]" >
		<?php if($users['User']['assigned_number']!=''){ ?>
	<option value="<?php echo $users['User']['assigned_number']; ?>"><?php echo $users['User']['assigned_number']; ?></option>
	
	<?php } if(!empty($usernumber)){?>
	
		  <?php foreach($usernumber as $values){   ?>
		
				 
		<option value="<?php echo $values; ?>" ><?php echo $values; ?></option>
		
      
		<?php  } }?>
       
			
       </select>
	   
	   
				

	   </div> 	
			
			
			
		<div class="feildbox" style="margin-top: 10px">
		<?php if (API_TYPE==0){
			$Option3=array('da-DK'=>'Danish, Denmark',
				'de-DE'=>'German, Germany',
				'en-AU'=>'English, Australia',
				'en-CA'=>'English, Canada',
				'en-GB'=>'English, UK',
				'en-IN'=>'English, India',
				'en-US'=>'English, United States',
				'ca-ES'=>'Catalan, Spain',
				'es-ES'=>'Spanish, Spain',
				'es-MX'=>'Spanish, Mexico',
				'fi-FI'=>'Finnish, Finland',
				'fr-CA'=>'French, Canada',
				'fr-FR'=>'French, France',
				'it-IT'=>'Italian, Italy',
				'ja-JP'=>'Japanese, Japan',
				'ko-KR'=>'Korean, Korea',
				'nb-NO'=>'Norwegian, Norway',
				'nl-NL'=>'Dutch, Netherlands',
				'pl-PL'=>'Polish-Poland',
				'pt-BR'=>'Portuguese, Brazil',
				'pt-PT'=>'Portuguese, Portugal',
				'ru-RU'=>'Russian, Russia',
				'sv-SE'=>'Swedish, Sweden',
				'zh-CN'=>'Chinese (Mandarin)',
				'zh-HK'=>'Chinese (Cantonese)',
				'zh-TW'=>'Chinese (Taiwanese Mandarin)'
			);
		}else if (API_TYPE==3){
			$Option3=array(
				'da-DK'=>'Danish',
				'nl-NL'=>'Dutch',
				'en-AU'=>'English - Australian',
				'en-GB'=>'English - British',
				'en-US'=>'English - USA',
				'fr-FR'=>'French',
				'fr-CA'=>'French - Canadian',
				'de-DE'=>'German',
				'it-IT'=>'Italian',
				'pl-PL'=>'Polish',
				'pt-PT'=>'Portuguese',
				'pt-BR'=>'Portuguese - Brazilian',
				'ru-RU'=>'Russian',
				'es-ES'=>'Spanish',
				'es-US'=>'Spanish - USA',
				'sv-SE'=>'Swedish'
			);
		}else{
			$Option3=array('de-de'=>'German, Germany',
			'en-au'=>'English, Australia',
			'en-gb'=>'English, UK',
			'en-in'=>'English, India',
			'en-us'=>'English, United States',
			'es-es'=>'Spanish, Spain',
			'es-mx'=>'Spanish, Mexico',
			'es-us'=>'Spanish, US',
			'fr-ca'=>'French, Canada',
			'fr-fr'=>'French, France',
			'it-it'=>'Italian, Italy',
			'is-is'=>'Icelandic, Iceland',
			'ja-jp'=>'Japanese, Japan',
			'ko-kr'=>'Korean, Korea',
			'nl-nl'=>'Dutch, Netherlands',
			'pl-pl'=>'Polish-Poland',
			'pt-br'=>'Portuguese, Brazil',
			'pt-pt'=>'Portuguese, Portugal',
			'ro-ro'=>'Romanian, Romania',
			'ru-ru'=>'Russian, Russia',
			'sv-se'=>'Swedish, Sweden',
			'tr-tr'=>'Turkish, Turkey',
			'zh-cn'=>'Chinese (Mandarin)');
		}
	?>
    			
				
			<div class="form-group">	
				<label>Text-to-Voice Language</label>
				<?php echo $this->Form->input('voice.language',array('type' =>'select','class' =>'form-control', 'div'=>false,'label'=>false, 'options'=>$Option3))?>
			</div>
		
		<div class="form-group">
		<label>Repeat Msg(# of times)</label>
		<?php for($i=1;$i<=5;$i++){
			$repeat[$i] = $i;
		}?>
		<?php echo $this->Form->input('voice.repeat',array('type' =>'select','class' =>'form-control', 'div'=>false,'label'=>false, 'options'=>$repeat))?>
		</div>
		
		<div class="form-group">
		<label>Pause(# of seconds before playing msg)</label>
		<?php echo $this->Form->input('voice.pause',array('type' =>'select','class' =>'form-control', 'div'=>false,'label'=>false, 'options'=>$repeat))?>
		</div>
		
			<?php echo $this->Form->submit('Send Voice Broadcast',array('div'=>false,'class'=>'inputbutton btn btn-primary'));?>
		
			
			</div>
		
		
		
			<?php echo $this->Form->end(); ?>
	<?php  }else{

		echo 'No numbers in your account exist with voice capability. You need to get a voice enabled number.';

	}
}
	   ?>
	</div>
</div>



</div>
</div>
</div>
				<style>
	legend{
		font-weight:bold!important;
	}
	fieldset label{
		display:inline!important;
	}
	</style>