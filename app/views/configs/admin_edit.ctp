<script>
$(document).ready(function (){
	$('#ConfigApiType1').click(function (){
		$('#twilio').hide();
		$('#nexmo').show();
		$('#plivo').hide();
    });
	$('#ConfigApiType2').click(function (){
		$('#twilio').hide();
		$('#nexmo').hide();
		$('#plivo').hide();
    });
	$('#ConfigApiType0').click(function (){
		$('#twilio').show();
		$('#nexmo').hide();
		$('#plivo').hide();
	});
	$('#ConfigApiType3').click(function (){
		$('#plivo').show();
		$('#nexmo').hide();
		$('#twilio').hide();
	});

	
    var ConfigApiType1=<?php echo $api_type;?>;
    if(ConfigApiType1==0){
	    $('#twilio').show();
		$('#nexmo').hide();
		$('#plivo').hide();
    }else if(ConfigApiType1==2){
		$('#twilio').hide();
		$('#nexmo').hide();
		$('#plivo').hide();
    }else if(ConfigApiType1==3){
		$('#twilio').hide();
		$('#nexmo').hide();
		$('#plivo').show();
    }else{
		$('#twilio').hide();
		$('#nexmo').show();
		$('#plivo').hide();
    }
});
   </script>
<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Config' => '/admin/configs/index',
						'Edit Config' => '/admin/configs/edit/1'
					   					   
				);				
				$matchingLinks = array();
				
				foreach ($navigation as $link) {
						if (preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base)))) {
								$matchingLinks[strlen($link)] = $link;
						}
				}
				
				krsort($matchingLinks);
				
				$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
				$out = array();
				
				foreach ($navigation as $title => $link) {
						$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
				}
				
				echo join("\n", $out);
				?>			
