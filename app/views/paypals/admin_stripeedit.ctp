<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Stripe' => '/admin/paypals/stripeindex',
						'Edit Stripe' => '/admin/paypals/stripeedit/1'
					   					   
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
		<legend><?php __('Edit Stripe'); ?></legend>

<?php
	echo $this->Form->input('Stripe.id',array('value'=>$this->data['Stripe']['id'],'type'=>'hidden'));
         echo $this->Form->input('Stripe.secret_key',array('value'=>$this->data['Stripe']['secret_key']));
	echo $this->Form->input('Stripe.publishable_key',array('value'=>$this->data['Stripe']['publishable_key']))           
?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>