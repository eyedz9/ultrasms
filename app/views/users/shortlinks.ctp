<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Short Links</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
								<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Short Links </span>
				</li>
			</ul>  
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li>
							<a href="<?php echo SITE_URL;?>/users/shortlinkadd" title="Add Short Link URL"><i class="fa fa-plus-square-o"></i> Add Short Link URL</a>
							<a href="<?php echo SITE_URL;?>/cronjobs/updateclicks" title="Refresh Clicks"><i class="fa fa-refresh"></i>
Refresh Clicks</a>
						</li>						
					</ul>
				</div>
			</div>				
		</div>
		<?php echo $this->Session->flash(); ?>				
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
						<!--<tbody>-->
							<tr <?php echo $class;?>>
								<td><?php echo $shortlinks['Shortlink']['shortname']; ?> &nbsp;</td>
								<td><?php echo $shortlinks['Shortlink']['url']; ?> &nbsp;</td>
								<td><a href="<?php echo $shortlinks['Shortlink']['short_url']; ?>" target="_blank" style="color:#005580; text-decoration: underline"><?php echo $shortlinks['Shortlink']['short_url']; ?></a> &nbsp;</td>
								<td><?php echo $shortlinks['Shortlink']['clicks']; ?> &nbsp;</td>
								<td class="actions">
									<a href="<?php echo SITE_URL;?>/messages/send_message?shortlink=<?php echo $shortlinks['Shortlink']['id']; ?>" class="btn blue btn-outline btn-sm">Send</a>
									<?php echo $this->Html->link(__('Delete', true), array('action' => 'shortlinkdelete', $shortlinks['Shortlink']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete?', true))); ?>
								</td>
							</tr>
						<!--</tbody>-->
						<?php endforeach; ?>
					</table>
					
				</div>
<div class="dataTables_paginate paging_bootstrap_number" id="sample_2_paginate">
						<ul class="pagination" style="visibility: visible;">
							<ul class="pagination">
							<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
							echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));?></li>
							<li >
							<?php //echo $this->Paginator->numbers(array('class' => 'paginate_button'));?>	
							</li>
							<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));?></li>
							</ul>
						</ul>
					</div>
			</div>
		</div>
	</div>
</div>