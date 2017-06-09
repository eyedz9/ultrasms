<script type="text/javascript" charset="utf-8">
	function delete1(id){
	//alert(id);
	var a = confirm('Are you sure you want to delete?');
	if(a==true){
	//alert('incondition');
	window.location="<?php echo SITE_URL?>/logs/deleteall/"+id;
	}
	}
</script>
		<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> <?php __('Logs');?></h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<span><?php __('Logs');?> </span>
						</li>
					</ul>
					<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn btn-fit-height grey-salt dropdown-toggle" type="button"> Actions
								<i class="fa fa-angle-down"></i>
							</button>
							<ul role="menu" class="dropdown-menu pull-right">
								<li>


									<?php
									if(API_TYPE==2){
									$navigation = array(
									'SMS Inbox' => '/logs/index/smsinbox',
									'Single SMS Outbox' => '/logs/index/singlesmsoutbox',
									'Group SMS Outbox' => '/logs/index/groupsmsoutbox'
									);	
									}else{
									$navigation = array(
									'SMS Inbox' => '/logs/index/smsinbox',
									'Single SMS Outbox' => '/logs/index/singlesmsoutbox',
									'Group SMS Outbox' => '/logs/index/groupsmsoutbox',
									'Voicemail' => '/logs/index/voice',
									'Voice Broadcast' => '/logs/index/broadcast',
                                                                        'Call Forward' => '/logs/index/callforward'
									);	
									}
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
				<div class="portlet box red">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-database"></i><?php __('Logs');?> </div>
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
					</div>			
					<?php if($type == 'groupsmsoutbox'){?>					
					<div class="portlet-body">
<a href="#null" onclick="delete1(5)" class="btn red" style="float:right"> Delete ALL <i class="fa fa-trash-o"></i></a>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-condensed">
							<thead>
								<tr>										
									<th><?php echo $this->Paginator->sort('group_name');?></th>
									<th>Recipients</th>
									<th>Sent on</th>
									<th class="actions"><?php __('Actions');?></th>
									<!--<th>
											<?php 
								if($type=="groupsmsoutbox"){?>
								<a href="#null" onclick="delete1(5)" class="btn red"> Delete ALL <i class="fa fa-trash-o"></i></a>
								<?php	}?>
									</th>-->
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
									<td><?php echo $log['Group']['group_name']; ?>&nbsp;</td>
									<td><?php echo $log['Group']['totalsubscriber']; ?>&nbsp;</td>
									<td><?php echo $log['GroupSmsBlast']['created']; ?>&nbsp;</td>
									<td class="actions">
							
										<?php echo $this->Html->link(__('Sent Statistics', true), array('action' => 'sentstatistics', $log['GroupSmsBlast']['id']), array('class' => 'btn blue btn-outline btn-sm')); ?>
										<?php
										if(API_TYPE==0){
										echo $this->Html->link(__('Refresh Statistics', true), array('action' => 'refreshstatistics', $log['GroupSmsBlast']['id']), array('class' => 'btn blue btn-outline btn-sm'));
										}?>		
										<?php echo $this->Html->link(__('Delete', true), array('action' => 'grouplogdelete', $log['GroupSmsBlast']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true)));
										?>
										
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
					<!--h1>voice</h1-->
					<?php }else if($type == 'voice'){?>
					<div class="portlet-body">
<a href="<?php echo SITE_URL;?>/logs/export/voice" class="btn green" style="float:right">Export Voicemail <i class="fa fa-file-excel-o"></i></a>
&nbsp;&nbsp;<a href="#null" onclick="delete1(4)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>										
										<th><?php echo $this->Paginator->sort('read');?></th>
										<th><?php echo $this->Paginator->sort('phone_number');?></th>
										<th><?php echo $this->Paginator->sort('created');?></th>
										<th class="actions"><?php __('Actions');?></th>
										<!--<th>
											<?php 
										if($type=="voice"){?>
										<a href="<?php echo SITE_URL;?>/logs/export" class="btn dark">Export Voicemail</a>
										<a href="#null" onclick="delete1(4)" class="btn default">Delete ALL</a>
										<?php 	}
										?>
										</th>-->
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
									<tr	<?php echo $class;?>>
										<td><?php echo $status = ($log['Log']['read']==0) ? 'New' : 'Heard'; ?>&nbsp;</td>		
										<td><?php echo $log['Log']['phone_number']; ?>&nbsp;</td>
										<td><?php echo $log['Log']['created']; ?>&nbsp;</td>
										<td class="actions">

									

											<?php if(API_TYPE==0){?>
<!--<?php echo $this->Html->link(__('Read', true), array('action' => 'view', $log['Log']['id']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>-->
<?php } ?>
											<?php if(strpos($log['Log']['voice_url'],"http") !== false && (API_TYPE==0 || API_TYPE==3)){ ?>
											<?php echo $this->Html->link(__('Listen', true),''.$log['Log']['voice_url'].'', array('class' => 'btn blue btn-outline btn-sm ')); ?>
											<?php } ?>
											<?php if(API_TYPE==1){?>
											<?php echo $this->Html->link(__('Listen', true),''.$log['Log']['voice_url'].'', array('class' => 'btn blue btn-outline btn-sm')); ?>
											<?php } ?>
											<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $log['Log']['id'],$type), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true), $log['Log']['id']));
											?>
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
					<!--h1>Broadcast</h1-->
					<?php	}else if($type == 'callforward'){ ?>
						<div class="portlet-body">
						<a href="<?php echo SITE_URL;?>/logs/export/callforward" class="btn green" style="float:right">Export Call Forward <i class="fa fa-file-excel-o"></i></a>
						&nbsp;&nbsp;<a href="#null" onclick="delete1(7)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th><?php echo $this->Paginator->sort('read');?></th>
										<th><?php echo $this->Paginator->sort('phone_number');?></th>
										<th>Call Duration</th>
										<th><?php echo $this->Paginator->sort('created');?></th>
										<th class="actions"><?php __('Actions');?></th>
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
										<td><?php echo $status = ($log['Log']['read']==0) ? 'New' : 'Heard'; ?>&nbsp;</td>		
										<td><?php echo $log['Log']['phone_number']; ?>&nbsp;</td>
										<td><?php echo $log['Log']['call_duration']; ?>&nbsp;</td>
										<td><?php echo $log['Log']['created']; ?>&nbsp;</td>
										<td class="actions">

											
											<?php if(strpos($log['Log']['voice_url'],"http") !== false) { ?>
											<?php echo $this->Html->link(__('Listen', true),''.$log['Log']['voice_url'].'', array('class' => 'btn blue btn-outline btn-sm')); ?>
											<?php } ?>
											<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $log['Log']['id'],$type), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true), $log['Log']['id']));
											?>
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
					<?php	}else if($type == 'broadcast'){ ?>
					
					<div class="portlet-body">
					<a href="<?php echo SITE_URL;?>/logs/export/broadcast" class="btn green" style="float:right">Export Broadcast <i class="fa fa-file-excel-o"></i></a>
					&nbsp;&nbsp;<a href="#null" onclick="delete1(6)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th><?php echo $this->Paginator->sort('read');?></th>
										<th><?php echo $this->Paginator->sort('phone_number');?></th>
										<th>Status</th>
										<th><?php echo $this->Paginator->sort('created');?></th>
										<th class="actions"><?php __('Actions');?></th>
										<!--<th>
										<?php 
											if($type=="broadcast"){?>
											<a href="<?php echo SITE_URL;?>/logs/export" class="btn dark">Export BroadCast</a>
											<a href="#null" onclick="delete1(6)" class="btn default">Delete ALL</a>
											<?php 	}
											?>
										
										</th>-->
										
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
										<td><?php echo $status = ($log['Log']['read']==0) ? 'New' : 'Heard'; ?>&nbsp;</td>		
										<td><?php echo $log['Log']['phone_number']; ?>&nbsp;</td>
										<td><?php echo $log['Log']['sms_status']; ?>&nbsp;</td>
										<td><?php echo $log['Log']['created']; ?>&nbsp;</td>
										<td class="actions">

											
											
                                                                                        <?php if(strpos($log['Log']['voice_url'],"http") !== false) { ?>
											<?php echo $this->Html->link(__('Listen', true),''.$log['Log']['voice_url'].'', array('class' => 'btn blue btn-outline btn-sm')); ?>
											<?php } ?>
											<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $log['Log']['id'],$type), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true), $log['Log']['id']));
											?>
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
					<?php }else{ ?>							
					<div class="portlet-body">
						<?php if($type=="smsinbox"){?>	
						<a href="<?php echo SITE_URL;?>/logs/export/smsinbox" class="btn green" style="float:right">Export SMS Inbox <i class="fa fa-file-excel-o"></i></a>
						&nbsp;&nbsp;<a href="#null" onclick="delete1(1)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<?php }else if($type=="smsoutbox"){ ?>
						<a href="<?php echo SITE_URL;?>/logs/export/smsoutbox" class="btn green" style="float:right">Export SMS Outbox <i class="fa fa-file-excel-o"></i></a>
						&nbsp;&nbsp;<a href="#null" onclick="delete1(2)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<?php }else if($type=="singlesmsoutbox"){ ?>
						<a href="<?php echo SITE_URL;?>/logs/export/singlesmsoutbox" class="btn green" style="float:right">Export Single SMS Outbox <i class="fa fa-file-excel-o"></i></a>
						&nbsp;&nbsp;<a href="#null" onclick="delete1(3)" class="btn red" style="float:right">Delete ALL <i class="fa fa-trash-o"></i></a>
						<?php }?>

						<div class="table-scrollable">							
							<table class="table table-striped table-bordered table-hover table-condensed">							
								<thead>
									<tr>
										<?php if($type=="smsinbox"){?>			
										<th><?php echo $this->Paginator->sort('read');?></th>
										<th><?php echo $this->Paginator->sort('phone_number');?></th>
                                                                                <th><?php echo $this->Paginator->sort('name');?></th>
                                                                                <th><?php echo $this->Paginator->sort('message');?></th>
										<th><?php echo $this->Paginator->sort('created');?></th>
										<th>Status</th>
										<th class="actions"><?php __('Actions');?></th>
										
										<!--<th><a href="<?php echo SITE_URL;?>/logs/export" class="btn dark">Export SMS Inbox</a>
										<a href="#null" onclick="delete1(1)" class="btn red">Delete ALL <i class="fa fa-trash-o"></i></a>
										</th>-->
									   
										<?php }else if($type=="smsoutbox"){ ?>
										<th><?php echo $this->Paginator->sort('read');?></th>
										<th><?php echo $this->Paginator->sort('phone_number');?></th>
										<th><?php echo $this->Paginator->sort('created');?></th>
										<th>&nbsp;</th> 
										<th>Status</th>
										<th class="actions"><?php __('Actions');?></th>
									<!--<th>
											<a href="<?php echo SITE_URL;?>/logs/export" class="btn yellow" >Export SMS Outbox <i class="fa fa-file-excel-o"></i></a>
											<a href="#null" onclick="delete1(2)" class="btn red">Delete ALL <i class="fa fa-trash-o"></i></a>
										</th>-->
										<?php }else if($type=="singlesmsoutbox"){ ?>
											<th><?php echo $this->Paginator->sort('read');?></th>
											<th><?php echo $this->Paginator->sort('phone_number');?></th>
											<th><?php echo $this->Paginator->sort('created');?></th>
											<th>&nbsp;</th> 
											<th>Status</th>
											<th class="actions"><?php __('Actions');?></th>											
<!--											<th>
											<a href="<?php echo SITE_URL;?>/logs/export" class="btn dark">Export Single SMS Outbox</a>
											<a href="#null" onclick="delete1(3)" class="btn default">Delete ALL</a>
										</th>-->
										  <?php }?>
										
									</tr>
								</thead>
								<?php
								$i = 0;
								foreach ($logs as $log):	
								$class = null;
								if ($i++ % 2 == 0) {
								$class = ' class="altrow"';
								}
								?>
								<!--<tbody>-->
									<tr <?php echo $class;?>>
										<td><?php echo $status = ($log['Log']['read']==0) ? 'New' : 'Read'; ?>&nbsp;</td>		
										<td><?php echo $log['Log']['phone_number']; ?>&nbsp;</td>
                                                                                
										<?php if($type=="smsinbox"){ ?>
											  <td><?php echo $log['Log']['name']; ?>&nbsp;</td>
                                                                                          <td><?php echo $log['Log']['text_message']; ?>&nbsp;</td>
										<?}?>
                                                                                
										<td><?php echo $log['Log']['created']; ?>&nbsp;</td>
										<?php if($log['Log']['route']=='outbox'){?>
										<?php if ($log['Log']['sms_status']=='undelivered' || $log['Log']['sms_status']=='failed'){?>
										<td><?php echo $this->Html->link($this->Html->image('note-error.png'), array('action' => 'errormessage', $log['Log']['id']), array('escape' =>false, 'class' => 'nyroModal')); ?></td>
										<td><?php echo $log['Log']['sms_status']; ?>&nbsp;</td>
										<?php }else{ ?>
										<td>&nbsp;</td>
										<td><?php echo $log['Log']['sms_status']; ?>&nbsp;</td>
										<?php } ?>
										<?php }else{ ?>
										<td><?php echo $log['Log']['sms_status']; ?>&nbsp;</td>
										<?php }?>
										<td class="actions">
										
											<?php echo $this->Html->link(__('Read', true), array('action' => 'view', $log['Log']['id']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>
											<?php //echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'send_sms', $log['Log']['phone_number']), array('class' => 'nyroModal')); ?>
											<?php if(API_TYPE==0){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>
											<?php }else if(API_TYPE==3){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'plivo_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>
											<?php }else if(API_TYPE==2){?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'slooce_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>
											<?php }else{?>
											<?php echo $this->Html->link(__('Send SMS', true), array('controller' => 'contacts','action' => 'nexmo_send_sms', $log['Log']['phone_number']), array('class' => 'btn blue btn-outline  btn-sm nyroModal')); ?>
											<?php } ?>
											<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $log['Log']['id'],$type), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this record?', true), $log['Log']['id']));
											?>
										</td>
									</tr>
								<!--</tbody>-->
										
                                    <?php endforeach; ?>									
								</tbody>
							</table>
							

							<?php }?>
						</div>
						<?php if($type=="smsinbox" || $type=="singlesmsoutbox" ){ ?>	
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
							<?php } ?>

					</div>
				</div>
			</div>	
		</div>		
                           