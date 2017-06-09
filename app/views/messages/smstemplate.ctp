<script>
            jQuery(function(){
			 jQuery("#message1").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter template Message"
                });jQuery("#KeywordId").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Group"
               
                });				
				jQuery("#messagename").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter template name"              
                });				
            });		
</script>
<script type="text/javascript">
	$(document).ready(function (){
		$('textarea[maxlength]').live('keyup change', function() {
			var str = $(this).val()
			var mx = parseInt($(this).attr('maxlength'))
			if (str.length > mx) {
			$(this).val(str.substr(0, mx))
			return false;
			}
	  })
	});
</script>

	<script>
	var count = "1588";
	function update(){
		var tex = $("#message1").val();
		var msg = $("#Preview1").val();
		tex = tex.replace('[','');
		tex = tex.replace(']','');
		tex = tex.replace('~','');
		tex = tex.replace(';','');
		tex = tex.replace('`','');
		tex = tex.replace('"','');
		var len = tex.length;
		var count1 = (1588-(len));
		$("#message1").val(tex);
		if(len > count){
		tex = tex.substring(0,count1);
		$("#Preview1").val(tex);
		return false;
		}
		$("#limit2").val(count1);
		$("#Preview1").val(tex);
	}
	</script>
<style>

.feildbox img {
    border: none;
    width: auto;
}
.ValidationErrors{
color:red;
}
.showmessage{
 float: right;
    height: 482px;
    margin-right: 0;
    width: 273px;
}
</style>
         <?php  $group_sms_id=$this->Session->read('groupsmsid');?>
		<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> <?php __('Message Templates');?></h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i><a href="<?php echo SITE_URL;?>/users/dashboard"> Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
                                                         <a href="<?php echo SITE_URL;?>/messages/template_message">Message Templates</a>
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
										'Create Message Template' => '/messages/smstemplate',
										' View Message Templates' => '/messages/template_message',
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
										$sms_balance=$this->Session->read('User.sms_balance');
										$assigned_number=$this->Session->read('User.assigned_number');
										$active=$this->Session->read('User.active');
									?>	
								</li>
								
							</ul>
						</div>
					</div>	-->			
				</div>
				<?php echo $this->Session->flash(); ?>	
				<div class="portlet mt-element-ribbon light portlet-fit  ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create message template form
</div>
					<div class="portlet-title">
						<div class="caption font-red-sunglo">
							<i class="fa fa-clone font-red-sunglo"></i>
							<span class="caption-subject bold uppercase"> </span>
						</div>                                   
					</div>
					<div class="portlet-body form">
						<?php echo $this->Form->create('Message',array('action'=> 'smstemplate'));?>
							<div class="form-body">
                                                                <div class="showmessage">
									<div style="float: right; margin-top: 0px; margin-right: 26px; width: 227px;"
									class="feildbox"><label>Preview</label>
										<?php echo $this->Form->input('Message.message_template', array('type' => 'textarea', 'cols'=>'15','rows'=>'12','class'=>'form-control','escape' => false,'label'=>false,'div'=>false,'id'=>"Preview1",'readonly'=>true,'style'=>'font-size:20px','data-role'=>'none')); ?>
									</div>
								</div>
								<div class="form-group">
									<label>Template</label>
									<!--<div class="input-group">  -->    
										<?php echo $this->Form->input('Message.messagename', array('label'=>false,'class'=>'form-control input-xlarge','div'=>false,'id'=>"messagename")); ?>
									<!--</div>-->
								</div>                                           
								<div class="form-group">
									<label>Message</label>
								   <?php echo $this->Form->textarea( 'Message.message_template',array('div'=>false,'label'=>false,'class' => 'form-control input-xlarge','id'=>'message1','maxlength'=>'1588','onKeyup'=>'return update()'))?>
								</div>
								<div id='counter'>Remaining Characters
									<input type=text class="form-control input-xsmall" name=limit2 id=limit2 size=4 readonly value="1588">
								</div>
									Special characters not allowed such as ~ [ ] ; "											
							</div>
							<div class="form-actions">
							   <?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
				
			</div>
		</div>
		<script type="text/javascript">
			$('#sent_on').datetimepicker({
				 minDate: 0,
				showSecond: false,
				//showMinute: false,
				dateFormat: 'dd-mm-yy',
				//timeFormat: 'hh',
				timeFormat: 'hh:mm',
				stepHour: 1,
				stepMinute: 5,
				stepSecond: 10
			});
		</script>