<ul  class="secondary_nav" style="width:5100px">
				<?php
				$navigation = array(
					  	'List Config' => '/admin/configs/index',
						'Edit Config' => '/admin/configs/edit/1',
						'Stripe Config' => '/admin/paypals/stripeindex',
						'Database Config'=>'/admin/paypals/dbconfig/'
					   					   
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
<style>

h3 {
    color: #C6C65B;
    font-family: 'Gill Sans','lucida grande',helvetica,arial,sans-serif;
    font-size: 150%;
    padding-top: 0px;
}
</style>
<div class="configs index">
	<h2><?php __('Configs');?></h2>
	
	<table cellpadding="0" cellspacing="0" style="width:6000px">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('registration_charge');?></th>
			<th><?php echo $this->Paginator->sort('free_sms');?></th>
			<th><?php echo $this->Paginator->sort('free_voice');?></th>
			<th><?php echo $this->Paginator->sort('paypal_email');?></th>
			<th><?php echo $this->Paginator->sort('site_url');?></th>
			<th><?php echo $this->Paginator->sort('sitename');?></th>
			<th><?php echo $this->Paginator->sort('support_email');?></th>
			<th><?php echo $this->Paginator->sort('api_type');?></th>
			<?php if($configdetails['Config']['api_type']==0){ ?>
				<th><?php echo $this->Paginator->sort('twilio_accountSid');?></th>
				<th><?php echo $this->Paginator->sort('twilio_auth_token');?></th>
			<?php }else if($configdetails['Config']['api_type']==1){ ?>
				<th><?php echo $this->Paginator->sort('nexmo_key');?></th>
				<th><?php echo $this->Paginator->sort('nexmo_secret');?></th>
			<?php }else if($configdetails['Config']['api_type']==3){ ?>
				<th><?php echo $this->Paginator->sort('plivo_key');?></th>
				<th><?php echo $this->Paginator->sort('plivo_token');?></th>
                                <!--<th><?php echo $this->Paginator->sort('plivoapp_id');?></th>-->
			
			<?php } ?>
			
			<!--<th><?php echo $this->Paginator->sort('2CO_account_ID');?></th>
			<th><?php echo $this->Paginator->sort('2CO_account_activation_prod_ID');?></th>-->
			
			<th><?php echo $this->Paginator->sort('referral_amount');?></th>
			<th><?php echo $this->Paginator->sort('recurring_referral_percent');?></th>
			<!--<th><?php echo $this->Paginator->sort('profilestartdate');?></th>-->
			<th><?php echo $this->Paginator->sort('payment_gateway');?></th>
			<th><?php echo $this->Paginator->sort('pay_activation_fees');?></th>
			<th><?php echo $this->Paginator->sort('mobile_page_limit');?></th>
			<th><?php echo $this->Paginator->sort('payment_currency_code');?></th>
                        <th><?php echo $this->Paginator->sort('country');?></th>
			
			<th>Charge For Additional(Secondary) Numbers</th>
			<th><?php echo $this->Paginator->sort('AllowTollFreeNumbers');?></th>
			<th><?php echo $this->Paginator->sort('FBTwitterSharing');?></th>
			<th><?php echo $this->Paginator->sort('facebook_appid');?></th>
			<th><?php echo $this->Paginator->sort('facebook_appsecret');?></th>
			<th><?php echo $this->Paginator->sort('bitly_username');?></th>
			<th><?php echo $this->Paginator->sort('bitly_api_key');?></th>
			<th><?php echo $this->Paginator->sort('bitly_access_token');?></th>
                        <th><?php echo $this->Paginator->sort('logout_url');?></th>
                        <th><?php echo $this->Paginator->sort('theme_color');?></th>
			<!--<th><?php echo $this->Paginator->sort('twitter_consumer_key');?></th>
			<th><?php echo $this->Paginator->sort('twitter_consumer_secret');?></th>
-->			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($configs as $config):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $config['Config']['id']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['registration_charge']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['free_sms']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['free_voice']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['paypal_email']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['site_url']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['sitename']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['support_email']; ?>&nbsp;</td>
			  <td><?php 
		
		if($config['Config']['api_type']==0){
			echo "Twilio";
		}else if($config['Config']['api_type']==1){
			echo "Nexmo";
		}else if($config['Config']['api_type']==2){
			echo "Slooce";
		}else if($config['Config']['api_type']==3){
			echo "Plivo";
		}
		
		 ?>&nbsp;</td>
		
		<?php if($config['Config']['api_type']==0){ ?>
		
		<td><?php echo $config['Config']['twilio_accountSid']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['twilio_auth_token']; ?>&nbsp;</td>
		
		<?php }else if($config['Config']['api_type']==1){ ?>
		
		<td><?php echo $config['Config']['nexmo_key']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['nexmo_secret']; ?>&nbsp;</td>
		
		<?php }else if($config['Config']['api_type']==3){ ?>
		
		<td><?php echo $config['Config']['plivo_key']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['plivo_token']; ?>&nbsp;</td>
                <!--<td><?php echo $config['Config']['plivoapp_id']; ?>&nbsp;</td>-->
		
	    <?php } ?>
		
		<!--<td><?php echo $config['Config']['2CO_account_ID']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['2CO_account_activation_prod_ID']; ?>&nbsp;</td>-->
		
		<td><?php echo $config['Config']['referral_amount']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['recurring_referral_percent'].'%'; ?>&nbsp;</td>
		<!--<td><?php echo $config['Config']['profilestartdate']; ?>&nbsp;</td>-->
		<td><?php echo $config['Config']['payment_gateway']; ?>&nbsp;</td>
		
		<td><?php 
		
		if($config['Config']['pay_activation_fees']==1){
		echo "On";
		
		}else if($config['Config']['pay_activation_fees']==2){
		
		echo "Off";
		}
		
		//echo $config['Config']['pay_activation_fees']; ?>&nbsp;</td>
		
		
		
		<td><?php echo $config['Config']['mobile_page_limit']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['payment_currency_code']; ?>&nbsp;</td>
                <td><?php echo $config['Config']['country']; ?>&nbsp;</td>

		 
		 <td><?php 
		
		if($config['Config']['charge_for_additional_numbers']==1){
		echo "On";
		
		}else if($config['Config']['charge_for_additional_numbers']==0){
		
		echo "Off";
		}
		?>&nbsp;</td>
		<td><?php 
		
		if($config['Config']['AllowTollFreeNumbers']==0){
		echo "On";
		
		}else if($config['Config']['AllowTollFreeNumbers']==1){
		
		echo "Off";
		}
		
		 ?>&nbsp;</td>
				 <td><?php 
		
		if($config['Config']['FBTwitterSharing']==0){
		echo "On";
		
		}else if($config['Config']['FBTwitterSharing']==1){
		
		echo "Off";
		
		}
		
		//echo $config['Config']['pay_activation_fees']; ?>&nbsp;</td>
		
		<td><?php echo $config['Config']['facebook_appid']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['facebook_appsecret']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['bitly_username']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['bitly_api_key']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['bitly_access_token']; ?>&nbsp;</td>
                <td><?php echo $config['Config']['logout_url']; ?>&nbsp;</td>
                <td><?php echo $config['Config']['theme_color']; ?>&nbsp;</td>
		<!--<td><?php echo $config['Config']['twitter_consumer_key']; ?>&nbsp;</td>
		<td><?php echo $config['Config']['twitter_consumer_secret']; ?>&nbsp;</td>
-->		
		
		
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $config['Config']['id'])); ?>
			
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
</div>