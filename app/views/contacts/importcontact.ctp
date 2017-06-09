<script>
 function confirmfrmSubmit()
{ 
var a=document.getElementById('group_id').value;	

//alert(a);

if(a==''){
alert("Please select a group");
return false;
		
}


}
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title">Import Contacts</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li><span> Import Contacts </span></li>
				</ul>  			
			</div>			
			<div class="clearfix"></div>
							<div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="fa fa-upload font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">Import Contacts</span>
                                    </div>
                                    
                                </div>
								  <?php echo $this->Session->flash(); ?>	
                                <div class="portlet-body form">
                                   <?php echo $this->Form->create('Contact',array('action'=> 'importcontact','name'=>'loginForm','id'=>'loginForm','enctype'=>'multipart/form-data','method'=>'post','onSubmit' => 'return confirmfrmSubmit();'));?>
                                        <div class="form-body">
                                           
                                            <div class="form-group">
                                                <label>Group Name</label>
                                                <?php 
				
				echo $this->Form->input('Group.id', array(
				'div'=>false,
				'label'=>false,				
				'class'=>'form-control input-large',
				'default'=>0,
				'id'=>'group_id',
				
				'multiple'=>true,
				'options' => $Group));
	
				?>
                                            </div>
                                            
                                        </div>
                                        <div class="form-actions">
                                           
											<?php echo $this->Form->submit('Next',array('div'=>false,'class'=>'btn blue'));?>
                                            
                                        </div>
                                   <?php echo $this->Form->end(); ?>
			 
                                </div>
                            </div>
						</div>
					</div>
                         