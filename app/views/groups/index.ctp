<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Groups</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
									<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Manage Groups</span>
					</li>
				</ul>  
					<div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">Actions <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="<?php echo SITE_URL;?>/groups/add" title="Add Group"><i class="fa fa-plus-square-o"></i> Add Group</a>
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
							<i class="fa fa-users"></i>Groups 
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
											<th scope="col"> <?php echo $this->Paginator->sort('group_name');?> </th>
                                                                                        <th scope="col"> <?php echo $this->Paginator->sort('group_type');?> </th>
											<th scope="col"><?php echo $this->Paginator->sort('keyword');?> </th>
											<?php if((!empty($users))||(!empty($numbers_mms)))  { ?>
											<th><?php echo $this->Paginator->sort('Type','sms_type');?></th>
											<?php  } ?>
											<th class="actions"><?php __('Actions');?></th>
										</tr>
										<?php
											$i = 0;
	                                                                                foreach ($groups as $group):
		                                                                        $class = null;
		                                                                        if ($i++ % 2 == 0) {
			                                                                $class = ' class="altrow"';
		                                                                        }
											if($group['Group']['sms_type']==1){
											$type='SMS';
											}else if($group['Group']['sms_type']==2){
											$type='MMS';
											}

                                                                                        if($group['Group']['group_type']==0){
											$grouptype='Coupon';
											}else if($group['Group']['group_type']==1){
											$grouptype='Join';
										        }else if($group['Group']['group_type']==2){
											$grouptype='Property (Real Estate)';
										        }else if($group['Group']['group_type']==3){
											$grouptype='Vehicle';
											}
										?>
								</thead>
									<!--<tbody>-->
										<tr>
										
											<td>
												<a class="nyroModal" href="<?php echo SITE_URL;?>/groups/contactlist/<?php echo $group['Group']['id']; ?>"><?php echo ucfirst($group['Group']['group_name']);?></a>(<?php echo $group['Group']['totalsubscriber']; ?>)
											</td>
                                                                                        <td>
												<?php echo $grouptype; ?>&nbsp;
											</td>	
											<td> 
												<?php echo $group['Group']['keyword']; ?>
											</td>
                                             <?php if((!empty($users))||(!empty($numbers_mms))){ ?>
											<td>
												<?php echo $type; ?>&nbsp;
											</td>		
											<?php } ?>
											<td> 
												<?php echo $this->Html->link(__('Send SMS', true), array('controller'=>'messages','action' => 'send_message', $group['Group']['id'],'groups'), array('class' => 'btn blue btn-outline btn-sm')); ?>
												
												<?php echo $this->Html->link(__('QR Code', true), array('action' => 'view', $group['Group']['id']),array('class' => 'btn blue btn-outline btn-sm')); ?>

												<?php echo $this->Html->link(__('Members', true), array('action' => 'contactlist', $group['Group']['id']),array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
	
												<!--a class="btn btn-success" onclick="$('.modal-body > form').submit();" data-toggle="modal" href="<?php echo SITE_URL;?>/groups/contactlist"<?php  $group['Group']['id'];?>>Members</a-->
												<!--
												<?php if(API_TYPE!=2){ ?>			
												<?php echo $this->Html->link(__('Voice Broadcast', true), array('action' => 'voicebroadcasting', $group['Group']['id']),array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>  
												
												<?php } ?>   -->
												<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $group['Group']['id']),array('class' => 'btn green btn-outline btn-sm')); ?>
												<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $group['Group']['id'],0), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this group and everything assigned to it(contacts, birthday wishes, voice broadcasts, loyalty programs, and autoresponders)?', true))); ?>
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
										echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));?></li>
										<li >
										<?php echo $this->Paginator->numbers();?>	
										</li>
										<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));?></li>
										</ul>
									</ul>
								</div>
                    </div>
               </div>           
		</div>
  </div> 