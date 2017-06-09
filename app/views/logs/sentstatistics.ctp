	
	<style>
	.text-muted a.btn {
	float:left}
	
	</style>
	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"><?php __('Sent Statistics');?></h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span><?php __('Sent Statistics');?></span>
					</li>
				</ul>  
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<?php
								$navigation = array(
								'Back' => '/logs/index/groupsmsoutbox',
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
							</li>														
						</ul>
					</div>
				</div>				
			</div>		
			<div class="clearfix"></div>
			<?php echo $this->Session->flash(); ?>
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-bar-chart font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"> <?php __('Sent Statistics');?></span>
					</div>
					<!--<div class="actions">
						<div class="btn-group">
							<a class="btn btn-sm green dropdown-toggle" href="javascript:;" data-toggle="dropdown"> Actions
								<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li>
									<?php
									if(API_TYPE==0){ 
									echo $this->Html->link(__('Refresh Statistics', true), array('action' => 'refreshstatistics', $groupContacts['GroupSmsBlast']['id']), array('class' => 'btn btn-success '));?>
									<?php } ?>	
								</li>
								
							</ul>
						</div>
					</div>-->
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">
							<?php $percentage=$groupContacts['GroupSmsBlast']['total_successful_messages']/$groupContacts['GroupSmsBlast']['totals']*100; ?>
							<!--<table class="table table-bordered table-striped" id="user" >
								<tbody>-->
									<?php
									if(API_TYPE==0){?>
									<!--<tr>												
										<td style="width:100%">
											<span class="text-muted"> 
												<b>NOTE:</b> Delivery stats are updated on a live basis. We update the counts and statuses as soon as we get receipt from the gateway, however there are times we never receive the status update after sending, so to keep all stats accurate, click the "Refresh Statistics" button below.
											</span>-->
<div class="note note-warning"><b>NOTE:</b> Delivery stats are updated on a live basis. We update the counts and statuses as soon as we get receipt from the gateway, however there are times we never receive the status update after sending, so to keep all stats accurate, click the "Refresh Statistics" button below.</div>
										<!--</td>
									</tr>-->
									<?php } ?>																
								<!--</tbody>
							</table>-->
							<table class="table table-bordered table-striped" id="user" >
								<tbody>
									<tr>												
										<td style="width:15%"><b>Group</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['Group']['group_name'];?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"><b>Sent On</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['GroupSmsBlast']['created']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"><b>Recipients</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['GroupSmsBlast']['totals']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"><b>Successful Messages</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['GroupSmsBlast']['total_successful_messages']; ?>
											</span>
										</td>
									</tr>								
									<tr>												
										<td style="width:15%"><b>Unsuccessful Messages:</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['GroupSmsBlast']['total_failed_messages']; ?>
											</span>
										</td>	
									</tr>
									<tr>												
										<td style="width:15%"><b>% Successful:</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo ROUND($percentage,1).'%'; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"><b>Message</b></td>
										<td style="width:35%">
											<span class="text-muted"> 
										<?php echo $groupContacts['Log'][0]['text_message']; ?>
											</span>
										</td>
									</tr> 						
								</tbody>
							</table>
							<table class="table table-bordered table-striped" id="user" >
								
												<?php if($groupContacts['GroupSmsBlast']['isdeleted']==0 && $groupContacts['GroupSmsBlast']['total_failed_messages'] > 0){?> 
<tbody>
									<tr>												
										<td style="width:15%">
											<span class="text-muted"> 
												<?php echo $this->Html->link(__('Delete Contacts', true), array('action' => 'unsubscribe', $groupContacts['GroupSmsBlast']['id']), array('class' =>'btn btn-danger  btn-sm'), sprintf(__('Are you sure you want to delete failed contacts?', true)));
												?>&nbsp;<!--<a rel="tooltip" title="Delete only these contacts from your list that have failed to receive the messages" class="ico" href="#" title="help" style="float:left;margin-right:0;margin-top:7px;"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Delete only these contacts from your list where the status is 'failed'" data-original-title="Delete Failed Contacts" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</span>
										</td>
									</tr>
												<?php } ?>
											
									<?php if(!empty($groupContacts['Log'][0]['image_url'])){?>
									<tr>
										<th style="text-align: left;width: 15%; vertical-align:top">Images:</th>
										<td style="width:35%">
											
										
										<div class="col-md-9">
                                                    <div data-provides="fileinput" class="fileinput fileinput-new">
                                                       
														
														<?php 
											$check=strpos($groupContacts['Log'][0]['image_url'],":");
											if($check!=''){
												$comma=strpos($groupContacts['Log'][0]['image_url'],",");
												if($comma!=''){
												$image_arr=explode(",",$groupContacts['Log'][0]['image_url']);
												foreach($image_arr as $value){	
												?>
												<img src="<?php echo $value; ?>" height="100px" width="100px" />
												<?php
												}
												}else{
												?>
												<img src="<?php echo $groupContacts['Log'][0]['image_url'] ?>" height="100px" width="100px" />
												<?php
												}
											}		
											?>
												
											</div>
											</div>
												</td>
									</tr>
									<?php } ?>
									<!--<tr>
									<td></td>
									</tr>-->
									
										<?php if($groupContacts['GroupSmsBlast']['isdeleted']==1){
										//$message='Failed contacts have already been deleted';
										?>
<tr>
										<td colspan=2><div style="width:auto;font-weight: bold;">Failed contacts for this bulk SMS delivery have been deleted</div></td>
</tr>
										<?php } ?>
					
								</tbody>
							</table>
						</div>
					</div>					
				</div>
				<div class="portlet box red">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-list"></i><?php __('Group Sent Records');?></div>
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
					</div>
					<div class="portlet-body">

								
									<?php
									if(API_TYPE==0){ 
									echo $this->Html->link(__('Refresh Statistics', true), array('action' => 'refreshstatistics', $groupContacts['GroupSmsBlast']['id']), array('class' => 'btn btn-success', 'style' => 'float:right'));?>
									<?php } ?>	
								
								
							
						<div class="table-scrollable">				
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th scope="col"><?php echo $this->Paginator->sort('phone_number');?></th>
										<th scope="col"><?php echo $this->Paginator->sort('created');?></th>
										<th scope="col"><?php echo $this->Paginator->sort('Status','sms_status');?></th>
										<th scope="col">Actions</th>									
									</tr>
									<?php 
										$i = 0;
										foreach ($logs as $log):	
										$class = null;
										if ($i++ % 2 == 0) {
										$class = ' class="altrow"';
										}
									?>
								</thead>
								<!--<tbody>-->
									<tr <?php echo $class;?>>
										<td class="stattd"><?php echo $log['Log']['phone_number']; ?>&nbsp;</td>
										<td class="stattd"><?php echo $log['Log']['created']; ?>&nbsp;</td>
										<?php if ($log['Log']['sms_status']=='undelivered' || $log['Log']['sms_status']=='failed'){?>
										<td class="stattd"><?php echo $log['Log']['sms_status']; ?>&nbsp;<?php echo $this->Html->link($this->Html->image('note-error.png'), array('action' => 'errormessage', $log['Log']['id']), array('escape' =>false, 'class' => 'nyroModal')); ?></td>
										<?php }else{?>
										<td class="stattd"><?php echo $log['Log']['sms_status']; ?>&nbsp;</td>
										<?php }?>
										<td class="actions" style="border-right:1px solid #e0e0e0; border-top:1px solid #e0e0e0">
											<?php if(API_TYPE==0){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
											<?php }else if(API_TYPE==1){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'nexmo_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
											<?php }else if(API_TYPE==2){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'slooce_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
											<?php }else if(API_TYPE==3){ ?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'plivo_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>
											<?php } ?>
											<?php echo $this->Html->link(__('Delete Log', true), array('action' => 'delete', $log['Log']['id'],$type), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true), $log['Log']['id'])); ?>
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
	</div>