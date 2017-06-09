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
            jQuery(function(){
			 jQuery("#message1").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a question"
                });jQuery("#optiona").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter option A"
               
                });
				jQuery("#optionb").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter option B"
               
                });
				jQuery("#optionc").validate({
                    expression: "if (VAL) return true; else return false;",
                    message:"Please enter option C"
               
                });
				jQuery("#optiond").validate({
                    expression: "if (VAL) return true; else return false;",
                    message:"Please enter option D"
               
                });
				
				jQuery("#code1").validate({
                    expression: "if (VAL) return true; else return false;",
                    message:"Please enter  a code "
               
                });
				
				;jQuery("#code1").validate({
                    expression: "if (VAL.length > 4 && VAL) return true; else return false;",
                    message: "Enter atleast 5 character code"
				
				});
				
				jQuery("#autoreply").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a autoreply message"
                });
				
            });		
</script>
<script>
var count = "50";
function update(){
var tex = $("#message1").val();
var msg = $("#Preview1").val();
/*var firstnamefound = 0;
var lastnamefound = 0;
if(tex.indexOf("%First_Name%") != -1){
			firstnamefound = 1;
		}
		if(tex.indexOf("%Last_Name%") != -1){
			lastnamefound = 1;
		}*/
tex = tex.replace('{','');
tex = tex.replace('}','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace("'",'');
tex = tex.replace('`','');
tex = tex.replace('"','');
//texcount = tex.replace('%First_Name%','');
//texcount = texcount.replace('%Last_Name%','');
var len = tex.length;
		/*if(firstnamefound){
			len = len + parseInt($("#firstName").val());
		}
		
		if(lastnamefound){
			len = len + parseInt($("#lastName").val());
		}*/
		//console.log('length:'+len);
 var count1 = (50-(len));
//console.log('count1:'+count1);
//var message = $("#Preview1").val();
//var lenth = message.length;
//alert(lenth);
$("#message1").val(tex);
if(len > count){
tex = tex.substring(0,count1);
//$("#Preview1").val(tex);
return false;
}
$("#limit2").val(count1);
//$("#Preview1").val(tex);
}
</script>
<script>
	var count = "160";
	function update1(id){
		var tex = $('#'+id).val();
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
		 var count1 = (160-(len));
		$("#"+id).val(tex);
		if(len > count){
		tex = tex.substring(0,count1);
		return false;
		}
	}
</script>
<script>
	var count = "160";
	function update12(){
		var tex = $("#autoreply").val();
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
		 var count1 = (160-(len));
		$("#autoreply").val(tex);
		if(len > count){
		tex = tex.substring(0,count1);
		return false;
		}
	}
</script>

<style>
.ValidationErrors{
color:red;
margin-bottom: 10px;
 float:right;
width:100%; 
 
}
</style>
	<div class="page-content-wrapper">
		<div class="page-content">              
			<h3 class="page-title"> <?php __('Polls');?></h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li><a href="<?php echo SITE_URL;?>/polls/question_list">Polls</a></li>
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
									' Back' => '/polls/question_list',
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
				</div>	-->			
			</div>		
			<div class="clearfix"></div>			
			<?php echo $this->Session->flash(); ?>	
			<div class="portlet mt-element-ribbon light portlet-fit ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
edit poll form
</div>
				<div class="portlet-title">
					<div class="caption font-red-sunglo">
						<i class="fa fa-list-ol font-red-sunglo"></i>
						<span class="caption-subject bold uppercase"> </span>
					</div>
				</div>
				<div class="portlet-body form">
				   <?php echo $this->Form->create('Poll',array('action'=> 'edit/'.$id));?>
					<div class="form-body">
						<div class="form-group">
							<label>Poll Question<span class="required_star">*</span></label>							
							<?php echo $this->Form->textarea('Question.question',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'message1','maxlength'=>'50','onKeyup'=>'return update()','value'=>$questionedits['Question']['question']))?>
						</div>
						<div class="form-group">
						<label>Remaining Characters</label>
							<input type=text name=limit2 id=limit2 class="form-control input-xsmall" size=4 readonly value="50">
						</div>
						<div class="form-group">
							<label>Option A <span class="required_star">*</span></label>
							<div class="input">
							 <input type="text" name="data[Option][optiona][]" class='form-control' id="optiona" class="inputtext" maxlength="20" value="<?php  echo  $questionedits['Option'][0]['optiona']?>">						 
							</div>
						</div>
						<div class="form-group">
							<label> Autoresponder message Option A <!--<a rel="tooltip" title="Please enter Autoresponder message Option A" class="ico" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Autoresponder message Option A will get sent after a subscriber votes A" data-original-title="Autoresponder A" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
