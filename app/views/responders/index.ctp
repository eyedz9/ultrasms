<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Auto Responders
			<small></small>
		</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Auto Responders</span>
					</li>
				</ul>  
					<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
								<i class="fa fa-angle-down"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li>
									<a href="<?php echo SITE_URL;?>/responders/add" title="Add Responder">
										<i class="fa fa-plus-square-o"></i> Add Auto Responder
									</a>
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
                                        <i class="fa fa-repeat"></i>Auto Responders</div>
                                    <div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
                                </div>
                <div class="portlet-body">
					<div class="table-scrollable">
						<table class="table table-striped table-bordered table-hover table-condensed">
							<thead>
								<tr>
									<th>Group Name</th>
									<th><?php echo $this->Paginator->sort('days');?></th>
									<th><?php echo $this->Paginator->sort('name');?> </th>
									<?php if((!empty($users))||(!empty($numbers_mms))){   ?>
									<th><?php echo $this->Paginator->sort('Type','sms_type');?> </th>
									<?php } ?>
									<th class="actions"><?php __('Actions');?></th>
								</tr>
								<?php
									$i = 0;
									foreach ($responders as $responder):
									$class = null;
									if ($i++ % 2 == 0) {
									$class = ' class="altrow"';
									}
								?>
							</thead>
							<!--<tbody>-->
								<tr <?php echo $class;?>>
									<td> <?php echo $responder['Group']['group_name'];?>&nbsp; </td>
									<td> <?php echo $responder['Responder']['days'];?>&nbsp; </td>
									<td> <?php echo ucfirst($responder['Responder']['name']);?>&nbsp; </td>
									<?php if((!empty($users))||(!empty($numbers_mms))){   ?>
									<td><?php if($responder['Responder']['sms_type']==2){
											echo 'MMS';
										}else{
										echo 'SMS';
											 }?>&nbsp;
									</td>
							<?php   } ?>
									<td class="actions">
										<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $responder['Responder']['id']),array('class' => 'btn blue btn-outline btn-sm')); ?>
										<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $responder['Responder']['id'],'0'), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this responder?', true))); ?>
									</td>
								</tr>
								<?php endforeach; ?>
							<!--</tbody>-->
						</table>
					</div>
					<div class="dataTables_paginate paging_bootstrap_number" id="sample_2_paginate">
						<ul class="pagination" style="visibility: visible;">
							<ul class="pagination">
								<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
								echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));?>
								</li>
								<li>
								<?php echo $this->Paginator->numbers();?> 
								</li>
								<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));?>
								</li>
							</ul>
						</ul>
					</div>
				</div>
			</div>               
	</div>
</div>         