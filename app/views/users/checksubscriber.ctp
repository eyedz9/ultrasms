<div class="portlet box blue-dark">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-users"></i>
			Total Subscribers
		</div>
	</div>
	<div class="portlet-body">
		<table  class="table table-striped table-bordered table-hover">
			<tr>
				<th>Keyword</th>
				<th>Subscribers</th>
			</tr>
			<?php 
				$i=0;
				foreach($totalsubscribers as $totalsubscriber) {
				$class = null; 
				if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
				}
			?>
			<tr <?php echo $class;?>>
				<td><?php echo $totalsubscriber['ContactGroup']['group_subscribers'] ?></td>
				<td><?php echo $totalsubscriber[0]['total'] ?></td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>


