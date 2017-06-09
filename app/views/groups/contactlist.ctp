<style>
.nyroModalLink, .nyroModalDom, .nyroModalForm, .nyroModalFormFile {
    
    max-width: 1000px;
    min-height: 71px;
    min-width: 750px;
    padding: 10px;
    position: relative;
}
</style>
<!--<div class="page-content-wrapper" >--><br/><br/>
		<div class="page-content" >              
<!--			<h3 class="page-title"> Members</h3>-->
			<!--<div class="page-bar">				
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="<?php echo SITE_URL;?>/groups/addcontact" title="Add New Contact"><i class="fa fa-plus-square-o"></i> Add New Contact</a>
							</li>
						</ul>
					</div>
				</div>				
			</div>		-->

			<div class="clearfix"></div>
				<div class="portlet box blue-dark">
<div style="text-align:right;float:right; padding-bottom: 10px">
	<a class="nyroModal" href="<?php echo SITE_URL;?>/contacts/add/1" title="Add New Contact"><i class="fa fa-user-plus" aria-hidden="true" style="font-size:20px;color:white;margin-top:15px;margin-right:10px"></i>
</a>
	</div>	
                    <div class="portlet-title">
						<div class="caption">
							<!--<i class="fa fa-users"></i>-->Members 
						</div>
					</div>
					<div class="portlet-body">
<!--div style="font-size:18px; color:#186c8f; padding-bottom: 10px;"><b>Group Name: <font style="color: #83c03a"><?php echo ucfirst($groupcount['Group']['group_name']); ?></b></font></div>
	<div style="font-size:18px; color:#186c8f; padding-bottom: 10px;"><b>Total Members: <font style="color: #83c03a"><?php echo $groupcount['Group']['totalsubscriber']; ?></b></font></div--->
	
		<table class="table table-bordered table-striped">
					<tr>												
						<td style="width:15%"><b>Group Name:</b></td>
						<td style="width:35%">
						<span class="text-muted" style="font-size:16px;font-weight: bold;color: green"> 
                        <?php echo ucfirst($groupcount['Group']['group_name']); ?>
						</span>
						</td>
					</tr>
					<tr>												
						<td style="width:15%"><b>Total Members:</b></td>
						<td style="width:35%">
						<span class="text-muted" style="font-size:16px;font-weight: bold;color: green"> 
                       <?php echo $groupcount['Group']['totalsubscriber']; ?>
						</span>
						</td>
					</tr>
			</table>
	
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>                                                   
										<th>Name</th>
										<th>Phone Number</th>
										<th class="actions"><?php __('Actions');?></th>
									</tr>
										<?php 
											$i = 0;
											foreach ($groups as $group):
											$class = null;
											if ($i++ % 2 == 0) {
											$class = ' class="altrow"';
											}
										?>
								</thead>
									<!--<tbody>-->
										<tr>
											<td><?php echo $group['Contact']['name']; ?>&nbsp;</td>
											<td><?php echo $group['Contact']['phone_number']; ?>&nbsp;</td>
											<td>
												<?php if(API_TYPE==0){?>
													<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'send_sms', $group['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
												<?php }else if(API_TYPE==1){ ?>
													<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'nexmo_send_sms', $group['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
                                                <?php }else if(API_TYPE==2){ ?>
													<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'slooce_send_sms', $group['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
                                                <?php }else if(API_TYPE==3){ ?>
                                                      <?php echo $this->Html->link(__('Send SMS', true), array('action' => 'plivo_send_sms', $group['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
												<?php } ?>
												<?php echo $this->Html->link(__('Edit', true), array('action' => 'editcontact', $group['Contact']['id']), array('class' => 'btn green btn-outline btn-sm nyroModal')); ?>
												<?php echo $this->Html->link(__('Delete', true), array('action' => 'deletecontact', $group['Contact']['id']), array('class' => 'btn red btn-outline btn-sm buttonstyle'), sprintf(__('Are you sure you want to delete this contact?',true))); ?>
											</td>
										</tr>
										<?php endforeach; ?>
									<!--</tbody>-->
							</table>
								
						</div>
<ul class="pagination" style="visibility: visible;">
									<ul class="pagination">
									<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
									echo $this->Paginator->prev('<', array('class'=>'nyroModal'), null, array('class' => 'prev disabled'));?></li>
									<li >
									<?php echo $this->Paginator->numbers(array('class'=>'nyroModal'),array('class' => 'paginate_button'));?>	
									</li>
									<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next('>', array('class'=>'nyroModal'), null, array('class' => 'next disabled'));?></li>
									</ul>
								</ul>
					</div>
			   </div>                              
			</div>
<!--	  </div>         -->