</ul>
<div class="configs form">
<?php echo $this->Form->create('Config',array('enctype'=>'multipart/form-data'));?>
	<fieldset>
		<legend><?php __('Edit Config'); ?></legend>
			<!--<h3 style="margin-top: -40px">Server Time <?php echo date("Y-m-d h:i:s A",$_SERVER['REQUEST_TIME']);?></h3><br/>
			<p><font color=red><b>NOTE:</b></font> Select your current timezone at the bottom to display the correct date and time. The server date/time above will display the correct date/time based on your timezone that you select.</p><br/>-->
			<?php
				$Option7=array('Afghanistan'=>'Afghanistan',
				'Albania'=>'Albania',
				'Algeria'=>'Algeria',
				'American Samoa'=>'American Samoa',
				'Andorra'=>'Andorra',
				'Angola'=>'Angola',
				'Anguilla'=>'Anguilla',
				'Antarctica'=>'Antarctica',
				'Antigua and Barbuda'=>'Antigua and Barbuda',
				'Argentina'=>'Argentina',
				'Armenia'=>'Armenia',
				'Aruba'=>'Aruba',
				'Australia'=>'Australia',
				'Austria'=>'Austria',
				'Azerbaijan'=>'Azerbaijan',
				'Bahamas'=>'Bahamas',
				'Bahrain'=>'Bahrain',
				'Bangladesh'=>'Bangladesh',
				'Barbados'=>'Barbados',
				'Belarus'=>'Belarus',
				'Belgium'=>'Belgium',
				'Belize'=>'Belize',
				'Benin'=>'Benin',
				'Bermuda'=>'Bermuda',
				'Bhutan'=>'Bhutan',
				'Bolivia'=>'Bolivia',
				'Bosnia and Herzegovina'=>'Bosnia and Herzegovina',
				'Botswana'=>'Botswana',
				'Bouvet Island'=>'Bouvet Island',
				'Brazil'=>'Brazil',
				'British Indian Ocean Territory'=>'British Indian Ocean Territory',
				'Brunei Darussalam'=>'Brunei Darussalam',
				'Bulgaria'=>'Bulgaria',
				'Burkina Faso'=>'Burkina Faso',
				'Burundi'=>'Burundi',
				'Cambodia'=>'Cambodia',
				'Cameroon'=>'Cameroon',
				'Canada'=>'Canada',
				'Cape Verde'=>'Cape Verde',
				'Cayman Islands'=>'Cayman Islands',
				'Central African Republic'=>'Central African Republic',
				'Chad'=>'Chad',
				'Chile'=>'Chile',
				'China'=>'China',
				'Christmas Island'=>'Christmas Island',
				'Cocos Keeling Islands'=>'Cocos Keeling Islands',
				'Colombia'=>'Colombia',
				'Comoros'=>'Comoros',
				'Congo'=>'Congo',
				'Congo The Democratic Republic of The'=>'Congo The Democratic Republic of The',
				'Cook Islands'=>'Cook Islands',
				'Costa Rica'=>'Costa Rica',
				'Croatia'=>'Croatia',
				'Cuba'=>'Cuba',
				'Cyprus'=>'Cyprus',
				'Czech Republic'=>'Czech Republic',
				'Denmark'=>'Denmark',
				'Djibouti'=>'Djibouti',
				'Dominica'=>'Dominica',
				'Dominican Republic'=>'Dominican Republic',
				'Ecuador'=>'Ecuador',
				'Egypt'=>'Egypt',
				'El Salvador'=>'El Salvador',
				'Equatorial Guinea'=>'Equatorial Guinea',
				'Eritrea'=>'Eritrea',
				'Estonia'=>'Estonia',
				'Ethiopia'=>'Ethiopia',
				'Falkland Islands Malvinas'=>'Falkland Islands Malvinas',
				'Faroe Islands'=>'Faroe Islands',
				'Fiji'=>'Fiji',
				'Finland'=>'Finland',
				'France'=>'France',
				'French Guiana'=>'French Guiana',
				'French Polynesia'=>'French Polynesia',
				'French Southern Territories'=>'French Southern Territories',
				'Gabon'=>'Gabon',
				'Gambia'=>'Gambia',
				'Georgia'=>'Georgia',
				'Germany'=>'Germany',
				'Ghana'=>'Ghana',
				'Gibraltar'=>'Gibraltar',
				'Greece'=>'Greece',
				'Greenland'=>'Greenland',
				'Grenada'=>'Grenada',
				'Guadeloupe'=>'Guadeloupe',
				'Guam'=>'Guam',
				'Guatemala'=>'Guatemala',
				'Guernsey'=>'Guernsey',
				'Guinea'=>'Guinea',
				'Guinea-bissau'=>'Guinea-bissau',
				'Guyana'=>'Guyana',
				'Haiti'=>'Haiti',
				'Heard Island and Mcdonald Islands'=>'Heard Island and Mcdonald Islands',
				'Honduras'=>'Honduras',
				'Hong Kong'=>'Hong Kong',
				'Hungary'=>'Hungary',
				'Iceland'=>'Iceland',
				'India'=>'India',
				'Indonesia'=>'Indonesia',
				'Iran Islamic Republic of'=>'Iran Islamic Republic of',
				'Iraq'=>'Iraq',
				'Ireland'=>'Ireland',
				'Isle of Man'=>'Isle of Man',
				'Israel'=>'Israel',
				'Italy'=>'Italy',
				'Jamaica'=>'Jamaica',
				'Japan'=>'Japan',
				'Jersey'=>'Jersey',
				'Jordan'=>'Jordan',
				'Kazakhstan'=>'Kazakhstan',
				'Kenya'=>'Kenya',
				'Kiribati'=>'Kiribati',
				'Korea Republic of'=>'Korea Republic of',
				'Kuwait'=>'Kuwait',
				'Kyrgyzstan'=>'Kyrgyzstan',
				'Latvia'=>'Latvia',
				'Lebanon'=>'Lebanon',
				'Lesotho'=>'Lesotho',
				'Liberia'=>'Liberia',
				'Libyan Arab Jamahiriya'=>'Libyan Arab Jamahiriya',
				'Liechtenstein'=>'Liechtenstein',
				'Lithuania'=>'Lithuania',
				'Luxembourg'=>'Luxembourg',
				'Macao'=>'Macao',
				'Macedonia The Former Yugoslav Republic of'=>'Macedonia The Former Yugoslav Republic of',
				'Madagascar'=>'Madagascar',
				'Malawi'=>'Malawi',
				'Malaysia'=>'Malaysia',
				'Maldives'=>'Maldives',
				'Mali'=>'Mali',
				'Malta'=>'Malta',
				'Marshall Islands'=>'Marshall Islands',
				'Martinique'=>'Martinique',
				'Mauritania'=>'Mauritania',
				'Mauritius'=>'Mauritius',
				'Mayotte'=>'Mayotte',
				'Mexico'=>'Mexico',
				'Micronesi Federated States of'=>'Micronesia Federated States of',
				'Moldova Republic of'=>'Moldova Republic of',
				'Monaco'=>'Monaco',
				'Mongolia'=>'Mongolia',
				'Montenegro'=>'Montenegro',
				'Montserrat'=>'Montserrat',
				'Morocco'=>'Morocco',
				'Mozambique'=>'Mozambique',
				'Myanmar'=>'Myanmar',
				'Namibia'=>'Namibia',
				'Nauru'=>'Nauru',
				'Nepal'=>'Nepal',
				'Netherlands'=>'Netherlands',
				'Netherlands Antilles'=>'Netherlands Antilles',
				'New Caledonia'=>'New Caledonia',
				'New Zealand'=>'New Zealand',
				'Nicaragua'=>'Nicaragua',
				'Niger'=>'Niger',
				'Nigeria'=>'Nigeria',
				'Niue'=>'Niue',
				'Norfolk Island'=>'Norfolk Island',
				'Northern Mariana Islands'=>'Northern Mariana Islands',
				'Norway'=>'Norway',
				'Oman'=>'Oman',
				'Pakistan'=>'Pakistan',
				'Palau'=>'Palau',
				'Palestinian Territory Occupied'=>'Palestinian Territory Occupied',
				'Panama'=>'Panama',
				'Papua New Guinea'=>'Papua New Guinea',
				'Paraguay'=>'Paraguay',
				'Peru'=>'Peru',
				'Philippines'=>'Philippines',
				'Pitcairn'=>'Pitcairn',
				'Poland'=>'Poland',
				'Portugal'=>'Portugal',
				'Puerto Rico'=>'Puerto Rico',
				'Qatar'=>'Qatar',
				'Reunion'=>'Reunion',
				'Romania'=>'Romania',
				'Russian Federation'=>'Russian Federation',
				'Rwanda'=>'Rwanda',
				'Saint Helena'=>'Saint Helena',
				'Saint Kitts and Nevis'=>'Saint Kitts and Nevis',
				'Saint Lucia'=>'Saint Lucia',
				'Saint Pierre and Miquelon'=>'Saint Pierre and Miquelon',
				'Saint Vincent and The Grenadines'=>'Saint Vincent and The Grenadines',
				'Samoa'=>'Samoa',
				'San Marino'=>'San Marino',
				'Sao Tome and Principe'=>'Sao Tome and Principe',
				'Saudi Arabia'=>'Saudi Arabia',
				'Senegal'=>'Senegal',
				'Serbia'=>'Serbia',
				'Seychelles'=>'Seychelles',
				'Sierra Leone'=>'Sierra Leone',
				'Singapore'=>'Singapore',
				'Slovakia'=>'Slovakia',
				'Slovenia'=>'Slovenia',
				'Solomon Islands'=>'Solomon Islands',
				'Somalia'=>'Somalia',
				'South Africa'=>'South Africa',
				'South Georgia and The South Sandwich Islands'=>'South Georgia and The South Sandwich Islands',
				'Spain'=>'Spain',
				'Sri Lanka'=>'Sri Lanka',
				'Sudan'=>'Sudan',
				'Suriname'=>'Suriname',
				'Svalbard and Jan Mayen'=>'Svalbard and Jan Mayen',
				'Swaziland'=>'Swaziland',
				'Sweden'=>'Sweden',
				'Switzerland'=>'Switzerland',
				'Syrian Arab Republic'=>'Syrian Arab Republic',
				'Taiwan, Province of China'=>'Taiwan, Province of China',
				'Tajikistan'=>'Tajikistan',
				'Tanzania, United Republic of'=>'Tanzania, United Republic of',
				'Thailand'=>'Thailand',
				'Timor-leste'=>'Timor-leste',
				'Togo'=>'Togo',
				'Tokelau'=>'Tokelau',
				'Tonga'=>'Tonga',
				'Trinidad and Tobago'=>'Trinidad and Tobago',
				'Tunisia'=>'Tunisia',
				'Turkey'=>'Turkey',
				'Turkmenistan'=>'Turkmenistan',
				'Turks and Caicos Islands'=>'Turks and Caicos Islands',
				'Tuvalu'=>'Tuvalu',
				'Uganda'=>'Uganda',
				'Ukraine'=>'Ukraine',
				'United Arab Emirates'=>'United Arab Emirates',
				'United Kingdom'=>'United Kingdom',
				'United States'=>'United States',
				'United States Minor Outlying Islands'=>'United States Minor Outlying Islands',
				'Uruguay'=>'Uruguay',
				'Uzbekistan'=>'Uzbekistan',
				'Vanuatu'=>'Vanuatu',
				'Venezuela'=>'Venezuela',
				'Viet Nam'=>'Viet Nam',
				'Virgin Islands - British'=>'Virgin Islands - British',
				'Virgin Islands - U.S.'=>'Virgin Islands - U.S.',
				'Wallis and Futuna'=>'Wallis and Futuna',
				'Western Sahara'=>'Western Sahara',
				'Yemen'=>'Yemen',
				'Zambia'=>'Zambia',
				'Zimbabwe'=>'Zimbabwe');

		$Option=array('1'=>'PayPal','2'=>'Stripe','3'=>'Both');
		$Option4=array('0'=>'On','1'=>'Off');
		$Option1=array('1'=>'On','2'=>'Off');
		$Option5=array('0'=>'On','1'=>'Off');
		$Optioncharge=array('1'=>'On','0'=>'Off');
		$Option2=array('AUD'=>'Australian Dollar','BRL'=>'Brazilian Real','GBP'=>'British Pound','CAD'=>'Canadian Dollar','DKK'=>'Danish Krone','EUR'=>'Euro','HKD'=>'Hong Kong Dollar','ILS'=>'Israeli New Shekel','JPY'=>'Japanese Yen','MXN'=>'Mexican Peso','NZD'=>'New Zealand Dollar','NOK'=>'Norwegian Krone','PHP'=>'Philippine Peso','SGD'=>'Singapore Dollar','SEK'=>'Swedish Krona','CHF'=>'Swiss Franc','USD'=>'United States Dollar');
	
		$Option3=array('0'=>'Twilio','1'=>'Nexmo');
        $Option6=array('l1grey'=>'Layout 1 Grey','l1default'=>'Layout 1 Default','l1blue'=>'Layout 1 Blue','l1dark'=>'Layout 1 Dark','l1light'=>'Layout 1 Light','l2grey'=>'Layout 2 Grey','l2default'=>'Layout 2 Default','l2blue'=>'Layout 2 Blue','l2dark'=>'Layout 2 Dark','l2light'=>'Layout 2 Light');
        //$Option6=array('grey'=>'Grey','grey2'=>'Grey2','brown'=>'Brown','blue'=>'Blue','darkblue'=>'Dark Blue','light'=>'White');
	
		echo $this->Form->input('id');
		echo $this->Form->input('registration_charge');
		echo $this->Form->input('free_sms');
		echo $this->Form->input('free_voice');
		echo $this->Form->input('paypal_email');
		echo $this->Form->input('site_url');
		echo $this->Form->input('sitename');
		echo $this->Form->input('support_email');

		//echo $this->Form->input('2CO_account_ID');
		//echo $this->Form->input('2CO_account_activation_prod_ID');
		echo $this->Form->input('referral_amount');
		echo $this->Form->input('recurring_referral_percent');
		
        echo $this->Form->input('mobile_page_limit');
		//echo $this->Form->input('profilestartdate');
		 echo $form->input('charge_for_additional_numbers',array('type'=>'select','options'=>$Optioncharge));
		 echo $form->input('payment_gateway',array('type'=>'select','options'=>$Option));
		  echo $form->input('pay_activation_fees',array('type'=>'select','options'=>$Option1));
  		  echo $form->input('payment_currency_code',array('type'=>'select','options'=>$Option2));
                echo $this->Form->input('country',array('type' =>'select','div'=>false,'label'=>'<strong>Country</strong>', 'options'=>$Option7));