</label></label>
							<div class="input">
								<input type="text" name="data[Option][autorsponder_message][]" class='form-control' maxlength="160" value="<?php  echo  $questionedits['Option'][0]['autorsponder_message']?>" id="autoresponder1" onKeyup='return update1("autoresponder1")'>							
							</div>
						</div>
						<div class="form-group">							
								<label>Option B <span class="required_star">*</span></label>
							<div class="input">						
								<input type="text" name="data[Option][optiona][]"id="optionb" class='form-control' maxlength="20"  value="<?php echo $questionedits['Option'][1]['optiona']?>">
							</div>
						</div>
						<div class="form-group">
							<label> Autoresponder message Option B <!--<a rel="tooltip" title="Please enter Autoresponder message Option B" class="ico" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>	-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Autoresponder message Option B will get sent after a subscriber votes B" data-original-title="Autoresponder B" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label></label>
							<div class="input">							
								<input type="text" name="data[Option][autorsponder_message][]" class='form-control' maxlength="160" value="<?php  echo  $questionedits['Option'][1]['autorsponder_message']?>" id="autoresponder2" onKeyup='return update1("autoresponder2")'>
							</div>
						</div>
						<div class="form-group">
							<label>Option C <span class="required_star">*</span></label>
							<div class="input">						
								<input type="text" name="data[Option][optiona][]" id="optionc" class='form-control' maxlength="20"  value="<?php echo  $questionedits['Option'][2]['optiona']?>">
							</div>
						</div>
						<div class="form-group">
							<label> Autoresponder message Option C <!--<a rel="tooltip" title="Please enter Autoresponder message Option C" class="ico" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>	-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Autoresponder message Option C will get sent after a subscriber votes C" data-original-title="Autoresponder C" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label></label>
							<div class="input">							
								<input type="text" name="data[Option][autorsponder_message][]" class='form-control' maxlength="160" value="<?php  echo  $questionedits['Option'][2]['autorsponder_message']?>" id="autoresponder3" onKeyup='return update1("autoresponder3")'>
							</div>
						</div>
						<div class="form-group">
							<label>Option D<span class="required_star">*</span></label>
							<div class="input">						
								<input type="text" name="data[Option][optiona][]" id="optiond" class='form-control' maxlength="20"  value="<?php echo $questionedits['Option'][3]['optiona']?>">
							</div>
						</div>
						<div class="form-group">
							<label>Autoresponder message Option D <!--<a rel="tooltip" title="Please enter Autoresponder message Option D" class="ico" href="#" title="help"><i class="fa fa-question-circle" style="font-size:18px"></i></a>	-->
<a href="javascript:;" data-container="body" data-trigger="hover" data-content="Autoresponder message Option D will get sent after a subscriber votes D" data-original-title="Autoresponder D" data-original-title="Autoresponder D" class="popovers"> <i class="fa fa-question-circle" style="font-size:18px"></i> </a>
							</label>
							<div class="input">						
								<input type="text" name="data[Option][autorsponder_message][]" class='form-control' maxlength="160" value="<?php  echo  $questionedits['Option'][3]['autorsponder_message']?>" id="autoresponder4" onKeyup='return update1("autoresponder4")'>
							</div>
						</div>
						<div class="form-group">
						<label for="some21">Auto-reply Message<span class="required_star">*</span>&nbsp;<a href="javascript:;" data-container="body" data-trigger="hover" data-content="The auto-reply message is the default message that gets sent only if you do not have an autoresponse for any of the specific options above" data-original-title="Auto-reply Message" class="popovers"><i class="fa fa-question-circle" style="font-size:18px"></i></a></label>  
						<?php echo $this->Form->textarea('Question.autoreply_message',array('div'=>false,'label'=>false,'class'=>'form-control','id'=>'autoreply','maxlength'=>'160','onKeyup'=>'return update12()','value'=>$questionedits['Question']['autoreply_message']))?>   
						</div>
					</div>
					<div class="form-actions">						
						<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
		   </div>       
		</div>
	</div> 
