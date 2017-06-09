<style>
.titleName {
  border-radius: 28px;
  display: inline-block;
  height: 39px;
  line-height: 15px;
  padding: 11px;
  text-align: center;
  width: 39px;
  color: #ffffff !important;
}
#chatmsg .main-chtbx span {
  float: left;
  height: 38px;
  line-height: 17px;
  margin: 0 10px 0 0;
  width: 38px;
}
#divimages a img {
  margin: 0 10px 0 0;
}
.text-right.date a img {
  display: inline-block;
  margin: 0 0 0 5px;
  position: relative;
  text-align: right;
  top: 3px;
  width: 16px;
}
.pull-right.text-right.date > span {
    display: inline;
}
.rednumb > a {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  color: #6f6f6f;
  font-weight: bold;
}

.tag-out{ padding :0px 10px !important; }

.ui-autocomplete.ui-front.ui-menu.ui-widget.ui-widget-content {
  padding:0!important; background:#fff; border:1px solid #78beb6!important; border-radius:0;
  width: 150px !important;
}

.ui-autocomplete li {  font-size: 12px;  padding: 4px 10px;}

.ui-autocomplete li:hover {  background: #dde4e6;  border:none; color:#333333;}

.ui-menu-item {
    list-style: outside none none !important;
}

.tab span {
  font-family: arial;
  font-weight: bold;
  margin: -5px 0 0 3px;
  position: relative;
  top: 0px;
  cursor:pointer;
}
.tab {
    background: #c0392b none repeat scroll 0 0;
    padding: 0 8px;
}

.add-btn input#tag {   margin: 0;
  font-size: 12px !important;
  height: 29px;
  padding: 2px 12px;
}

.tag-out .add-btn .btn.chat { margin: 0 0 0 3px;
  font-size: 11px;
  height: 28px  !important;
  padding: 0 5px !important;
}

.tab {  background: #78beb6;  border: 1px solid #184f4f;  padding: 0 8px;}

.tags-in a {  color: #184f4f!important;}

.tags-in a span {  color:#c0392b!important;}

.srtags {
    display: inline-block;
    float: left;
    margin: 0 0 8px 14px;
    width: 150px;
}

/*.srtags input {
  border-radius: 0;
  float: left;
  font-size: 12px !important;
  height: 20px;
  margin: 0 1px 0 0;
  padding: 2px 10px;
  text-align: left;
  width: 100px;
}*/

.srtags input {
    border-radius: 0;
    float: left;
    font-size: 11px !important;
    height: 22px;
    margin: -1px 1px 0 0;
    padding: 0 10px;
    text-align: left;
    width: 100px;
}

.srtags .btn.chat {
  color: #fff !important;
  float: left;
  font-size: 12px;
  height: 20px;
  line-height: 19px;
  padding: 0 7px;
}

.srtags input::-webkit-input-placeholder { font-size: 12px !important;}

.srtags input:-ms-input-placeholder { font-size: 12px !important;}

.srtags input:-moz-placeholder { font-size: 12px !important;}

.srtags input::-moz-placeholder { font-size: 12px !important;}


</style>
<script>
function deletethread(group_id){
	var r=confirm("Are you sure you want to delete ?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletegroup_thread/"+group_id,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsg_group();
			}
		});
	}
}
function deletemsggroup(id){
	var r=confirm("Are you sure you want to delete ?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletemsggroup/"+id,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsg_group();
			}
		});
	}
}
function getnewmsg_group(){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/groupchatmsg/GROUP/<?php echo $groups['Group']['id']; ?>",
		type: "POST",
		dataType: "html",
		success: function(response) {
			if(response==0){
				window.location.reload(true); 
			}else{
				$('#chatmsg').html(response);
			}
		}
	});
}
function favoriteassigngroup(group_id,fav){
	if(fav==0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/groupfavorite/"+group_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefungroup"+group_id).attr("onclick", "return favoriteassigngroup("+group_id+",1);");
					$('#favoriteassigngroup'+group_id).empty();
					$('#favoriteassigngroup'+group_id).html('<i class="fa fa-star blue" aria-hidden="true"></i>');
				}
			}
		});
	}else{
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/groupfavorite/"+group_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefungroup"+group_id).attr("onclick", "return favoriteassigngroup("+group_id+",0);");
					$('#favoriteassigngroup'+group_id).empty();
					$('#favoriteassigngroup'+group_id).html('<i class="fa fa-star white" aria-hidden="true"></i>');
				}
			}
		});
	}
}
</script>
<div class="portlet box red inlstpr chtsecrgt">
	<div class="portlet-title">
		<div class="caption" style="line-height:36px;">
			<a id="favoritefungroup<?php echo $groups['Group']['id'];?>" href="#null" onclick="return favoriteassigngroup(<?php echo $groups['Group']['id'];?>,<?php echo $groups['Group']['favorite'];?>)">
				<?php if($groups['Group']['favorite']==1){ ?>
					<span class="star-img" id="favoriteassigngroup<?php echo $groups['Group']['id'];?>"><i class="fa fa-star blue"></i></span>
				<?php }else if($groups['Group']['favorite']==0){ ?>
					<span class="star-img" id="favoriteassigngroup<?php echo $groups['Group']['id'];?>"><i class="fa fa-star white"></i></span>
				<?php }?>
			</a>
			<?php echo ucfirst($groups['Group']['group_name']) ?><?php echo ' | '.$groups['Group']['totalsubscriber'].' Total Subscribers';?>
		</div>
		<div class="btn-group pull-right tools" style="padding:6px 0 0 !important;">
			<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
				<i class="fa fa-angle-down"></i>
			</button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li><a href="#null" title="Delete Chat" onclick="return deletethread(<?php echo $groups['Group']['id']; ?>)"><i class="fa fa-trash"></i> Delete Chat</a></li>
				<li><a href="#null" title="Download Chat History"><i class="fa fa-download"></i> Download Chat History</a></li>							
			</ul>
		</div>
	</div>
