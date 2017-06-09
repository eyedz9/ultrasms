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
	<h2><?php __('Stripe Config');?></h2>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Id</th>
			<th>Secret Key</th>
			<th>Publishable Key</th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<tr>
		<td><?php echo $stripes['Stripe']['id']; ?>&nbsp;</td>
		<td><?php echo $stripes['Stripe']['secret_key']; ?>&nbsp;</td>
		<td><?php echo $stripes['Stripe']['publishable_key']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'stripeedit', $stripes['Stripe']['id'])); ?>
		</td>
	</tr>
	</table>
	
</div>