<script>
function insert(id){
	window.parent.jQuery.nmTop().close();
	window.parent.checkmsgtemplate(id);
}
</script>
<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-clone"></i><?php __('Message Templates');?> </div>
	</div>
	<div class="portlet-body">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th scope="col"> Name </th>
						<th scope="col"> Message </th>
						<th scope="col"> Created </th>
						<th scope="col"> Action  </th>
					</tr>
				</thead>
					<?php 
						$i = 0;
						foreach($Smstemplate as $Smstemplates) { 
						$class = null;

						if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
						}
					?>
					<tr <?php echo $class;?>> 
						<td class="tc"><?php echo $Smstemplates['Smstemplate']['messagename'] ?></td>
						<td class="tc"><?php echo $Smstemplates['Smstemplate']['message_template'] ?></td>
						<td class="tc"><?php echo $Smstemplates['Smstemplate']['created'] ?></td>
						<td class="actions" style="padding: 2px; width:150px;">
							<a href="#null" class="btn blue btn-outline btn-sm" onclick="insert(<?php echo $Smstemplates['Smstemplate']['id']; ?>)">Insert</a>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>