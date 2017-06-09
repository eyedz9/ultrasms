<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Paypal' => '/admin/paypals/index',
						'Edit Paypal' => '/admin/paypals/add'
					   					   
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
<?php echo $this->Form->create('Paypal');?>
	<fieldset>
		<legend><?php __('Add Paypal'); ?></legend>
	<?php
		echo $this->Form->input('paypal_api_username');
		echo $this->Form->input('password','paypal_api_password');
		echo $this->Form->input('paypal_api_signature');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
