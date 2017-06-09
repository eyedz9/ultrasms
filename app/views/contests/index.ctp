
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> <?php __('Contests');?>
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
						<span><?php __('Contests');?></span>
					</li>
				</ul> 
				<div class="page-toolbar">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
							

<li>
<a href="<?php echo SITE_URL;?>/contests/add" title="Add Contest"><i class="fa fa-plus-square-o"></i> Add Contest</a>
								
                            </li>
                        </ul>
                    </div>
                </div>	
				<div class="clearfix"></div>
			</div>
			<?php echo $this->Session->flash(); ?>
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-trophy"></i><?php __('Contests');?> 
					</div>
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
									<th>Name </th>
									<th>keyword</th>
									<th>Winning #</th>
									<th class="actions"><?php __('Actions');?></th>
								   
								</tr>
									<?php
								$i = 0;
								foreach ($contests as $contest):
									$class = null;
									if ($i++ % 2 == 0) {
										$class = ' class="altrow"';
									}
								?>
							</thead>
							<!--<tbody>-->
								<tr <?php echo $class;?>>
									<td>
										<?php echo ucfirst($contest['Contest']['group_name']).'('.$contest['Contest']['totalsubscriber'].')'; ?> &nbsp;
									</td>
									<td>
										<?php echo $contest['Contest']['keyword']; ?>&nbsp;
									</td>
									<?php //echo $contest['Contest']['totalsubscriber']; ?>
									<td>
										<?php echo $contest['Contest']['winning_phone_number']; ?>&nbsp;
									</td>
									<td class="actions">
									    <?php echo $this->Html->link(__('Send Contest', true), array('action' => 'sendcontest', $contest['Contest']['id']),array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
										<?php echo $this->Html->link(__('Pick Winner', true), array('action' => 'contest_winner', $contest['Contest']['id']),array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
										<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $contest['Contest']['id']),array('class' => 'btn green btn-outline btn-sm')); ?>
										<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $contest['Contest']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete?', true))); ?>
									</td>
								</tr>
								<?php endforeach; ?>
							<!--</tbody>-->
						</table>
						
					</div>
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