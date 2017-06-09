<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#namephone").validate({
                    /*expression: "if (VAL) return true; else return false;",
                    message: "Please enter  name or phone number"*/
                });jQuery("#KeywordId").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Group"
               
                });
				
				jQuery("#lastName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter value"
               
                });
				
            });
            /* ]]> */	
            
function clearfilters(){

/*document.forms[0].elements["namephone"].value='';
document.forms[0].elements["ContactPhone"].value=1;*/
document.forms[0].elements["GroupId"].value=0;
document.forms[0].elements["ContactSource"].value=3;

} 
 

          		
</script>
<style>
.ValidationErrors{
color:red;
margin-bottom: 10px;
 float:right;
width:290px; 
 
}
</style>
	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> Contacts</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
								<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li><span>Contacts  </span></li>
					</ul> 
					<div class="page-toolbar">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
								<i class="fa fa-angle-down"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li>
									 <a  class="nyroModal" href="<?php echo SITE_URL;?>/contacts/add" title="Add Contact"><i class="fa fa-plus-square-o"></i> Add Contact </a>
								</li>	
								<li>
									 <a href="<?php echo SITE_URL;?>/contacts/export" title="Export Contacts"><i class="fa fa-file-excel-o"></i> Export Contacts  </a>
								</li>							
							</ul>
						</div>
					</div>	                
				</div>
			<div class="">
				<div class="row">
					<a  class="btn btn-warning center-block" href="<?php echo SITE_URL;?>/contacts/add" title="Add Contact"><i class="fa fa-plus-square-o"></i> Add Contact </a>
					<!--<div class="dashboard-stat blue">
						<div class="visual">
							<i class="fa fa-user"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?php echo $Subscribercount;?>"></span>
							</div>
							<div class="desc"> Total Subscribers </div>
						</div>
					</div>-->

<div class="tiles" style="margin-right:0px">
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="tile selected bg-blue-hoki" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Subscribers </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $totsubscribercount;?>">                </span> </div>
                                    </div>
</div>
</div>

<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="tile selected bg-red-sunglo" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-user-times"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Un-Subscribers </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $unsubscribercount ;?>"></span> </div>
                                    </div>
</div>
</div>

<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
<div class="tile selected bg-green-turquoise" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-comment"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Subs by SMS </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $Subscribercountsms ;?>"></span> </div>
                                    </div>
</div>
</div>

<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="tile selected bg-yellow-saffron" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Subs by Widget </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $Subscribercountwidget ;?>"></span> </div>
                                    </div>
</div>
</div>

<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="tile selected bg-blue-madison" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-tablet"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Subs by Kiosk </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $Subscribercountkiosk ;?>"></span> </div>
                                    </div>
</div>
</div>

<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="tile selected bg-green" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Imports </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $Subscribercountimport ;?>"></span> </div>
                                    </div>
</div>
</div>

<!--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="width: 19.8%">
<div class="tile selected bg-purple-studio" style="width:100% !important">
                                    <div class="corner"> </div>
                                    <div class="tile-body">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <div class="tile-object">
                                        <div class="name"> Imports </div>
                                        <div class="number" style="font-size:17px"> <span data-counter="counterup" data-value="<?php echo $Subscribercountimport;?>"></span> </div>
                                    </div>