echo '<br/><br/>';
  		  echo $form->input('AllowTollFreeNumbers',array('type'=>'select','options'=>$Option4));
  		  echo $form->input('FBTwitterSharing',array('type'=>'select','options'=>$Option5));
		  
		  if($apicount==0){ 
		  
		    $Option3=array('0'=>'Twilio','1'=>'Nexmo','2'=>'Slooce','3'=>'Plivo');
			$attributes=array('style'=>'width: 19px;margin-bottom: 2px;');
			echo $this->Form->radio('api_type',$Option3,$attributes); 

		  }else{ ?>
		  
		  <div style="font-weight:bold;color:red;">You can’t change the SMS API unless you release all the users numbers from the system. Once the new SMS API is chosen, each user would then have to purchase a new number.</div>
		  
		  <?php } ?>
		  <br>
		  <div id="twilio" style = "display:none;">
		  
		  	 <?php 

			   echo $this->Form->input('twilio_accountSid');
		       echo $this->Form->input('twilio_auth_token');

			   ?>
		  
		  </div>
		   
		   <div id="nexmo" style = "display:none;">
           <?php
		   
			   echo $this->Form->input('nexmo_key');
		       echo $this->Form->input('nexmo_secret');
			   
			   ?>
		  </div>
		  <div id="plivo" style = "display:none;">
           <?php
		   
			   echo $this->Form->input('plivo_key');
		       echo $this->Form->input('plivo_token');
			   
			   ?>
		  </div>
		  <?php 
			echo $this->Form->input('facebook_appid');
			echo $this->Form->input('facebook_appsecret');
			echo $this->Form->input('birthday_msg');
			echo $this->Form->input('name_capture_msg');
			echo $this->Form->input('email_capture_msg');
			echo $this->Form->input('bitly_username');
			echo $this->Form->input('bitly_api_key');
			echo $this->Form->input('bitly_access_token');
			echo $this->Form->input('logout_url');
			echo $this->Form->input('theme_color',array('type'=>'select','options'=>$Option6));
			echo $this->Form->input('upload_logo',array('type'=>'file'));
		  ?>
		 <p> Please upload logo. Recommended logo size is 300 * 60 pixels<p>
		 <?php if($this->data['Config']['logo'] !=''){ ?>
		 <img src="<?php echo SITE_URL?>/img/<?php echo $this->data['Config']['logo'];?>"/>
		 <?php } ?>
		   <!--<?php 
		//	echo $this->Form->input('twitter_consumer_key');
			//echo $this->Form->input('twitter_consumer_secret');
		  ?>
	-->	
		
		
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>