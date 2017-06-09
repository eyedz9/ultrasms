<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Packages' => '/admin/packages/index',
						'Add Packages' => '/admin/packages/add',
						'List Monthly Packages' => '/admin/packages/monthlypackage',
						'Add Monthly Packages' => '/admin/packages/addmonthlypackage'
					   					   
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
<div class="packages form">
<?php echo $this->Form->create('Package');?>
	<fieldset>
		<legend><?php __('Edit Monthly Package'); ?></legend>
	<?php
	$Option=array('1'=>'Active','0'=>'Inactive');
$Option4=array('Afghanistan'=>'Afghanistan',
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
		echo $this->Form->input('MonthlyPackage.id');
		echo $this->Form->input('MonthlyPackage.product_id',array('type'=>'text','label'=>'<strong>Plan ID</strong>'));
		echo $this->Form->input('MonthlyPackage.package_name');
		
		echo $this->Form->input('MonthlyPackage.amount');
		echo $this->Form->input('MonthlyPackage.text_messages_credit');
		echo $this->Form->input('MonthlyPackage.voice_messages_credit');
                echo $this->Form->input('MonthlyPackage.user_country',array('type' =>'select','div'=>false,'label'=>'<strong>Country</strong>', 'options'=>$Option4));
                echo '<br/><br/>';		
		echo $form->input('MonthlyPackage.status',array('type'=>'select','options'=>$Option));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>