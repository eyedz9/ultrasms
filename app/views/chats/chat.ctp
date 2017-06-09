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
  margin: 0 10px 0 10px;
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

.portlet.box > .portlet-title > .caption .white {
    color: white;
}
/*.inbx-list .media-list .media:nth-child(2n+1) {
    background: #fbfcfd none repeat scroll 0 0;
}*/
.portlet.light > .portlet-title > .actions .btn-icon-only 
{
    height: 47px;
    width: 47px;
}

</style>
<script>
function markunread(id){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/markunread/"+id,
		type: "POST",
		dataType: "html",
		success: function(response){
			getnewmsgdetrails();
		}
	});
}	
function markread(id){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/markread/"+id,
		type: "POST",
		dataType: "html",
		success: function(response){
			getnewmsgdetrails();
		}
	});
}		
function deletethread(contactid){
	var r=confirm("Are you sure you want to delete?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletecontact_thread/"+contactid,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsgdetrails();
			}
		});
	}
}
function deletecontact(contactid){
	var r=confirm("Are you sure you want to delete?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletecontact/"+contactid,
			type: "POST",
			dataType: "html",
			success: function(response){
				window.location.reload(true);
			}
		});
	}
}
function downloadhistory(contactid){
	$.ajax({
		url: "<?php  echo SITE_URL ?>/chats/downloadhistory/"+contactid,
		type: "POST",
		dataType: "html",
		success: function(response){
			getnewmsgdetrails();
		}
	});
}
function deletemsg(logid){
	var r=confirm("Are you sure you want to delete?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/deletemsg/"+logid,
			type: "POST",
			dataType: "html",
			success: function(response){
				alert(response);
				getnewmsgdetrails();
			}
		});
		return false;
	}
}
function retry(id){
	var r=confirm("Are you sure you want to resend?");
	if (r==true){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/retry/"+id,
			type: "POST",
			dataType: "html",
			success: function(response){
				getnewmsgdetrails();
				setTimeout(function(){
					getnewmsgdetrails();
				},12000);
			}
		});

	}
}
function getnewmsgdetrails(){
	$.ajax({
                url: "<?php  echo SITE_URL ?>/chats/chatmsg/CONTACT/<?php echo $contactArr['Contact']['id'];?>",
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
function favoriteassignsingle(contact_id,fav){
	if(fav==0){
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorite/"+contact_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefunsingle"+contact_id).attr("onclick", "return favoriteassignsingle("+contact_id+",1);");
					$('#favoriteassignsingle'+contact_id).empty();
					$('#favoriteassignsingle'+contact_id).html('<i class="fa fa-heart blue" aria-hidden="true"></i>');
				}
			}
		});
	}else{
		$.ajax({
			url: "<?php  echo SITE_URL ?>/chats/favorite/"+contact_id+"/"+fav,
			type: "POST",
			dataType: "html",
			success: function(response) {
				if(response==0){
					window.location.reload(true); 
				}else{
					$("#favoritefunsingle"+contact_id).attr("onclick", "return favoriteassignsingle("+contact_id+",0);");
					$('#favoriteassignsingle'+contact_id).empty();
					$('#favoriteassignsingle'+contact_id).html('<i class="fa fa-heart white" aria-hidden="true"></i>');
				}
			}
		});
	}
}
</script>
<!--<div class="portlet box red inlstpr chtsecrgt">-->
<!--<div class="portlet light" style="padding:0px">-->
<div class="portlet mt-element-ribbon light portlet-fit " style="margin-bottom:0px;padding-right:0px">
<div class="ribbon ribbon-left ribbon-shadow ribbon-border-dash-hor ribbon-color-success uppercase" style="height:70px;top:0px;display:table;z-index:0">

<?php 
				$phone = preg_replace("/[^0-9]/", "", $contactArr['Contact']['phone_number']);
				$length = strlen($phone);
                                $number = $phone;
				/*if($length==7){
					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
				}else if($length==8){
					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
				}else if($length==9){
					$number =  preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
				}else if($length==10){
					$number =  preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
				}else if($length==11){
					$number = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
				}else if($length > 11){
					$number = $phone;
				}	*/				
			?>
			<div style="display: table-cell;vertical-align: middle;"><font style="font-size:22px"><?php if(trim($contactArr['Contact']['name']) !=''){ echo ucfirst($contactArr['Contact']['name']); echo ' ('.$number.')';}else{ echo $number; } ?></font></div>

