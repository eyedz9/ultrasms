<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Number List
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
					<span>Number List</span>
				</li>
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-phone"></i>Number List</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Number</th>
								<th>SMS</th>
								<th>Voice</th>
							</tr>
						</thead>
						<?php 
						$i = 0;
						foreach($plivoall_data as $invoicedetil) { 
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
						?>
						<!--<tbody>-->
							<tr <?php echo $class;?>> 
								<td>
								<?php echo $invoicedetil['UserNumber']['number'] ?>
								</td>
								<td><?php if($invoicedetil['UserNumber']['sms']==1){ ?>
                                      <i class="fa fa-check"></i>
								<?php } ?>
								</td>
								<td><?php if($invoicedetil['UserNumber']['voice']==1){ ?>
                                    <i class="fa fa-check"></i>
								<?php } ?>
								</td>
							</tr>
						<!--</tbody>-->
						<?php } ?>
					</table>
					
				</div>
<div class="dataTables_paginate paging_bootstrap_number">
						<ul class="pagination" style="visibility: visible;">
							<ul class="pagination">
								<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
								echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));?>
								</li>
								<li>
								<?php //echo $this->Paginator->numbers(array('class' => 'paginate_button'));?> 
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
		