</div>
</div>-->
</div>
                                    

				</div>
			</div>

			<div class="clearfix"></div>
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption font-blue">
						<i class="fa fa-search font-blue"></i>
						<span class="caption-subject bold uppercase"> Contact Search </span>
					</div>					
				</div>
				<div class="portlet-body">
                                           <div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
								   <i class="fa fa-search"></i> </div>
							     <div class="tools">
							         <a href="javascript:;" class="expand"></a>
							     </div>
							</div>
                                        <div class="portlet-body portlet-collapsed">
					<?php echo $this->Form->create('Contact',array('action'=> 'index'));?>
						<div class="form-body">
							<div class="form-group">
								<label for="exampleInputPassword1">Search by name or phone number</label>
								<div class="input-group">
								   <?php echo $this->Form->input('Contact.name', array('label'=>false,'div'=>false,'id'=>'namephone','class'=>'form-control')); ?>
									<span class="input-group-addon">
										<i class="fa fa-phone font-red"></i>
									</span>
								</div>
                            </div>
							<div class="form-group">
								<label>Select name or phone number</label>
								<?php
									$Option=array('1'=>'Name','2'=>'Phone number');
									echo $this->Form->input('Contact.phone', array(
									'class'=>'form-control',
									'label'=>false,
									'default'=>1,
									'type'=>'select',
									'options' => $Option
									));
								?>
							</div>							
							<div class="form-actions">
								<?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn blue'));?>
								<input class="btn default" style="cursor: pointer;" type="button" class="inputbutton" onclick="clearfilters();" value="Clear Filters">
								
							</div>
							<div class="form-group">
								<label>Groups</label>
								<?php
									$Group[0]='';
									echo $this->Form->input('Group.id', array(
									'class'=>'form-control',
									'label'=>false,
									'default'=>0,
									'type'=>'select',
									'onchange'=>'confirmmessage(this.value);',
									'options' => $Group
									));
								?>
							</div>
							 <div class="form-group">
								<label>Source</label>
								<?php
									$Option1=array('4'=>'','0'=>'Import','1'=>'SMS','2'=>'Widget','3'=>'Kiosk');
									echo $this->Form->input('Contact.source', array(
									'class'=>'form-control',
									'label'=>false,
									'default'=>4,
									'type'=>'select',
									'options' => $Option1
									));
								?>
							</div>
						</div>
						<div class="form-actions">
							<?php echo $this->Form->submit('Filter',array('div'=>false,'class'=>'btn blue'));?>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>		
			</div></div></div>
			<?php  echo $this->Session->flash(); ?>			
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-user"></i>Contacts  
					</div>
