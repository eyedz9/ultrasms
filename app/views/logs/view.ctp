			<div class="portlet box blue-dark">
				<div class="portlet-title">
						<div class="caption">
						<!--<i class="fa fa-users"></i>-->
						<?php  __('Log');?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">
							<!--div><//?php $i = 0; $class = ' class="altrow"';?></div-->
							<table class="table table-bordered table-striped">
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Phone Number'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $log['Log']['phone_number']; ?> 
											</span>
										</td>
									</tr>
<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Name'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $log['Log']['name']; ?> 
											</span>
										</td>
									</tr>
									<?php	if($log['Log']['msg_type']!='text'){
									if(API_TYPE==0){?>
		
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Transcribed Message'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $log['Log']['text_message']; ?>
											</span>
										</td>
									</tr> 
									<?php } ?>
									<tr>												
										<td style="width:15%"><?php if ($i % 2 == 0) echo $class;?><?php __('Listen'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<!--<a href="<?php echo $log['Log']['voice_url']; ?>" target="__blank">Download</a>-->
												<?php echo $this->Html->link(__('Download', true),''.$log['Log']['voice_url'].'', array('class' => '')); ?>
											</span>
										</td>
									</tr> 
									<?php
									}else{
									?>
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Text Message'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $log['Log']['text_message']; ?>
											</span>
										</td>
									</tr> 
									
									<?php if(!empty($log['Log']['image_url'])){?>
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Images'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php 
													$check=strpos($log['Log']['image_url'],":");
			
													if($check!=''){
													$comma=strpos($log['Log']['image_url'],",");
													if($comma!=''){
													$image_arr=explode(",",$log['Log']['image_url']);
													foreach($image_arr as $value){	
												?>
												<img src="<?php echo $value; ?>" height="100px" width="100px" />
												<?php } } else{?>
													<img src="<?php echo $log['Log']['image_url'] ?>" height="100px" width="100px" />
												<?php } }		?>
											</span>
										</td>
									</tr>
									<?php } ?>	
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Route'); ?></td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $log['Log']['route']; ?>
											</span>
										</td>
									</tr> 
									<?php } ?>
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Created'); ?></td>
										<td style="width:35%">
											<span class="text-muted"> 
											<?php if ($i++ % 2 == 0) echo $class;?>
											<?php echo $log['Log']['created']; ?>
											</span>
										</td>
									</tr> 
							</table>							
						</div>
					</div>
				</div>
			</div>

