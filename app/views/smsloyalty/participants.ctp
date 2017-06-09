	<div class="portlet box blue-dark">
		<div class="portlet-title">
			<div class="caption">
                                  Loyalty Program
			</div>
		</div>
		<div class="portlet-body">
			<div class="row">
				<div class="col-md-12">
					<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th><?php echo $this->Paginator->sort('Name');?></th>
									<th><?php echo $this->Paginator->sort('Phone','contact_id');?></th>
									<th><?php echo $this->Paginator->sort('Punches','count_trial');?></th>
									<th><?php echo $this->Paginator->sort('Winner','is_winner');?></th>
									<th><?php echo $this->Paginator->sort('Redeemed','redemptions');?></th>
									<th><?php echo $this->Paginator->sort('Date','msg_date');?></th>
								</tr>
								<?php
									$i = 0;
									foreach ($participant as $participant):
									$class = null;
									if ($i++ % 2 == 0) {
									$class = ' class="altrow"';
									}
								?>
							</thead>
							<!--<tbody>-->
								<tr <?php echo $class;?>>
								<td><?php echo ucfirst($participant['Contact']['name']);?></td>
								<td><?php echo ucfirst($participant['Contact']['phone_number']);?></td>
								<td><?php echo ucfirst($participant['SmsloyaltyUser']['count_trial']);?></td>
								<td><?php if($participant['SmsloyaltyUser']['is_winner']==1){ echo "Yes"; }else{ echo "No"; };?></td>
								<td><?php if($participant['SmsloyaltyUser']['redemptions']==1){ echo "Yes"; }else{ echo "No"; };?></td>
								<td><?php echo date('m/d/Y',strtotime($participant['SmsloyaltyUser']['msg_date']));?></td>
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
