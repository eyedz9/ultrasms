<style>
.ValidationErrors{
color:red;
}
</style>

<script>
jQuery(function(){
	jQuery("#groupname").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter sms contest name"
	});jQuery("#username").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter username"
	});jQuery("#keyword").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter sms contest keyword"
	});jQuery("#message").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter message"
	});jQuery("#systemmessage").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter sms contest message"
	});;jQuery("#username").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter username"
	});
});		
</script>
<script type="text/javascript">
$(document).ready(function (){
	$('textarea[maxlength]').live('keyup change', function() {
		var str = $(this).val()
		var mx = parseInt($(this).attr('maxlength'))
		if (str.length > mx){
			$(this).val(str.substr(0, mx))
			return false;
		}
	})
});
</script>
<script>
var count = "160";
function update(){
	var tex = $("#message").val();
	var msg = $("#message").val();
	var count1 = (160-(msg.length));
	tex = tex.replace('{','');
	tex = tex.replace('}','');
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace('`','');
	tex = tex.replace("'","");
	tex = tex.replace('"','');
	var len = tex.length;
	$("#message").val(tex);
	if(len > count){
		tex = tex.substring(0,count1);
		return false;
	}
	$("#limit").val(count-len);
}
function update1(){
	var tex = $("#groupname").val();
	tex = tex.replace('{','');
	tex = tex.replace('`','');
	tex = tex.replace('}','');
	tex = tex.replace('[','');
	tex = tex.replace(']','');
	tex = tex.replace('~','');
	tex = tex.replace(';','');
	tex = tex.replace("'","");
	tex = tex.replace('"','');
	$("#groupname").val(tex);
}
</script>   
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Contests
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
						<a href="<?php echo SITE_URL;?>/contests/index">Contests</a>
					</li>
				</ul>  
				<!--<div class="page-toolbar">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
							<li>
								<?php
								$navigation = array(
								'  Contests' => '/contests/index',
								' Create Contest' => '/contests/add',
								);				
								$matchingLinks = array();
								foreach ($navigation as $link){
									if(preg_match('/^'.preg_quote($link, '/').'/', substr($this->here, strlen($this->base	)))){
										$matchingLinks[strlen($link)] = $link;
									}
								}
								krsort($matchingLinks);
								$activeLink = ife(!empty($matchingLinks), array_shift($matchingLinks));
								$out = array();
								foreach ($navigation as $title => $link){
									$out[] = '<li>'.$html->link($title, $link, ife($link == $activeLink, array('class' => 'current'))).'</li>';
								}
								echo join("\n", $out);
								?>	
                            </li>
                        </ul>
                    </div>
                </div>	-->
			</div>
			<div class="clearfix"></div>
			<div class="portlet mt-element-ribbon light portlet-fit  ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create contest form
</div>
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-trophy font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"></span>
					</div>
				</div>		
				<?php 
				if((empty($numbers_sms))&&($users['User']['sms']==0)){ ?>
					<div class="m-heading-1 border-white m-bordered">
							<h3>You need to get a SMS enabled online number to use this feature.</h3>
							<br>
							<b>Purchase Number to use this feature by </b>
							<?php  if(API_TYPE==0){
								echo $this->Html->link('Get Number', array('controller' =>'twilios', 'action' =>'searchcountry'), array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
							}else if(API_TYPE==1){
								echo $this->Html->link('Get Number', array('controller' =>'nexmos', 'action' =>'searchcountry'),array('class' => 'nyroModal' ,'style'=>'color:#ff0000;'));
							} ?>
					</div>
					 <?php 
				}else   { ?>
				 <?php echo $this->Session->flash(); ?>
				<div class="portlet-body form">
					<?php echo $this->Form->create('Contest',array('action'=> 'add'));?>
						<div class="form-body">
							<div class="form-group">
								<label>Contest Name<span class="required_star"></span></label>
									<?php echo $this->Form->input('Contest.group_name',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'groupname','onKeyup'=>'return update1()'))?>
							</div>
							<div class="form-group">
								<label>Contest Keyword <span class="required_star"></span></label>
									<?php echo $this->Form->input('Contest.keyword',array('div'=>false,'label'=>false, 'class' => 'form-control','value'=>'','id'=>'keyword'))?>
							</div>
							<div class="form-group">
								<label>Contest Auto Responder Message<span class="required_star"></label>
									<?php echo $this->Form->input('Contest.system_message',array('div'=>false,'label'=>false, 'class' => 'form-control ','id'=>'message','maxlength'=>'160','rows'=>'6','cols'=>46,'onKeyup' =>'return update()'))?>
								<div id='counter' style="margin-top:5px">Remaining Characters&nbsp;
									<script type="text/javascript">
										document.write("<input type=text  class='form-control input-xsmall' name=limit id=limit size=4 readonly value="+count+">");
									</script>
								</div>
									Special characters not allowed such as ~ { } [ ] ;
									<span id="messageErr" class="ValidationErrors"></span>
							</div>
						</div>
						<div class="form-actions">
							<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
							<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'contests','action' => 'index'),array('class'=>'btn default','style'=>'margin:0px;')); ?>
						</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
	            <?php   } ?>
	</div>
</div>						