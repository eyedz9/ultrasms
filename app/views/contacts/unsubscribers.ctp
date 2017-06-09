<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Unsubscribers</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
									<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Unsubscribers</span>
					</li>
				</ul>  
					<div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">Actions <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
									 <a href="<?php echo SITE_URL;?>/contacts/exportunsubs" title="Export Unsubscribers"><i class="fa fa-file-excel-o"></i> Export Unsubscribers  </a>
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
							<i class="fa fa-user-times"></i>Unsubscribers
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
						<tr>
							<th>Name</th>
							<th>Number</th>
							<th>Group</th>
							<th>Source</th>
                                                        <th>Unsubscribed Date</th>
			                                <th class="actions" style="text-align: center">Action</th>	
						</tr>
							<?php 
								$i = 0;
								foreach ($contacts as $contact):
								$class = null;
								if ($i++ % 2 == 0) {
								$class = ' class="altrow"';
								}
							?>
						<tr <?php echo $class;?>>
							
							<td style="text-align: left;"><?php echo $contact['Contact']['name']; ?>&nbsp;</td>
								
							<td style="text-align: left;"><?php echo $contact['Contact']['phone_number']; ?>&nbsp;</td>								
							<td style="text-align: left;"><?php echo $contact['Group']['group_name']; ?>&nbsp;</td>
								
                                                        <?php if($contact['ContactGroup']['subscribed_by_sms']==0){ ?>
							<td style="text-align: left;">Import</td>
								<?php }else if($contact['ContactGroup']['subscribed_by_sms']==1) { ?>
							<td style="text-align: left;">SMS</td>
								<?php }else if($contact['ContactGroup']['subscribed_by_sms']==2){ ?>
							<td style="text-align: left;">Widget</td>
								<?php }else { ?>
                                                        <td style="text-align: left;">Kiosk</td>
                                                                <?php } ?>

                                                        <td style="text-align: left;">
								<?php echo $contact['ContactGroup']['created'] ; ?>&nbsp;</td>

							

								
							<td class="actions">
								<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $contact['Contact']['id']), array('class' => 'btn red btn-outline btn-sm'),sprintf(__('Are you sure you want to delete this contact?',true))) ; ?>
								
						</tr>
							<?php endforeach; ?>
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