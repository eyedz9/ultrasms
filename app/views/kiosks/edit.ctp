<style>
.padd {
  padding: 0;
}
.ValidationErrors {
    color: red;
}
</style>
<?php
echo $this->Html->css('colorPicker');
echo $this->Html->script('jquery.colorPicker');
?>
<script>
$(function() {
	$('#color').colorPicker();
	$('#background_color').colorPicker();
	$('#keypad_text_color').colorPicker();
	$('#keypad_button_color').colorPicker();
	$('#textcolor').colorPicker();
	$('#buttoncolor').colorPicker();
	$('#bottom_text_color').colorPicker();
 });
</script>
<div class="page-content-wrapper">
	<div class="page-content">              
		<h3 class="page-title"> Kiosk</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-home"></i>
				<a href="<?php echo SITE_URL;?>/users/dashboard">Dashboard</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo SITE_URL;?>/kiosks/index">Kiosks</a>
				</li>
			</ul>  			
		</div>			
		<div class="clearfix"></div>
			<?php echo $this->Session->flash(); ?>	
					<!--<div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">-->

<div class="portlet mt-element-ribbon light portlet-fit ">
<div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase">
<div class="ribbon-sub ribbon-clip ribbon-right"></div>
edit kiosk form
</div>
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="fa fa-tablet font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase"></span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <form role="form" method="post" enctype="multipart/form-data">
									<input type="hidden" id="name" name="data[Kiosk][id]" value="<?php echo $id;?>">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label>Kiosk Name</label>
                                                 <div class="input">
                                                    <input type="text" class="form-control" placeholder="Kiosk Name" id="name" name="data[Kiosk][name]" value="<?php echo $Kioskslist['Kiosks']['name'];?>"> </div>
                                            </div>
											<div class="form-group">
												<label>Loyalty Programs</label>
												<?php
												$Smsloyalty[0]='Select Loyalty Programs';
												echo $this->Form->input('Kiosk.loyalty_programs', array(
												'class'=>'form-control',
												'label'=>false,
												'default'=>$Kioskslist['Kiosks']['loyalty_id'],
												'type'=>'select',
												'options' => $Smsloyalty));
												?>
											</div>
											<hr></hr>
											<div class="form-group">
												<label style="font-size:20px;padding-bottom:10px">Background Color</label>
												<div class="input">
												   <input name="data[Kiosk][background_color]" id="background_color" value="<?php if(isset($Kioskslist['Kiosks']['background_color'])){ echo $Kioskslist['Kiosks']['background_color']; }else{ echo "#ff9900"; } ?>" type="text">
												</div>
											</div>
											<div class="form-group">
												<label for="exampleInputFile1">File Upload</label>
												<input type="file" name="data[Kiosk][file]" id="file">
											</div>
											<div class="form-group">
												<label>Style</label>
												<select class="form-control" name="data[Kiosk][style]" id="style">
													<option value="Centered" <?php if($Kioskslist['Kiosks']['style']=='Centered'){ echo "selected"; }?>>Centered</option>
													<option value="Stretched" <?php if($Kioskslist['Kiosks']['style']=='Stretched'){ echo "selected"; }?>>Stretched</option>
													<option value="Tiled" <?php if($Kioskslist['Kiosks']['style']=='Tiled'){ echo "selected"; }?>>Tiled</option>
												</select>
											</div>	
											<hr></hr>
											<div class="form-group">
												<label for="exampleInputFile1" style="font-size:20px;padding-bottom:10px">Business Logo</label>
												<input type="file" id="business_logo" name="data[Kiosk][business_logo]">
												<p class="help-block"> Image Upload. </p>
											</div><hr></hr>
											<div class="form-group">
												<label style="font-size:20px;padding-bottom:10px">Text Header</label>
												<textarea class="form-control" id="textheader" name="data[Kiosk][textheader]" rows="3"><?php if(isset($Kioskslist['Kiosks']['textheader'])){ echo $Kioskslist['Kiosks']['textheader']; }else{ echo "<strong>DEMO Loyalty Program</strong><p>You can join our program or check yourself in below</p>"; } ?></textarea>
											</div>
												
											<div class="form-group">
												<label>Alignment</label>
												<select class="form-control" id="alignment" name="data[Kiosk][alignment]">
													<option value="Center" <?php if($Kioskslist['Kiosks']['alignment']=='Center'){ echo "selected"; }?>>Center</option>
													<option value="Left" <?php if($Kioskslist['Kiosks']['alignment']=='Left'){ echo "selected"; }?>>Left</option>
													<option value="Right" <?php if($Kioskslist['Kiosks']['alignment']=='Right'){ echo "selected"; }?>>Right</option>
												</select>
											</div>	
											<div class="form-group">
												<label>Font</label>
												<select class="form-control" id="font" name="data[Kiosk][font]">
													<option value="A" <?php if($Kioskslist['Kiosks']['font']=='A'){ echo "selected"; }?>>Arial</option>
													<option value="H" <?php if($Kioskslist['Kiosks']['font']=='H'){ echo "selected"; }?>>Helvetica</option>
													<option value="T" <?php if($Kioskslist['Kiosks']['font']=='T'){ echo "selected"; }?>>Times New Roman</option>
													<option value="V" <?php if($Kioskslist['Kiosks']['font']=='V'){ echo "selected"; }?>>Verdana</option>
												</select>
											</div>	
											<div class="form-group">
												<label>Size</label>
												<select class="form-control" id="fontsize" name="data[Kiosk][fontsize]">
													<option value="8" <?php if($Kioskslist['Kiosks']['fontsize']=='8'){ echo "selected"; }?>>8</option>
													<option value="9" <?php if($Kioskslist['Kiosks']['fontsize']=='9'){ echo "selected"; }?>>9</option>
													<option value="10" <?php if($Kioskslist['Kiosks']['fontsize']=='10'){ echo "selected"; }?>>10</option>
													<option value="11" <?php if($Kioskslist['Kiosks']['fontsize']=='11'){ echo "selected"; }?>>11</option>
													<option value="12" <?php if($Kioskslist['Kiosks']['fontsize']=='12'){ echo "selected"; }?>>12</option>
													<option value="13" <?php if($Kioskslist['Kiosks']['fontsize']=='13'){ echo "selected"; }?>>13</option>
													<option value="14" <?php if($Kioskslist['Kiosks']['fontsize']=='14'){ echo "selected"; }?>>14</option>
													<option value="16" <?php if($Kioskslist['Kiosks']['fontsize']=='16'){ echo "selected"; }?>>16</option>
													<option value="18" <?php if($Kioskslist['Kiosks']['fontsize']=='18'){ echo "selected"; }?>>18</option>
													<option value="20" <?php if($Kioskslist['Kiosks']['fontsize']=='20'){ echo "selected"; }?>>20</option>
													<option value="24" <?php if($Kioskslist['Kiosks']['fontsize']=='24'){ echo "selected"; }?>>24</option>
												</select>
											</div>
											<div class="form-group">
												<label>Color</label>
												 <input name="data[Kiosk][color]" id="color" value="<?php if(isset($Kioskslist['Kiosks']['color'])){ echo $Kioskslist['Kiosks']['color']; }else{ echo "#ff9900"; } ?>" type="text">
											</div>											
                                            <div class="form-group">
                                                <label>Style</label>
                                                <div class="checkbox-inline">
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleB]" <?php if($Kioskslist['Kiosks']['styleB']==1){ echo "checked"; }?> > B </label>
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleI]" <?php if($Kioskslist['Kiosks']['styleI']==1){ echo "checked"; }?>> I</label>
                                                    <label>
                                                        <input type="checkbox" name="data[Kiosk][styleU]" <?php if($Kioskslist['Kiosks']['styleU']==1){ echo "checked"; }?>> U</label>
                                                </div>
                                            </div>
											<hr></hr>
											<div class="form-group">
