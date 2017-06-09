<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>-->
<script type="text/javascript">
$(function() {
	$("table tr:nth-child(odd)").addClass("odd-row");
	$("table td:first-child, table th:first-child").addClass("first");
	$("table td:last-child, table th:last-child").addClass("last");
});

</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> <?php __('Group Scheduled Messages');?>
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
					<span><?php __('Group Scheduled Messages');?></span>
				</li>
			</ul>   
			<div class="page-toolbar">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
						<?php
						$navigation = array(
						'Send Message' => '/messages/send_message',
						'View Group Scheduled Messages' => '/messages/schedule_message',
						'View Contacts Scheduled Messages' => '/messages/singlemessages',
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
					</ul>
				</div>
			</div>	
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-clock-o"></i><?php __('Group Scheduled Messages');?> </div>
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>
			</div>
			<div class="portlet-body">
			<!--<table class="table table-bordered table-striped">
				<tbody>
					<tr>
						<td>
						<b>NOTE:</b> There may be small delays (1-15 minutes) depending on how often the jobs on server are set to run and when the job was last executed. The max delay there will ever be is 15 minutes.		
						</td>
					</tr>
				</tbody>
			</table>-->
<div class="note note-warning"><b>NOTE:</b> There may be small delays (1-15 minutes) depending on how often the jobs on server are set to run and when the job was last executed. The max delay there will ever be is 15 minutes.</div>	
				<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th ><?php echo $html->link("Group", array('controller'=>'messages', 'action' => 'schedule_message/Group.group_name/'.$sort));?></th>
								<th ><?php echo $html->link("Message", array('controller'=>'messages', 'action' => 'schedule_message/ScheduleMessage.message/'.$sort));?></th>
								<th ><?php echo $html->link("Send on", array('controller'=>'messages', 'action' => 'schedule_message/ScheduleMessage.send_on/'.$sort));?></th>
								<?php if((!empty($users)) || !empty($numbers_mms)){ ?>
								<th><?php echo $html->link("Type", array('controller'=>'messages', 'action' => 'schedule_message/ScheduleMessage.msg_type/'.$sort));?></th>
								<th ><?php echo $html->link("Media", array('controller'=>'messages', 'action' => 'schedule_message/ScheduleMessage.message/'.$sort));?></th>
								<?php } ?>
								<th ><?php echo $html->link("Created", array('controller'=>'messages', 'action' => 'schedule_message/ScheduleMessage.created/'.$sort));?></th>
								<th >Action</th>
							</tr>
						</thead>
						<?php 
							$i = 0;
							foreach($ScheduleMessage as $ScheduleMessages) { 
							$class = null;
							if($ScheduleMessages['ScheduleMessage']['msg_type']==1){
							$message=$ScheduleMessages['ScheduleMessage']['message'];
							$msg_type='SMS';
							}else if($ScheduleMessages['ScheduleMessage']['msg_type']==2){
							$message=$ScheduleMessages['ScheduleMessage']['mms_text'];
							$image_url=$ScheduleMessages['ScheduleMessage']['message'];
							$msg_type='MMS';
							}
							if ($i++ % 2 == 0) {
							$class = ' class="odd1-row"';
							}
							?>
						<!--<tbody>-->
							<tr <?php echo $class;?>> 
								<td><?php echo $ScheduleMessages['Group']['group_name'] ?></td>
								<td  style="word-break: break-all;"><?php echo $message; ?></td>
								<td><?php echo $ScheduleMessages['ScheduleMessage']['send_on'] ?></td>
								<?php
								if((!empty($users)) || !empty($numbers_mms)){ ?>
								<td><?php echo $msg_type; ?></td>
								<td  style="word-break: break-all;">
								<?php 
									if($ScheduleMessages['ScheduleMessage']['msg_type']==2){
										if($image_url!=''){
										$check=strpos($image_url,":");
										if($check!=''){
										$comma=strpos($image_url,",");
										if($comma!=''){
										$image_arr=explode(",",$image_url);
											foreach($image_arr as $value){	
											?>
											<img src="<?php echo $value; ?>" height="80px" width="70px" />
											<?php
											}
										}else{
										?>
										<img src="<?php echo $image_url ?>" height="80px" width="70px" />
										<?php
										}
										}
										}else{
										?>
										<img src="<?php echo $ScheduleMessages['ScheduleMessage']['pick_file'] ?>" height="80px" width="70px" />
										<?php
										}
									}
									?>
								</td>
						<?php   } ?>
								<td><?php echo $ScheduleMessages['ScheduleMessage']['created'] ?></td>
								<td class="actions" style="padding: 2px; width:215px; vertical-align: middle;">
								<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $ScheduleMessages['ScheduleMessage']['id']), array('class' => 'btn green btn-outline btn-sm')); ?>
								<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $ScheduleMessages['ScheduleMessage']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete?', true))); ?>
								<?php echo $this->Html->link(__('Copy WI', true), array('action' => 'copygroupschedule', $ScheduleMessages['ScheduleMessage']['id'], 1), array('escape' =>false, 'class' => 'btn blue btn-outline btn-sm')); ?>
								<?php echo $this->Html->link(__('Copy MI', true), array('action' => 'copygroupschedule', $ScheduleMessages['ScheduleMessage']['id'], 0), array('escape' =>false, 'class' => 'btn blue btn-outline btn-sm')); ?>
								</td>
							</tr>
							<?php } ?>
						<!--</tbody>-->
							
					</table>

					<?php 
						if ($i > 0){?>
						<table  class="table table-bordered">
							<tbody>
								<tr>
									<td>
										<b>Copy WI(Weekly Increment)</b> - If you want to create a recurring weekly scheduled SMS, "Copy WI" will create an exact duplicate of that scheduled SMS, but with a send on date 1 week later. 
										<br/><br/>
										<b>Copy MI(Monthly Increment)</b> - If you want to create a recurring monthly scheduled SMS, "Copy MI" will create an exact duplicate of that scheduled SMS, but with a send on date 1 month later. 
										<br/><br/>
										This is very fast and easy way to create recurring SMS, rather than manually creating every scheduled SMS from the Bulk SMS page. Of course, you can edit the details of each scheduled SMS as well.
									</td>
								</tr>
							</tbody>
						</table>
					<?php }?>
					
				</div>
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