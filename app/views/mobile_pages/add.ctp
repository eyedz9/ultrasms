<script>
$(function() {    
       //use this method to add new colors to pallete
       //$.fn.colorPicker.addColors(['000', '000', 'fff', 'fff']);
       $('#headerColor').colorPicker();
       $('#headerfontColor').colorPicker();
       $('#bodybgColor').colorPicker();
       $('#footerColor').colorPicker();
       $('#footertextColor').colorPicker();
       $('#fontColor').colorPicker();
      });
</script>
<script>
            jQuery(function(){
			 jQuery("#title").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter title"
                });
            });		
</script>
<script>
function openiframe(id){
   document.getElementById('welcomeDiv').style.display = "block";
}
function getValue (){
if ( CKEDITOR.instances.editor_office2003.getData() == '' ){
    alert( 'Please enter description' );
	return false;
} 
 }
</script>
<style>
.ValidationErrors{
color:red;
}
</style>
<?
echo $this->Html->css('colorPicker');
echo $this->Html->script('jquery.colorPicker');
App::import('Helper', 'Fck');
$this->Fck = new FckHelper(); 
echo $this->Html->script('ckeditor/ckeditor');
?>
	<div class="page-content-wrapper">
			<div class="page-content">              
				<h3 class="page-title"> Mobile Splash Pages</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="icon-home"></i>
						<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo SITE_URL;?>/mobile_pages/pagedetails">Mobile Splash Pages</a>
						</li>
					</ul>  			
				</div>			
				<div class="clearfix"></div>
					<?php echo $this->Session->flash(); ?>
					<div class="portlet mt-element-ribbon light portlet-fit">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
create mobile splash page form
</div>
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="fa fa-paint-brush font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase"> </span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <?php echo $this->Form->create('MobilePage',array('action'=> 'add','enctype'=>'multipart/form-data','id'=>'pageadd','onsubmit'=>'return getValue();'));?>									
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <div class="input">                                                  
                                                   <?php echo $this->Form->input('MobilePage.title',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'title'))?>
												</div>
                                            </div>
											<div class="form-group">
                                                <label>Header Logo</label>
                                                <div class="input">
                                                   <input type="file" name="data[MobilePage][header_logo]" id="file">
												</div>
                                            </div>
											<div class="form-group">
                                                <label>Header Background Color</label>
                                                <div class="input">
                                                   <input style="display: none;" name="data[MobilePage][header_color]" id="headerColor" value="#ff0000" type="text">
												</div>
                                            </div>
											<div class="form-group">
                                                <label>Header Text Color</label>
                                                <div class="input">
                                                  <input style="display: none;" name="data[MobilePage][headerfont_color]" id="headerfontColor" value="#000000" type="text">
												</div>
                                            </div>  
											<div class="form-group">
												<label for="exampleInputFile1">Upload Image</label></br>
												<span class="btn blue  btn-outline">
													<a href="#null" onclick="return openiframe();">Click Here</a>
													</span>
													<div id="welcomeDiv" style="float:left;display:none; width:100%" class="dz-default dz-message">
														<iframe style="width:100%;height:auto;border:0" src="<?php echo SITE_URL;?>/mobile_pages/upload">
														</iframe>
													</div>

											</div>											
                                            <div class="form-group">
                                                <label>Description</label>
                                               <?php echo $this->Form->textarea('MobilePage.description',array('div'=>false,'label'=>false,'class'=>'ckeditor','id'=>'editor_office2003'))?>
                                            </div>
											<div class="feildbox controlset">
												<label>Body Background color<span class="required_star"></span></label>
												<input style="display: none;" name="data[MobilePage][bodybg_color]" id="bodybgColor" value="#ffffff" type="text">
											</div>	
											 <div class="form-group">
                                                <label>Google Maps HTML Embed Code</label>
                                                <div class="input">
                                                   <?php echo $this->Form->input('MobilePage.mapurl',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'mapurl'))?>
												</div>
                                            </div>
											<div class="form-group">
                                                <label>Footer Text</label>
                                                <div class="input">
                                                    <?php echo $this->Form->input('MobilePage.footertext',array('div'=>false,'label'=>false, 'class' => 'form-control','id'=>'footertext'))?>
												</div>
                                            </div>
											<div class="form-group">
                                                <label>Footer Background Color</label>
                                                <div class="input">
                                                  <input style="display: none;" name="data[MobilePage][footer_color]" id="footerColor" value="#ff0000" type="text">
												</div>
                                            </div> 
											<div class="form-group">
                                                <label>Footer Text Color</label>
                                                <div class="input">
                                                  <input style="display: none;" name="data[MobilePage][footertext_color]" id="footertextColor" value="#000000" type="text">
												</div>
                                            </div> 
                                            
                                        </div>
                                        <div class="form-actions">
    
												<?php echo $this->Form->submit('Create Page',array('div'=>false,'class'=>'btn blue'));?>
			<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'mobile_pages','action' => 'pagedetails'),array('class'=>'btn default')); ?>
                                        </div>
                                    	<?php echo $this->Form->end(); ?>
                                </div>
                            </div>    </div>
                            </div>