<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a name"
                });jQuery("#phoneno").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter phoneno"
                });jQuery("#phoneno").validate({
                    expression: "if (VAL.match(/^[0-9]*$/) && VAL) return true; else return false;",
                    message: "Please enter a valid integer"
                });
				
				
            });
            /* ]]> */			
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
  }
  )
});
</script>
<script>


var count = "127";
function update(){
var tex = $("#message").val();

var msg = $("#GroupAutoMessage").val();



var count1 = (127-(msg.length));

tex = tex.replace('{','');
tex = tex.replace('}','');
tex = tex.replace(':','');
tex = tex.replace('[','');
tex = tex.replace(']','');
tex = tex.replace('~','');
tex = tex.replace(';','');
tex = tex.replace('`','');
tex = tex.replace("'","");
tex = tex.replace('"','');
var len = tex.length;




//var message = $("#Preview").val();
//var lenth = msg.length;


$("#message").val(tex);
if(len > count){
tex = tex.substring(0,count1);
//$("#message").val(tex);
//$("#Preview").val(tex+msg);
return false;
}

//var alert1=(count1-len)
//alert(alert1);

$("#limit").val(count-len);

//$("#Preview").val(tex+msg) ;
}
function update1(){


var tex = $("#groupname").val();
//alert(tex);


//tex = tex.replace(/[^a-zA-Z 0-9]+/g,'');
tex = tex.replace('{','');
tex = tex.replace('`','');
tex = tex.replace('}','');
tex = tex.replace(':','');
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
			</div>			
			<div class="clearfix"></div>
			<div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">  QR Codes</span>
                                    </div>
                                    <!--div class="actions">
                                        <div class="btn-group">
                                            <a class="btn btn-sm green dropdown-toggle" href="javascript:;" data-toggle="dropdown"> Actions
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a href="javascript:;">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;">
                                                        <i class="fa fa-trash-o"></i> Delete </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;">
                                                        <i class="fa fa-ban"></i> Ban </a>
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                    <a href="javascript:;"> Make admin </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div-->
                                </div>
                                <div class="portlet-body form">
                                    <?php echo $this->Form->create('Group',array('action'=> 'qrcodes/'.$groupname.'/'.$keyword.'/'.$id.''));?>
                                        <div class="form-body">
                                            
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Group Name</label>
                                                <div class="input-group">
                                                    
													<?php echo $this->Form->input('Group.group_name',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'groupname','onKeyup'=>'return update1()','value'=>$groupname,'readonly'=>true))?>
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user font-red"></i>
                                                    </span>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label for="exampleInputPassword1">Name</label>
                                              <div class="input-group">
                                                    
													<?php echo $this->Form->input('Contact.name',array('div'=>false,'label'=>false, 'class' => 'form-control','value'=>'','id'=>'name'))?>
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user font-red"></i>
                                                    </span>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label for="exampleInputPassword1">Phone Number</label>
                                              <div class="input-group">
                                                    
													<?php echo $this->Form->input('Contact.phoneno',array('div'=>false,'label'=>false, 'class' => 'form-control','value'=>'','id'=>'phoneno'))?>
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-phone font-red"></i>
                                                    </span>
                                                </div>
                                            </div>
                                           
                                       
                                          
                                           	<?php echo $this->Form->submit('Save',array('div'=>false,'class'=>'btn blue'));?>
                                       
                                   <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
               </div>
               </div>
                          