</div>
	<div class="portlet-title">
		<div class="caption" style="line-height:36px;">

			<!--<span style="width:20px;background:none;cursor:pointer" id="favoritefunsingle<?php echo $contactArr['Contact']['id'];?>" href="javascript:void(0)" onclick="return favoriteassignsingle(<?php echo $contactArr['Contact']['id'];?>,<?php echo $contactArr['Contact']['favorite'];?>)" title="Mark/Unmark as Favorite">-->
			<!--<?php if($contactArr['Contact']['favorite']==1){ ?>
				<span class="star-img" id="favoriteassignsingle<?php echo $contactArr['Contact']['id'];?>"><i class="fa fa-heart blue"></i></span></span>
			<?php }else if($contactArr['Contact']['favorite']==0){ ?>
				<span class="star-img" id="favoriteassignsingle<?php echo $contactArr['Contact']['id'];?>"><i class="fa fa-heart white"></i></span></span>
			<?php }?>-->
			
			
		</div>
			<!--<div class="btn-group pull-right tools" style="padding:6px 0 0 !important;">
			<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
			<i class="fa fa-angle-down"></i>
			</button>
			<ul class="dropdown-menu pull-right" role="menu">
			<li>

<a href="#null" onclick="return deletecontact(<?php echo $contactArr['Contact']['id']; ?>)" title="Delete Contact"><i class="fa fa-user-times"></i> Delete Contact</a>
</li>
</li>
			<li><a href="#null" title="Delete Chat" onclick="return deletethread(<?php echo $contactArr['Contact']['id']; ?>)"><i class="fa fa-trash"></i> Delete Chat</a></li>
			<li><a href="<?php  echo SITE_URL ?>/chats/downloadhistory/<?php echo $contactArr['Contact']['id']; ?>" title="Download Chat History"><i class="fa fa-download"></i> Download Chat History</a>
<li><a href="<?php echo SITE_URL ?>/messages/send_message/<?php echo $contactArr['Contact']['id']; ?>/contacts" title="Schedule Message"><i class="fa fa-calendar-plus-o"></i> Schedule Message</a>
			</li>							
			</ul>
			</div>-->

<div class="actions" style="padding-left: 250px;">
                                        <span class="btn btn-circle btn-icon-only btn-default" onclick="return deletecontact(<?php echo $contactArr['Contact']['id']; ?>)" title="Delete Contact" style="border-color: #EF4836;border-radius: 0px !important;box-shadow:1px 1px 2px -1px rgba(0, 0, 0, 0.52) inset !important">
                                            <i class="icon-user-unfollow" style="margin-top:11px;font-size:22px;color:#EF4836 "></i>
                                        </span>&nbsp;
                                        <span class="btn btn-circle btn-icon-only btn-default" title="Delete Chat" onclick="return deletethread(<?php echo $contactArr['Contact']['id']; ?>)" style="border-color: #F7CA18;border-radius: 0px !important;box-shadow:1px 1px 2px -1px rgba(0, 0, 0, 0.52) inset !important ">
                                            <i class="icon-trash" style="margin-top:11px;font-size:22px;color:#F7CA18 "></i>
                                        </span>&nbsp;
                                        <a class="btn btn-circle btn-icon-only btn-default" href="<?php  echo SITE_URL ?>/chats/downloadhistory/<?php echo $contactArr['Contact']['id']; ?>" title="Download Chat History" style="border-color: #8E44AD;border-radius: 0px !important;box-shadow:1px 1px 2px -1px rgba(0, 0, 0, 0.52) inset !important">
                                            <i class="icon-cloud-download" style="margin-top:11px;font-size:22px;color:#8E44AD"></i>
                                        </a>&nbsp;
                                        <a class="btn btn-circle btn-icon-only btn-default" href="<?php echo SITE_URL ?>/messages/send_message/<?php echo $contactArr['Contact']['id']; ?>/contacts" title="Schedule Message" style="border-color: #26C281;border-radius: 0px !important;box-shadow:1px 1px 2px -1px rgba(0, 0, 0, 0.52) inset !important "> <i class="icon-calendar" style="margin-top:11px;font-size:22px;color:#26C281 "></i></a>
                                    </div>


	</div>
