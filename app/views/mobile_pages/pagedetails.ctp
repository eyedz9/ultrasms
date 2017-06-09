<script>
function previewPopUp (id) {
   //window.open('<?php echo SITE_URL?>/lp.php?id='+id);  
   window.open('<?php echo SITE_URL?>/lp.php?id='+id,'popUpWindow','left=100,top=50,width=300,height=600,resizable=yes,toolbar=yes,scrollbars=yes,menubar=no,,location=no,directories=no, status=yes');  
   //window.open('<?php echo SITE_URL?>/lp.php?id='+id,'mywin','left=20,top=20,width=300,height=auto,toolbar=1,scrollbars=yes,location=true');   
} 
</script>
	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> Mobile Splash Pages</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Mobile Splash Pages</span>
					</li>
				</ul>  
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
							   <a href="<?php echo SITE_URL;?>/mobile_pages/add" title="Add Mobile Splash Page"><i class="fa fa-plus-square-o"></i> Add Mobile Splash Page</a>
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
						<i class="fa fa-paint-brush"></i>Mobile Splash Pages </div>
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
									<th scope="col">Title</th>
									<th class="actions"><?php __('Actions');?></th>
								</tr>
								<?php
									$i = 0;
									foreach ($mobilespages as $mobilespage):
									$class = null;
									if ($i++ % 2 == 0) {
									$class = ' class="altrow"';
									}
									?>
									<?php //$id=base64_encode($mobilespage['MobilePage']['id']);
									$base64 = base64_encode($mobilespage['MobilePage']['id']);
									$changeid = str_replace('=' ,'', $base64);
									$id = str_replace('+' ,'', $changeid );
								?>
							</thead>
							<!--<tbody>-->
								<tr <?php echo $class;?>>
									<td><?php echo $mobilespage['MobilePage']['title']; ?></td>
									<td class="actions">
										<a href="#null" class="btn blue btn-outline btn-sm" onclick="previewPopUp('<?php echo $id; ?>')">View</a>
										<?php echo $this->Html->link(__('QR code', true), array('action' => 'qrcodeview', $mobilespage['MobilePage']['id']),array('class' => 'btn blue btn-outline btn-sm')); ?>
										<?php echo $this->Html->link(__('Send page', true), array('controller'=>'messages','action' => 'send_message', $mobilespage['MobilePage']['id'],'mobile'),array('class' => 'btn blue btn-outline btn-sm')); ?>
										<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $mobilespage['MobilePage']['id']),array('class' => 'btn green btn-outline btn-sm')); ?>
										<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $mobilespage['MobilePage']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete?', true))); ?>
									</td>                                            
								</tr>
								<?php endforeach; ?>
							<!--</tbody>-->
						</table>
					</div>

			</div> 
		</div>
	</div>
</div>