</div>
<div class="valign-top panel panel-default table-layout">
<div class="panel-collapse pull out">
	<div class="panel-body inbx-list" style="height:338px;overflow:hidden;">
		<div class="showimages"></div>
		<div class="table-responsive panel-collapse pull out">
			<div class="panel-body slimscroll np" id="scroll-area2" style="position: relative; width: auto; overflow: auto; height:335px;" data-auto-scroll="true" data-slide-speed="200">
				<div id="chatmsg">
					<ul class="col-lg-12 col-md-12 col-sm-12 col-xs-12 main-chtbx media-list">
						<?php if(!empty($logs)){?>
								<?php 
								$i=1; 
								foreach($logs as $log){ 
									if($i%2==0){	
										$cont_color="white";
										$date_black="black";
									}else{
										$cont_color="";	
										$date_black="";
									}
								?>
								<li>
									<?php if($this->Session->read('User.email') !=''){ 
										$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $this->Session->read('User.email') ) ) ) . '?d=404';
										$headers = @get_headers( $url );
										if(preg_match( '|200|', $headers[0])){ ?>
											<span><img class="media-object img-circle" style="width: 39px;" src="<?php echo $url;?>" alt=""/></span>
										<?php }else{ 
											$fname =  substr($this->Session->read('User.first_name'),0,1);
											$lname =  substr($this->Session->read('User.last_name'),0,1);
											$name = strtoupper($fname.''.$lname);
											?>
											<span class="titleName" style="background:#78beb6 none repeat scroll 0 0;"><?php echo $name; ?></span>
										<?php }	?>
									<?php }else{ ?>
										<span><i class="fa fa-users" aria-hidden="true" style="font-size:20px;"></i></span>
									<?php } ?>
									<div class="main-chat">
										<h3><?php echo ucfirst($this->Session->read('User.first_name').' '.$this->Session->read('User.last_name'))?></h3>
										<p class="pull-right text-right date"><?php echo date('j M Y',strtotime($log['GroupSmsBlast']['created'])); ?></p>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd">
											<p style=" word-wrap: break-word;"><?php echo ucfirst($log['GroupSmsBlast']['msg']); ?></p>
											<p>
											<?php if(isset($log['Log'][0])){?>
												<?php if($log['Log'][0]['image_url'] !=''){?>
													<?php 
													$images_url = explode(',',$log['Log'][0]['image_url']);
													foreach($images_url as $images_urls){
													?>
													<img width='100px' height='100px' src="<?php echo $images_urls;?>">
													<?php } ?>
												<?php } ?>
											<?php } ?>
											</p>
											<div class="div-hover">
												<a href="#null" onclick="return deletemsggroup(<?php echo $log['GroupSmsBlast']['id'];?>)"><i class="fa fa-circle"></i>delete</a>
											</div>
										</div>
									</div>
								</li>
							<?php } ?>
						<?php } ?>
					</ul><!--/ main-chtbx -->
				</div>
			</div>
		</div>
	</div>
</div>
</div>