<div class="tools">
<a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
<a class="fullscreen" href="javascript:;" data-original-title="" title=""> </a>
<a class="remove" href="javascript:;" data-original-title="" title=""> </a>
</div>

				</div>
					
				<div class="portlet-body">
					<div class="table-scrollable">
					<?php if(empty($contacts)){?>
					<div style="font-weight: bold; font-size: 15px;text-align: center;">No contacts found. Please try again</div>
					<?php  }else{ ?>
					<table class="table table-striped table-bordered table-hover table-condensed">
						<tr>
							<th>&nbsp;</th>
							<th><?php echo $html->link('Name', array('controller'=>'contacts', 'action' => 'index/Contact.name/'.$sort));?></th>
							<?php if($users['User']['capture_email_name']==0){ ?>
							<th>Email</th>
							<?php } ?>
							<?php if($users['User']['birthday_wishes']==0){ ?>
							<th>Birthday</th>
								<?php } ?>
							<th><?php echo $html->link("Number", array('controller'=>'contacts', 'action' => 'index/Contact.phone_number/'.$sort));?></th>
								<th><?php echo $html->link("Group", array('controller'=>'contacts', 'action' => 'index/Group.group_name/'.$sort));?></th>
								<th>Sub</th>
								<th>Source</th>
								<!--<th class="actions" style="text-align: center">Action</th>	-->
                                                                <th class="actions" >Action</th>
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
							<td style="text-align: center">
								<?php if($contact['ContactGroup']['un_subscribers']==0){ ?>
								<a href="<?php echo SITE_URL;?>/messages/send_message/<?php echo $contact['Contact']['id']; ?>/contacts" data-container="body" data-trigger="hover" data-content="Schedule Appointment Reminder" data-original-title="Appointment Reminder" class="popovers"><i class="fa fa-calendar-plus-o" style="font-size:18px"></i></a>
								
							<?php } ?>
							<td style="text-align: left;"><?php echo $contact['Contact']['name']; ?>&nbsp;</td>
								<?php if($users['User']['capture_email_name']==0){ ?>
							<td style="text-align: left;"><?php echo $contact['Contact']['email']; ?>&nbsp;</td>
								<?php } ?>
								<?php if($users['User']['birthday_wishes']==0){ ?>
							<!--<td style="text-align: left;"><?php echo $contact['Contact']['birthday']; ?>&nbsp;</td>-->
                                                            <td style="text-align: left;"><?php echo $contact['Contact']['birthday'] == '0000-00-00'?"":$contact['Contact']['birthday']; ?></td>
								<?php } ?>
							<td style="text-align: left;"><?php echo $contact['Contact']['phone_number']; ?>&nbsp;
<?php if($contact['Contact']['carrier'] != ''){ ?>
<a href="javascript:;" data-container="body" data-trigger="hover" data-html="true" data-content="<b>Carrier:</b> <?php echo $contact['Contact']['carrier']; ?><br/><b>Location:</b> <?php echo $contact['Contact']['location']; ?><br/><b>Country:</b> <?php echo $contact['Contact']['phone_country']; ?><br/><b>Line Type:</b> <?php echo $contact['Contact']['line_type']; ?>" data-original-title="Number Information" class="popovers"><i class="fa fa-info-circle" style="font-size:16px;color:#67809f "></i></a>
<?} ?>
</td>								
							<td style="text-align: left;"><?php echo $contact['Group']['group_name']; ?>&nbsp;</td>
								<?php if($contact['ContactGroup']['un_subscribers']==0){ ?>
							<td style="text-align: center;">
								<!--<img  src="<?php echo SITE_URL ?>/img/mob.png" title="Subscriber">-->
                                                                 <i class="fa fa-user font-green-jungle" style="font-size:22px"></i>

							</td>
							<?php }else{ ?>
							<td style="text-align: center;">
								<!--<img  src="<?php echo SITE_URL ?>/img/mob2.png" title="Un-subscriber">-->
                                                                <i class="fa fa-user-times font-red-thunderbird" style="font-size:22px;"></i>

							</td>
								<?php } ?>
								<?php if($contact['ContactGroup']['subscribed_by_sms']==0){ ?>
							<td style="text-align: left;">Import</td>
								<?php }else if($contact['ContactGroup']['subscribed_by_sms']==1) { ?>
							<td style="text-align: left;">SMS</td>
								<?php }else if($contact['ContactGroup']['subscribed_by_sms']==2){ ?>
							<td style="text-align: left;">Widget</td>
								<?php }else { ?>
                                                        <td style="text-align: left;">Kiosk</td>
                                                                <?php } ?>
								<?php if($contact['ContactGroup']['un_subscribers']==0){ ?>
							<td class="actions">
								<?php if(API_TYPE==0){?>
								<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'send_sms', $contact['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); 



?>

								<?php }else if(API_TYPE==1){ ?>
								<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'nexmo_send_sms', $contact['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>

								<?php }else if(API_TYPE==2){ ?>
								<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'slooce_send_sms', $contact['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>

								<?php }else if(API_TYPE==3){ ?>
									<?php echo $this->Html->link(__('Send SMS', true), array('action' => 'plivo_send_sms', $contact['Contact']['phone_number']), array('class' => 'btn blue btn-outline btn-sm nyroModal')); ?>

								<?php } ?>
								<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $contact['Contact']['id']), array('class' => 'btn green btn-outline btn-sm nyroModal')); 

?>


								<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $contact['Contact']['id']), array('class' => 'btn red btn-outline btn-sm'),sprintf(__('Are you sure you want to delete this contact?',true))) ; 


?>
								<?php if(API_TYPE==2){?>
								<?php echo $this->Html->link(__('Stop', true), array('action' => 'stoppartner/'.$contact['ContactGroup']['group_id'], $contact['Contact']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to stop this contact?',true))); ?>
								<?php } ?>
							</td>
								<?php }else{ ?>
							<td>
								<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $contact['Contact']['id']), array('class' => 'btn green btn-outline btn-sm nyroModal')); ?>

								<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $contact['Contact']['id']), array('class' => 'btn red btn-outline btn-sm'), sprintf(__('Are you sure you want to delete this contact?',true))); ?>

							</td>
							<?php } ?>
						</tr>
							<?php endforeach; ?>
					</table>
						
						<?php } ?>
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
