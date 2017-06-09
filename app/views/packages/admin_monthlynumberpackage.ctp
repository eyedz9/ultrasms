<ul  class="secondary_nav">
				<?php
				$navigation = array(
					  	'List Packages' => '/admin/packages/index',
						'Add Packages' => '/admin/packages/add',
						'List Monthly Packages' => '/admin/packages/monthlypackage',
						'Add Monthly Packages' => '/admin/packages/addmonthlypackage',
						'List Secondary Number Packages' => '/admin/packages/monthlynumberpackage',
						'Add Secondary Number Packages' => '/admin/packages/addmonthlynumberpackage'
					   					   
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
<div class="packages index">
	<h2><?php __('List Secondary Number Packages');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
                        <th><?php echo $this->Paginator->sort('Plan ID','plan_id');?></th>
			<th><?php echo $this->Paginator->sort('package_name');?></th>
			<th><?php echo $this->Paginator->sort('amount');?></th>
			<th><?php echo $this->Paginator->sort('# of Secondary Numbers','total_secondary_numbers');?></th>
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php echo $this->Paginator->sort('Country','country');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	
	
	foreach ($packagesdata as $package):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
                <td><?php echo $package['MonthlyNumberPackage']['plan']; ?>&nbsp;</td>
		<td><?php echo $package['MonthlyNumberPackage']['package_name']; ?>&nbsp;</td>
		<td><?php echo $package['MonthlyNumberPackage']['amount']; ?>&nbsp;</td>
		<td><?php echo $package['MonthlyNumberPackage']['total_secondary_numbers']; ?>&nbsp;</td>
		<td><?php 
		
		if($package['MonthlyNumberPackage']['status'] == 1){
		echo "Active";
		
		}else{
		echo "Inactive";
		
		}
		?>&nbsp;</td>
		<td><?php echo $package['MonthlyNumberPackage']['country']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'editmonthlynumberpackage', $package['MonthlyNumberPackage']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'monthlynumberdelete', $package['MonthlyNumberPackage']['id']), null, sprintf(__('Are you sure you want to delete?', true))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