<label style="font-size:20px;padding-bottom:10px">Kiosk Buttons</label>
                                                
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" <?php if($Kioskslist['Kiosks']['joinbuttons']==1){ echo "checked"; }?> id="joinbuttons" name="data[Kiosk][joinbuttons]"> Join Button </label>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" <?php if($Kioskslist['Kiosks']['punchcard']==1){ echo "checked"; }?> id="punchcard" name="data[Kiosk][punchcard]"> Punch Card </label>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="checkbox-list">
                                                    <label>
                                                        <input type="checkbox" <?php if($Kioskslist['Kiosks']['checkpoints']==1){ echo "checked"; }?> id="checkpoints" name="data[Kiosk][checkpoints]"> Check Points</label>
                                                    <label>
                                                </div>
                                            </div>
											<div class="form-group">

                                                <label>Join Button Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="joinbutton" name="data[Kiosk][joinbutton]" placeholder="Join The Program" value="<?php if(isset($Kioskslist['Kiosks']['joinbutton'])){ echo $Kioskslist['Kiosks']['joinbutton']; }else{ echo "Join The Program"; } ?>"> 
												<!--</div>-->
                                            </div>
											 <div class="form-group">
                                                <label>Punch Card Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="checkin" name="data[Kiosk][checkin]" placeholder="Check In" value="<?php if(isset($Kioskslist['Kiosks']['checkin'])){ echo $Kioskslist['Kiosks']['checkin']; }else{ echo "Check In"; } ?>" > 
												<!--</div>-->
                                            </div>
											<div class="form-group">
                                                <label>Check Points Text</label>
                                                <!--<div class="input-group">-->
                                                    <input type="text" class="form-control input-medium" id="mypoints" name="data[Kiosk][mypoints]" placeholder="My Points" value="<?php if(isset($Kioskslist['Kiosks']['mypoints'])){ echo $Kioskslist['Kiosks']['mypoints']; }else{ echo "My Points"; } ?>">
												<!--</div>-->
                                            </div>
											<div class="form-group">
												<label>Button Color</label>
												<input name="data[Kiosk][buttoncolor]" id="buttoncolor" value="<?php if(isset($Kioskslist['Kiosks']['buttoncolor'])){ echo $Kioskslist['Kiosks']['buttoncolor']; }else{ echo "#ff99cc"; } ?>" type="text">
											</div>
											<div class="form-group">
												<label>Text Color</label>
												<input name="data[Kiosk][textcolor]" id="textcolor" value="<?php if(isset($Kioskslist['Kiosks']['textcolor'])){ echo $Kioskslist['Kiosks']['textcolor']; }else{ echo "#999999"; } ?>" type="text">
											</div>
											<hr></hr>
											<div class="form-group">
												<label style="font-size:20px;padding-bottom:10px">KeyPad Buttons</label>
											</div>
											<div class="form-group">
												<label>Button Color</label>
												<input name="data[Kiosk][keypad_button_color]" id="keypad_button_color" value="<?php if(isset($Kioskslist['Kiosks']['keypad_button_color'])){ echo $Kioskslist['Kiosks']['keypad_button_color']; }else{ echo "#999999"; } ?>" type="text">
											</div>
											<div class="form-group">
												<label>Text Color</label>
												<input name="data[Kiosk][keypad_text_color]" id="keypad_text_color" value="<?php if(isset($Kioskslist['Kiosks']['keypad_text_color'])){ echo $Kioskslist['Kiosks']['keypad_text_color']; }else{ echo "#ff99cc"; } ?>" type="text">
											</div>
											<hr></hr>
											<div class="form-group">
                                                <label style="font-size:20px;padding-bottom:10px">Bottom Text</label>
                                                <textarea name="data[Kiosk][bottom_text]" id="bottom_text" class="form-control" rows="3"><?php if(isset($Kioskslist['Kiosks']['bottom_text'])){ echo $Kioskslist['Kiosks']['bottom_text']; }else{ ?>Copyright <?php echo date('Y');?> - All Rights Reserved	<?php } ?></textarea>
                                            </div>
											<div class="form-group">
                                                <label>Alignment</label>
                                                <select class="form-control" name="data[Kiosk][bottom_text_alignment]" id="bottom_text_alignment">
                                                    <option value="Center" <?php if($Kioskslist['Kiosks']['bottom_text_alignment']=='Center'){ echo "selected"; }?>>Center</option>
													<option value="Left" <?php if($Kioskslist['Kiosks']['bottom_text_alignment']=='Left'){ echo "selected"; }?>>Left</option>
													<option value="Right" <?php if($Kioskslist['Kiosks']['bottom_text_alignment']=='Right'){ echo "selected"; }?>>Right</option>
                                                </select>
											</div>	
											<div class="form-group">
													<label>Font</label>
													<select class="form-control" name="data[Kiosk][bottom_text_font]" id="bottom_text_font">
														<option value="A" <?php if($Kioskslist['Kiosks']['bottom_text_font']=='A'){ echo "selected"; }?>>Arial</option>
														<option value="H" <?php if($Kioskslist['Kiosks']['bottom_text_font']=='H'){ echo "selected"; }?>>Helvetica</option>
														<option value="T" <?php if($Kioskslist['Kiosks']['bottom_text_font']=='T'){ echo "selected"; }?>>Times New Roman</option>
														<option value="V" <?php if($Kioskslist['Kiosks']['bottom_text_font']=='V'){ echo "selected"; }?>>Verdana</option>
													</select>
											</div>	
											<div class="form-group">
												<label>Size</label>
												<select class="form-control" name="data[Kiosk][bottom_text_size]" id="bottom_text_size">
													<option value="8" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='8'){ echo "selected"; }?>>8</option>
													<option value="9" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='9'){ echo "selected"; }?>>9</option>
													<option value="10" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='10'){ echo "selected"; }?>>10</option>
													<option value="11" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='11'){ echo "selected"; }?>>11</option>
													<option value="12" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='12'){ echo "selected"; }?>>12</option>
													<option value="13" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='13'){ echo "selected"; }?>>13</option>
													<option value="14" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='14'){ echo "selected"; }?>>14</option>
													<option value="16" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='16'){ echo "selected"; }?>>16</option>
													<option value="18" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='18'){ echo "selected"; }?>>18</option>
													<option value="20" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='20'){ echo "selected"; }?>>20</option>
													<option value="24" <?php if($Kioskslist['Kiosks']['bottom_text_size']=='24'){ echo "selected"; }?>>24</option>
												</select>
											</div>
											<div class="form-group">
												<label>Color</label>
												<input name="data[Kiosk][bottom_text_color]" id="bottom_text_color" value="<?php if(isset($Kioskslist['Kiosks']['bottom_text_color'])){ echo $Kioskslist['Kiosks']['bottom_text_color']; }else{ echo "#ffcc00"; } ?>" type="text">
											</div>											
                                            <div class="form-group">
                                                <label>Style</label>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleB]" id="bottom_text_styleB" <?php if($Kioskslist['Kiosks']['bottom_text_styleB']==1){ echo "checked"; }?> > B </label>
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleI]" id="bottom_text_styleI" <?php if($Kioskslist['Kiosks']['bottom_text_styleI']==1){ echo "checked"; }?>> I</label> 
                                                    <label><input type="checkbox" name="data[Kiosk][bottom_text_styleU]" id="bottom_text_styleU" <?php if($Kioskslist['Kiosks']['bottom_text_styleU']==1){ echo "checked"; }?>> U</label>
                                                </div>
                                            </div>
											<hr></hr>
                                            <div class="form-group">
                                                <label>Collect Additional Information</label>
                                                <div class="checkbox-inline">
                                                    <label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][firstname]" id="firstname" <?php if($Kioskslist['Kiosks']['firstname']==1){ echo "checked"; }?>> First Name </label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][lastname]" id="lastname" <?php if($Kioskslist['Kiosks']['lastname']==1){ echo "checked"; }?>> Last Name
													</label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][email]" id="email" <?php if($Kioskslist['Kiosks']['email']==1){ echo "checked"; }?>> Email
													</label>
													<label class="col-sm-6">
                                                        <input type="checkbox" name="data[Kiosk][dob]" id="dob" <?php if($Kioskslist['Kiosks']['dob']==1){ echo "checked"; }?>> Date Of Birth
													</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn blue">Update</button>
											<a class="btn default" href="<?php echo SITE_URL;?>/kiosks/index">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                                           </div>
                    </div>
		<script>
            jQuery(function(){
				jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a kiosk name"
                });jQuery("#KioskLoyaltyPrograms").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please select a kiosk loyalty program"
                });jQuery("#textheader").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter text header"
                });jQuery("#bottom_text").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter bottom text"
                });
				
            });		
	</script>