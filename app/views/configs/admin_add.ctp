<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Config' => '/admin/configs/index',
						'Edit Config' => '/admin/configs/add'
					   					   
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
<?php echo $this->Form->create('Config');?>
	<fieldset>
		<legend><?php __('Add Config'); ?></legend>
	<?php
		echo $this->Form->input('registration_charge');
		echo $this->Form->input('free_sms');
		echo $this->Form->input('free_voice');
		echo $this->Form->input('paypal_email');
		echo $this->Form->input('twilio_accountSid');
		echo $this->Form->input('twilio_auth_token');
		echo $this->Form->input('referral_amount');
		echo $this->Form->input('twitter');
		echo $this->Form->input('facebook');
		echo $this->Form->input('skype');
		 
		  
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
