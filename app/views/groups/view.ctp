<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Groups</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<span>Manage</span>
					</li>
				</ul>  
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn btn-fit-height grey-salt dropdown-toggle" type="button"> Actions
							<i class="fa fa-angle-down"></i>
						</button>
						<ul role="menu" class="dropdown-menu pull-right">
							<li>
							<?php
								$navigation = array(					  	
								'Back' => '/groups/index',);										
								$matchingLinks = array();				
								foreach ($navigation as $link){
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
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-qrcode font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"> <?php __('QR Code Generated');?></span>
					</div>
				</div>                           
					<div class="portlet light">
						<div>
							<?php 
								if(isset($qrimage)){
								//echo "Qr generated..";
								echo "<img src='".$qrimage."' style='border-color: #d3d3d3; border-style: solid; border-width: 3px'/>";
								}
							?>
							<div class="feildbox">
								<i>Scanning this QR code will subscribe the number to the group</i>
							</div>								
						</div>
							<?php $qrimageimage="<img src='".$qrimage."'/>";?> 
                                <div class="portlet-body">
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;"><div style=" overflow: hidden; width: auto;" class="scroller" data-initialized="1">
                                        <h4>Copy and paste the below HTML to your website in order to display the QR code above:</h4>
                                        <p> <?php echo $this->Form->input('Preview', array('type' => 'textarea','class'=>'form-control', 'escape' => false,'label'=>false,'div'=>false,'id'=>"message",'readonly'=>true,'value'=>$qrimageimage)); ?> </p>
                                    </div></div>
                                </div>
                    </div>
               </div>
                          