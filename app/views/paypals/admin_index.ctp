<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Paypal' => '/admin/paypals/index',
						'Edit Paypal' => '/admin/paypals/edit/1'
					   					   
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
table tr.altrow td {
    text-align: center!important;
}
</style>
<div class="configs index">
	<h2><?php __('PayPal Config');?></h2>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('paypal_api_username');?></th>
			<th><?php echo $this->Paginator->sort('paypal_api_password');?></th>
			<th><?php echo $this->Paginator->sort('paypal_api_signature');?></th>
						 
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($paypals as $paypal):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $paypal['Paypal']['id']; ?>&nbsp;</td>
		<td><?php echo $paypal['Paypal']['paypal_api_username']; ?>&nbsp;</td>
		<td><?php echo $paypal['Paypal']['paypal_api_password']; ?>&nbsp;</td>
		<td><?php echo $paypal['Paypal']['paypal_api_signature']; ?>&nbsp;</td>
				 
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $paypal['Paypal']['id'])); ?>
			
			
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
</div>