<div class="page-content-wrapper">
	<div class="page-content">  <?php __('QR Code Generated');?>            
		<h3 class="page-title"> 
			<small> </small>
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span><?php __('QR Code Generated');?></span>
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
								'Back' => '/users/qrcodeindex',
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
		<?php $qrimageimage="<img src='".$qrimage1."'/>";?>
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="icon-settings font-red-sunglo"></i>
					<span class="caption-subject bold uppercase">  <?php __('QR Code Generated');?></span>
				</div>
			</div>	
			<div class="portlet-body form">
				<div class="form-group">
					<label style="font-size:14px;font-weight:normal;"><b>Copy and paste the below HTML to your website in order to display the QR code on the left: </b></label>
					<?php echo $this->Form->input('Preview', array('type' => 'textarea', 'escape' => false,'label'=>false,'div'=>false,'id'=>"Preview1",'readonly'=>true,'class'=>'form-control','value'=>$qrimageimage)); ?>
				</div>
				<div style="padding-left:43px;padding-top: 22px;">
					<?php 
					if(isset($qrimage1)){
					echo "<img src='".$qrimage1."' style='border-color: #659cd0; border-style: solid; border-width: 3px'/>";
					}
					?>
				</div>
				<!--div class="feildbox">
					<label> Mandatory Appended Message</label>
					<?php// echo $this->Form->input('Group.auto_message', array('type' => 'textarea', 'cols' => 46,'rows'=>'4','style'=>'width:295px; border: 1px solid #BA3B11;border-radius: 5px 5px 5px 5px;','escape' => false,'label'=>false,'value'=>'STOP to end. Msg&Data rates apply','readonly'=>true)) ?>

				</div-->
			</div>
		</div>           
	</div>
</div>