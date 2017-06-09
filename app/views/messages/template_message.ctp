		<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> <?php __('Message Templates');?></h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
										<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<span><?php __('Message Templates');?></span>
						</li>
					</ul>  
					<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
							<i class="fa fa-angle-down"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li>
<a href="<?php echo SITE_URL;?>/messages/smstemplate" title="Add Message Template"><i class="fa fa-plus-square-o"></i> Add Message Template</a>
									
								</li>
							</ul>
						</div>
					</div>				
				</div>
				<?php echo $this->Session->flash(); ?>				
				<div class="clearfix"></div>
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
								<!--<tbody>-->
									<tr <?php echo $class;?>> 
										<td class="tc"><?php echo $Smstemplates['Smstemplate']['messagename'] ?></td>
										<td class="tc"><?php echo $Smstemplates['Smstemplate']['message_template'] ?></td>
										<td class="tc"><?php echo $Smstemplates['Smstemplate']['created'] ?></td>
										<td class="actions" style="padding: 2px; width:150px;">
											<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit_smstemplate', $Smstemplates['Smstemplate']['id']), array('class' => 'btn blue btn-outline btn-sm')); ?>
											<?php echo $this->Html->link(__('Delete', true), array('action' => 'template_delete', $Smstemplates['Smstemplate']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete', true))); ?>
										</td>
									</tr>
									<?php } ?>
								<!--</tbody>-->
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>