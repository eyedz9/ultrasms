<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List DbConfig' => '/admin/paypals/dbconfig',
						'Edit DbConfig' => '/admin/paypals/dbconfigedit/1'
					   					   
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
<input type='hidden' value='0'>
<div class="configs index">
	<h2><?php __('DB Config');?></h2>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			
			<th>DBName</th>
<th>DBUsername</th>
			<th>DBPassword</th>
						 
			<th class="actions"><?php __('Actions');?></th>
			</tr>
	<?php
	$i = 0;
	
	foreach ($dbdata as $paypal):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $paypal['Dbconfig']['id']; ?>&nbsp;</td>
		
		<td><?php echo $paypal['Dbconfig']['dbname']; ?>&nbsp;</td>
<td><?php echo $paypal['Dbconfig']['dbusername']; ?>&nbsp;</td>
		<td><?php echo $paypal['Dbconfig']['dbpassword']; ?>&nbsp;</td>
				 
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'dbconfigedit', $paypal['Dbconfig']['id'])); ?>
			
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
</div>