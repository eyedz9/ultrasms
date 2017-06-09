<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Package List
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
					<span> Package List</span>
				</li>
			</ul>                
		</div>
		<div class="clearfix"></div>
		<?php echo $this->Session->flash(); ?>
		<div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-green"></i>
                                <span class="caption-subject font-green bold uppercase">Package List </span>
                            </div>
                         
                        </div>
                        <div class="portlet-body" style="height: auto;">
                            <div class="pricing-content-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="price-column-container border-active">
                                            <div class="price-table-head bg-blue">
                                                <h2 class="no-margin">Welcome <?php echo $user['User']['first_name']?>!</h2>
												
                                            </div>
                                            <div class="arrow-down border-top-blue"></div>
                                            <div class="price-table-pricing">
                                                <h4>
												Your current account balances:
                                                </h4>
                                             
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
														<font style="font-size:16px;">Text Message Credits: <b><?php echo $user['User']['sms_balance']?></b><br>
														Voicemail Credits: <b><?php echo $user['User']['voice_balance']?></b><br>
														</font>
													</div>
													<br><br><h4>SMS Credit Packages</h4><br>
														<?php if($id=='1'){
														echo "<table border=0><tr>";
															foreach($text_packages as $text):
															echo "<td>".$this->Html->link($text['Package']['name'], array('controller' =>'users', 'action' =>'purchase_credit', $text['Package']['id']), array('class' => 'nyroModal inputbutton'))."</td>";
															endforeach;
															?>
															</tr></table>
															<br><br><br><h3>Voicemail Credit Packages</h3>
															<table border=0><tr>
															<?php
															foreach($voice_packages as $voice):
															echo "<td>".$this->Html->link($voice['Package']['name'], array('controller' =>'users', 'action' =>'purchase_credit', $voice['Package']['id']), array('class' => 'nyroModal inputbutton'))."</td>&nbsp;";
															endforeach;
															?>
															</tr></table>
															<br>
														
														<?php }else if($id=='2'){
														echo "<table border=0><tr>";
															foreach($text_packages as $text):
															echo "<td>".$this->Html->link($text['Package']['name'], array('controller' =>'users', 'action' =>'purchase_credit_checkout', $text['Package']['id']), array('class' => 'nyroModal inputbutton'))."</td>";
															endforeach;
															?>
															</tr>
															</table>
															<br><br><br><h3>Voicemail Credit Packages</h3>
															<table border=0>
															<tr>
															<?php
															foreach($voice_packages as $voice):
															echo "<td>".$this->Html->link($voice['Package']['name'], array('controller' =>'users', 'action' =>'purchase_credit_checkout', $voice['Package']['id']), array('class' => 'nyroModal inputbutton'))."</td>&nbsp;";
															endforeach;
															?>
															</tr></table>
															<br>
														
														
														
														<?php } ?>
														
                                                </div>
                                              
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
	</div>
</div>