</div>
<div class="valign-top panel panel-default table-layout">
<div class="panel-collapse pull out">
	<div class="panel-body inbx-list" style="height:561px;overflow:hidden;">
		<div class="showimages"></div>
		<div class="table-responsive panel-collapse pull out">
			<div class="panel-body slimscroll np" id="scroll-area2" style="position: relative; width: auto; overflow: auto; height:561px;" data-auto-scroll="true" data-slide-speed="200">
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
								<!--<li class="media border-dotted <?php echo $cont_color;?>">-->
                                                                    
									<?php if($log['Log']['route']=='inbox'){ ?>
<li class="media border-dotted <?php echo $cont_color;?>" style="margin-bottom:10px;background:#fbfcfd">
                                                                           <?php if(trim($log['Contact']['color']) == '') { 
                                                                                 $color = '#dddddd';
                                                                           }else {
                                                                                 $color = $log['Contact']['color'];
                                                                           }?>
										<?php if($log['Contact']['email'] !=''){ ?> 
											<?php
												$url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $log['Contact']['email'] ) ) ) . '?d=404';
												$headers = @get_headers( $url );
												if(preg_match( '|200|', $headers[0])){
													echo '<img src="'.$url.'" style="width:39px;float:left;margin-left:10px" class="media-object img-circle" alt="">';
												}else if(trim($log['Contact']['name']) !=''){
													echo '<span class="titleName" style="background: '.$color.' none repeat scroll 0 0;">'.strtoupper(substr($log['Contact']['name'],0,2)).'</span>	';
												}else{ 
											               echo '<span class="titleName" style="background: '.$color.' none repeat scroll 0 0;">#</span>';
}
											?>
										<?php }else if(trim($log['Contact']['name']) !=''){  ?>
											<span class="titleName" style="background: <?php echo $color;?> none repeat scroll 0 0;"><?php echo strtoupper(substr($log['Contact']['name'],0,2)); ?></span>	
										<?php }else{ ?>
											<!--<span class="titleName" style="background: <?php echo $log['Contact']['color'];?> none repeat scroll 0 0;">#</span>	-->
                                                                                        <span class="titleName" style="background: <?php echo $color;?> none repeat scroll 0 0;">#</span>
										<?php } ?>
									<?php }else{ ?>
<li class="media border-dotted" style="margin-bottom:10px;background:#ebf9ff">
										<?php
											$fname =  substr($this->Session->read('User.first_name'),0,1);
											$lname =  substr($this->Session->read('User.last_name'),0,1);
											$name = strtoupper($fname.''.$lname);
										?>
										<!--<span class="titleName" style="background:#78beb6 none repeat scroll 0 0;"><?php echo $name; ?></span>-->
									<?php } ?>
									<!--<div class="main-chat">-->
										<?php if($log['Log']['route']=='inbox'){ ?>
                                                                                        <div class="main-chat">
											<h3><?php if(trim($log['Contact']['name']) !=''){ echo ucfirst($log['Contact']['name']); }else{ echo $log['Contact']['phone_number']; } ?></h3>
										<?php }else{ ?>
                                                                                        <div class="main-chat" style="padding-left:10px;float:left;width:100%">
											<h3><?php echo ucfirst($this->Session->read('User.first_name').' '.$this->Session->read('User.last_name'))?></h3>
										<?php } ?>
										<p class="pull-right text-right date">
											<?php
												$full = false;
												$now = new DateTime;
												$ago = new DateTime($log['Log']['created']);
												$diff = $now->diff($ago);
												$diff->w = floor($diff->d / 7);
												$diff->d -= $diff->w * 7;
												$string = array(
													'y' => 'year',
													'm' => 'month',
													'w' => 'week',
													'd' => 'day',
													'h' => 'hour',
													'i' => 'minute',
													's' => 'second',
												);
												if($diff->d == 0){
													foreach ($string as $k => &$v) {
														if ($diff->$k) {
															$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
														} else {
															unset($string[$k]);
														}
													}
													if (!$full) $string = array_slice($string, 0, 1);
													if($log['Log']['route']=='outbox'){
														if(($diff->s <= 5) && ($diff->i == 0)){
															echo "Sending..";
														}else if(($diff->i == 0) && ($diff->s >= 5) && ($diff->s <= 10)){
															echo ucfirst($log['Log']['sms_status']);
														}else if (trim($log['Log']['sms_status'])=='undelivered' || trim($log['Log']['sms_status'])=='failed') { ?>
															<p class="pull-right text-right date" style="color:#c0392b !important;"><?php echo ucfirst($log['Log']['sms_status']);?><span class="reficn"><a href="#null" onclick="return retry(<?php echo $log['Log']['id'];?>)"><i class="fa fa-refresh" aria-hidden="true"></i></a></span></p>
														<?php }else{
															echo date('h:i a / j M Y',strtotime($log['Log']['created']));
														}
													}else{
														echo date('h:i a / j M Y',strtotime($log['Log']['created']));
													}
												}else{
													echo date('h:i a / j M Y',strtotime($log['Log']['created']));
												}
											?>
										</p>
										<?php if($log['Log']['msg_type']=='text'){ ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd">
												<!--<p style=" word-wrap: break-word;"><?php echo ucfirst($log['Log']['text_message']); ?></p>-->
<?php if(trim($log['Log']['image_url'])!=''){ ?>
                                      <p style=" word-wrap: break-word;"><img src="<?php echo $log['Log']['image_url']; ?>" width="100px" height="100px"></p>
                                <?php }else {?>
				      <p style=" word-wrap: break-word;"><?php echo ucfirst($log['Log']['text_message']); ?></p>
                                <?php } ?>
                                
												<div class="div-hover">
                                                                                                <?php if($log['Log']['route']=='inbox'){ ?>
													<?php if($log['Log']['read']==0){?>
														<a href="javascript:void(0)" onclick="return markread(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-check-circle-o" style="font-size:13px;"></i>mark as read</a>
													<?php }else if($log['Log']['read']==1){?>
														<a href="javascript:void(0)" onclick="return markunread(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-check-circle" style="font-size:13px;"></i>mark as unread</a>
													<?php }} ?>
													<a href="javascript:void(0)" onclick="return deletemsg(<?php echo $log['Log']['id'];?>)" style="font-size:13px;"><i class="fa fa-trash" style="font-size:13px;"></i>delete</a>
												</div>
											</div>
										<?php } ?>
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