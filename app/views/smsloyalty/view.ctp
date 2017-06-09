	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> <?php  __('Loyalty Details');?></h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span><?php  __('Loyalty Details');?></span>
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
									'Back' => '/smsloyalty/index',
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
			<div class="portlet light portlet-fit ">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-gift font-red"></i>
						<span class="caption-subject font-red sbold uppercase"><?php  __('Loyalty Details');?></span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">
							<!--div><//?php $i = 0; $class = ' class="altrow"';?></div-->
							<table class="table table-bordered table-striped" id="user" >
								<tbody>
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Program Name'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['program_name']; ?> 
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Start Date'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['startdate']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"><?php if ($i % 2 == 0) echo $class;?><?php __('End Date'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['enddate']; ?> 
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Punch Code'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['coupancode']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Status Keyword'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['codestatus']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Reach Goal'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['reachgoal']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Add Points Message(Punch Code)'); ?></td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['addpoints']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Reach Goal Message(Reward Offer)'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['reachedatgoal']; ?>
												<?php if($loyalty['Smsloyalty']['image'] !=''){?>
													<img src="<?php echo SITE_URL;?>/mms/<?php echo $loyalty['Smsloyalty']['image']; ?>" class="thumbnail" style="height:70px!important;width:70px!important;"/>
												<?php } ?>			
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Check Status Message(Status Keyword)'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['checkstatus']; ?>
											</span>
										</td>
									</tr> 
									<tr>												
										<td style="width:15%"> <?php if ($i % 2 == 0) echo $class;?><?php __('Created'); ?> </td>
										<td style="width:35%">
											<span class="text-muted"> 
												<?php if ($i++ % 2 == 0) echo $class;?>
												<?php echo $loyalty['Smsloyalty']['created']; ?>
											</span>
										</td>
									</tr> 											
								</tbody>
							</table>
						</div>
					</div>					
				</div>
			</div>  
		</div>
	</div>