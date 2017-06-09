<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title">Kiosk Builder</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
									<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Kiosk Builder</span>
					</li>
				</ul>  
					<div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="<?php echo SITE_URL;?>/kiosks/add" title="Add Group"><i class="fa fa-plus-square-o"></i> Create New Kiosk</a>
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
                                        <i class="fa fa-tablet"></i>Kiosks </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-scrollable">
					<table class="table table-striped table-bordered table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->Paginator->sort('name');?></th>
                                                    <th>Kiosk URL</th>
                                                    <th><?php echo $this->Paginator->sort('created');?></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <!--<tbody>-->
											<?php foreach ($kiosks as $kiosk): ?>
                                                <tr>
                                                    <td><?php echo $kiosk['Kiosks']['name']; ?></td>
                                                    <td><a href="<?php echo SITE_URL.'/kiosks/view/'.$kiosk['Kiosks']['unique_id'];?>" target="_blank" style="color:#005580; text-decoration: underline"><?php echo SITE_URL.'/kiosks/view/'.$kiosk['Kiosks']['unique_id'];?></a> &nbsp;</td>
                                                    <td><?php echo $kiosk['Kiosks']['created']; ?></td>
													<td>
													<a target="_blank" class="btn green btn-outline btn-sm" href="<?php echo SITE_URL;?>/kiosks/view/<?php echo $kiosk['Kiosks']['unique_id'];?>">View</a>
													<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $kiosk['Kiosks']['id']),array('class' => 'btn green btn-outline btn-sm')); ?>
													<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $kiosk['Kiosks']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this kiosk ?', true))); ?>				
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