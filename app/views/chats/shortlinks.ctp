<script>
function insert(id){
	window.parent.jQuery.nmTop().close();
	window.parent.checkshortlinks(id);
}
</script>
<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-link"></i>Short Links
		</div>				
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('Name','shortname');?></th>
						<th><?php echo $this->Paginator->sort('URL','url');?></th>
						<th><?php echo $this->Paginator->sort('Short URL','short_url');?></th>
						<th><?php echo $this->Paginator->sort('clicks');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
				</thead>
					<?php
						$i = 0;
						foreach ($shortlink as $shortlinks):
						$class = null;
						if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
						}
					?>
					<tr <?php echo $class;?>>
						<td><?php echo $shortlinks['Shortlink']['shortname']; ?> &nbsp;</td>
						<td><?php echo $shortlinks['Shortlink']['url']; ?> &nbsp;</td>
						<td><a href="<?php echo $shortlinks['Shortlink']['short_url']; ?>" target="_blank" style="color:#005580; text-decoration: underline"><?php echo $shortlinks['Shortlink']['short_url']; ?></a> &nbsp;</td>
						<td><?php echo $shortlinks['Shortlink']['clicks']; ?> &nbsp;</td>
						<td class="actions">
							<a href="#null" class="btn blue btn-outline btn-sm" onclick="insert(<?php echo $shortlinks['Shortlink']['id']; ?>)">Insert</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>