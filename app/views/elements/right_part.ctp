<?php if($this->Session->check('User')):?>
	<div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu page-header-fixed  " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">


				<?php 
					$pay_activation_fee=PAY_ACTIVATION_FEES;
						if($loggedUser['User']['active']=='0' && $pay_activation_fee==1){?>
                        
                            <li class="nav-item start active open">
                            <a href="<?php echo SITE_URL;?>/users/dashboard" class="nav-link nav-toggle">
                                <i class="fa fa-usd" style="color:#15c1df"></i>
                                <span class="title">Pay Activation Fee</span>
                                <!--<span class="arrow"></span>-->
                            </a>     
			    </li>
			    <?php }else if($loggedUser['User']['assigned_number']=='0'){ ?>
                            
			<?php if(API_TYPE==1){?>
                        <li class="nav-item start active open">
                            <a class="nyroModal" href="<?php echo SITE_URL;?>/nexmos/searchcountry">
                                <i class="fa fa-phone" style="color:#15c1df"></i>
                                <span class="title">Get Number</span>
                                <!--<span class="arrow"></span>-->
                            </a>
                        </li>
			<?php }else if(API_TYPE==3){?>
					<li class="nav-item start active open">
                            <a class="nyroModal" href="<?php echo SITE_URL;?>/plivos/searchcountry">
                                <i class="fa fa-phone" style="color:#15c1df"></i>
                                <span class="title">Get Number</span>
                                <!--<span class="arrow"></span>-->
                            </a>
                        </li>
			<?php }else if(API_TYPE==0){?>
						<li class="nav-item start active open">
							<a class="nyroModal" href="<?php echo SITE_URL;?>/twilios/searchcountry">
							<i class="fa fa-phone" style="color:#15c1df"></i>
                                <span class="title">Get Number</span>
                                <!--<span class="arrow"></span>-->
								</a>
						</li>
                            
                        
							<?php } ?>
                        <li class="nav-item">
                            <a href="<?php echo SITE_URL;?>/users/profile" class="nav-link nav-toggle">
                                <i class="fa fa-tachometer"></i>
                                <span class="title">Dashboard</span>
                                <!--<span class="arrow"></span>-->
                            </a> 
                        </li>
			<?php }else if($loggedUser['User']['active']=='1'){ ?>
                        <li class="nav-item">			    
                            <a href="<?php echo SITE_URL;?>/users/profile" class="nav-link nav-toggle">
                                <i class="fa fa-tachometer"></i>
                                <span class="title">Dashboard</span>
                                <!--<span class="arrow"></span>-->
                            </a>
			</li>			
							<?php } ?>
  
                        <li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/groups/index" class="nav-link nav-toggle">
                                <i class="fa fa-users"></i>
                                <span class="title">Groups</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo SITE_URL;?>/groups/index" class="nav-link ">
                                        <span class="title">
                                            Manage Groups</span>
                                    </a>
                                </li><?php if($loggedUser['User']['autoresponders']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/responders/index" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Auto Responders</span>
										<!--<span class="arrow"></span>-->
									</a>     
								</li> <?php } ?>                              
                            </ul>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/contacts/index" class="nav-link nav-toggle">
                                <i class="fa fa-user"></i>
							
                                <span class="title">Contacts</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo SITE_URL;?>/contacts/index" class="nav-link ">
                                        <span class="title">Manage Contacts</span>
                                    </a>
                                </li>  <?php if($loggedUser['User']['importcontacts']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/contacts/upload" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Import Contacts</span>

									</a>
								</li> <?php } ?>  
                                <!--<li class="nav-item  ">
					<a href="<?php echo SITE_URL;?>/contacts/unsubscribers" class="nav-link nav-toggle">
						<i class=""></i>
						<span class="title">Unsubscribers</span>

					</a>
				</li> -->                        
                            </ul>
                        </li>

                        <li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/messages/send_message" class="nav-link nav-toggle">
                                <i class="fa fa-comment-o"></i>
							
                                <span class="title">Messages</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo SITE_URL;?>/messages/send_message" class="nav-link ">
                                        <span class="title">Send Bulk SMS</span>
                                    </a>
                                </li>  
                                                                <li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/messages/schedule_message" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Groups Message Queue</span>

									</a>
								</li>     
                                                                <li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/messages/singlemessages" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Contacts Message Queue</span>

									</a>
								</li>     
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/messages/template_message" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Message Templates</span>

									</a>
								</li>     
                                                                <?php if((BITLY_USERNAME !='') && (BITLY_API_KEY !='')){ ?>
<?php if($loggedUser['User']['shortlinks']=='1'){ ?>
                                <li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/users/shortlinks" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">Short Links</span>

									</a>
								</li> 
                                
                                                                <?php }} ?>  
                                 <!--<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/chats" class="nav-link nav-toggle">
										<i class=""></i>
										<span class="title">SMS Chat</span>

									</a>
								</li>  -->
                                 
                            </ul>
                        </li>

                        <li class="nav-item  ">
					<a href="<?php echo SITE_URL;?>/chats" class="nav-link nav-toggle">
						<i class="fa fa-commenting-o"></i>
						<span class="title">SMS Chat&nbsp;&nbsp;<span class="badge badge-danger"><?php echo $unreadTextMsg;?></span></span>

					</a>
			</li>  
                        
                        <?php if(API_TYPE !=2){ ?> 
                        <?php if($loggedUser['User']['voicebroadcast']=='1'){ ?>                       
                        <li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/groups/broadcast_list" class="nav-link nav-toggle">
                                <i class="fa fa-bullhorn"></i>
                                <span class="title">Voice Broadcast</span>

                            </a>     
			</li>
                        <?php }} ?>

                        

			    <li class="nav-item  ">
                            <?php if($loggedUser['User']['polls']=='1'){ ?> 
                            <a href="<?php echo SITE_URL;?>/polls/question_list" class="nav-link nav-toggle">
                            <?php } else{ ?>
                            <a href="" class="nav-link nav-toggle">
                            <?php } ?>
                                <i class="fa fa-wrench"></i>
                                <span class="title">Tools</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
								<?php if($loggedUser['User']['polls']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/polls/question_list" class="nav-link nav-toggle">
										<span class="title">Polls</span>

									</a>     
								</li><?php } ?>
                                                                <?php if($loggedUser['User']['contests']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/contests/index" class="nav-link nav-toggle">
										<span class="title">Contests</span>

									</a>     
								</li><?php } ?>
                                                                <?php if($loggedUser['User']['loyaltyprograms']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/smsloyalty/index" class="nav-link nav-toggle">
										<span class="title">SMS Loyalty Programs</span>

									</a>     
								</li> <?php } ?> 
                                                                <?php if($loggedUser['User']['kioskbuilder']=='1'){ ?>
                                                                <li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/kiosks/index" class="nav-link nav-toggle">
										<span class="title">Kiosk Builder</span>

									</a>     
								</li><?php } ?>
                                                                <?php if($loggedUser['User']['birthdaywishes']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/birthday/index" class="nav-link nav-toggle">
										<span class="title">Birthday SMS Wishes</span>

									</a>     
								</li><?php } ?>
                                                                <?php if($loggedUser['User']['mobilepagebuilder']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/mobile_pages/pagedetails" class="nav-link nav-toggle">
										<span class="title">Mobile Page Builder</span>

									</a>     
								</li><?php } ?>
                                                                <?php if($loggedUser['User']['webwidgets']=='1'){ ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/weblinks/index" class="nav-link nav-toggle">
										<span class="title">Web Sign-up Widgets</span>

									</a>     
								</li><?php } ?>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/users/qrcodeindex" class="nav-link nav-toggle">
										<span class="title">QR Codes</span>

									</a>     
								</li>
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/messages/nongsm" class="nav-link nav-toggle nyroModal">
										<span class="title">Non-GSM Character Checker</span>

									</a>     
								</li>							
                            </ul>
                        </li>
						<li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/logs/index" class="nav-link nav-toggle">
                                <i class="fa fa-file-text-o"></i>
                                <span class="title"> Logs   </span>

                            </a>     
						</li>
						<li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/users/reports" class="nav-link nav-toggle">
                                <i class="fa fa-bar-chart"></i>
                                <span class="title"> Reports   </span>

                            </a>     
						</li>
						<li class="nav-item  ">
                           <a href="<?php echo SITE_URL;?>/users/affiliates" class="nav-link nav-toggle">
                                <i class="fa fa-sitemap"></i>
                                <span class="title">Affiliates</span>
                                <span class="arrow"></span>
                            </a>
							<ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo SITE_URL;?>/users/affiliates" class="nav-link ">
                                        <span class="title">
                                            Affiliate URLs</span>
                                    </a>
                                </li> 
								<li class="nav-item  ">
									<a href="<?php echo SITE_URL;?>/referrals/index" class="nav-link nav-toggle">
										<span class="title"> Referrals </span>

									</a>     
								</li>                               
                            </ul>
                        </li>
						<li class="nav-item  ">
                            <a href="<?php echo SITE_URL;?>/users/logout" class="nav-link nav-toggle">
                                <i class="fa fa-sign-out"></i>
                                <span class="title"> Logout  </span>

                            </a>     
						</li>
					</ul>	
                </div>
            </div>			
				<!--h4><span>Statistics</span></h4>
				<div class="sidebarbox">
					<p>Primary Number: <b><?php echo $loggedUser['User']['assigned_number'];?></b>
					<?php if(API_TYPE==1){?>
					Number List: (<b><?php echo $this->Html->link('Number List',array('controller' =>'users', 'action' =>'numberlist_nexmo'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
					Timezone: <b><?php echo $loggedUser['User']['timezone'];?></b>
					<?php }else{?>
					Number List: (<b><?php echo $this->Html->link('Number List',array('controller' =>'users', 'action' =>'numberlist_twillio'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
					Timezone: <b><?php echo $loggedUser['User']['timezone'];?></b>
					
					<?php } ?></p>
					Referred Users (activated/paid): <b><?php echo $statistic['referredUser'];?></b><br/>
					Overall Credited Commissions: <b>
				<?php 
						   $currencycode=PAYMENT_CURRENCY_CODE;
							
						   if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>
							  
							  $<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='JPY'){ ?>
							  ¥<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='EUR'){ ?>
							  €<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='GBP'){ ?>
							  £<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
							  kr<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='CHF'){ ?>
							  CHF<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='BRL'){ ?>
							  R$<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='PHP'){ ?>
							  ₱<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php } else if($currencycode=='ILS'){ ?>
							  ₪<?php echo $statistic['overAllCredit'];?></b><br/>
							  <?php }?>
					
					Unpaid Commissions: <b>
					<?php
					 if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD'){?>             
							  $<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='JPY'){ ?>
							  ¥<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='EUR'){ ?>
							  €<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='GBP'){ ?>
							  £<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK'){ ?>
							  kr<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='CHF'){ ?>
							  CHF<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='BRL'){ ?>
							  R$<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='PHP'){ ?>
							  ₱<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php } else if($currencycode=='ILS'){ ?>
							  ₪<?php echo $statistic['unPaidCommision'];?></b><br/><br/>
							  <?php }?>
				<?php if($loggedUser['User']['email_alert_credit_options']==0 && $loggedUser['User']['sms_balance'] <= $loggedUser['User']['low_sms_balances']){?>		
						SMS Credits: <b style="color:red;"> <?php echo $loggedUser['User']['sms_balance']?></b><br>		
						<?php }else{?>
						SMS Credits:	<b><?php echo $loggedUser['User']['sms_balance']?></b><br>
						<?php } ?>
						<?php if($loggedUser['User']['email_alert_credit_options']==0 && $loggedUser['User']['voice_balance'] <= $loggedUser['User']['low_voice_balances']){?>		
						Voice Credits: <b style="color:red;"> <?php echo $loggedUser['User']['voice_balance']?></b><br>		
						<?php }else{?>
							Voice Credits:	<b><?php echo $loggedUser['User']['voice_balance']?></b><br>
						<?php } ?>
						<br/>
						Next Renewal Date: 
						<?php 
						$date=strtotime($loggedUser['User']['next_renewal_dates']); 
						list($year, $month, $day) = explode('-', $loggedUser['User']['next_renewal_dates']); 
						if (checkdate($month,$day,$year)){?>			
						(<?php echo $datereplace=date('Y-m-d',$date);?>)
						<?php } else { ?>
						<?php echo "(<font style='color: green'><b>None</b></font>)"; }?>			
						<?php ?>
						<br/>		
						Past Receipts: (<b><?php echo $this->Html->link('Past Receipts',array('controller' =>'users', 'action' =>'invoices'), array('class' => 'nyroModal', 'style' => 'color:#12759E;'));?></b>)
						<br/>
						<br/>
					<?php 
							 $payment=PAYMENT_GATEWAY;			
							if($payment=='1'){?>			
							Purchase Credits <?php echo $this->Html->link(__('PayPal', true), array('controller' =>'users', 'action' =>'paypalpayment'),array('class' => 'paypalpayment'))?><br />
							<?php }else if($payment=='2'){ ?>			
							Purchase Credits <?php echo $this->Html->link('2Checkout', array('controller' =>'users', 'action' =>'checkoutpayment'),array('class' => 'checkoutpayment'))?><br />
							<?php }else if($payment=='3'){ ?>
							Purchase Credits <?php echo $this->Html->link('PayPal', array('controller' =>'users', 'action' =>'paypalpayment'),array('class' => 'paypalpayment'))?> or <?php echo $this->Html->link('2Checkout', array('controller' =>'users', 'action' =>'checkoutpayment'),array('class' => 'checkoutpayment'))?><br />
							<?php } ?>
				<br/>
				<font color="red">ATTENTION:</font> Web developers, utilize our incredibly simple 
						<b><?php echo $this->Html->link(__('PHP API', true), array('controller' => 'twilios','action' => 'apibox', $loggedUser['User']['id']), array('class' => 'nyroModal', 'style' => 'color:#12759E;')); ?>	</b>
				</div-->
           <?php endif;?>