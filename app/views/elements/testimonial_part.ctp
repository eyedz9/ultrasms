

<style>
.testimonials .user-img img {
width:90px;
height:94px;
}
</style>
<h5>Client Testimonials</h5>
					<div class="inner-message">
					<div  id="slideshow">
						<div>
								<div class="user-img">
								<?php echo $this->Html->image('user-img.png')?>
								</div>
								<div class="message">
									<p>The user interface couldn't be any easier or more intuitive to navigate. Everything in here is all self-explanatory and for an average user, this is great to see.</p>
									<span>- Alan</span>
								</div>
						</div>
						<div>
								<div class="user-img">
								<?php echo $this->Html->image('user-img2.jpg')?>
								<!--img style="width:90px;height:94px;"src="./img/user-img2.jpg"-->
								
								</div>
								<div class="message">
									<p>I am loving all the features this service provides! I have tried others in the past and most of them didn't provide the kind of flexibility and power with the ease of use as this one.</p>
									<span>- Peter</span>
								</div>
						</div>
								<div>
								<div class="user-img">
								<?php echo $this->Html->image('user-img3.jpg')?>
								<!--img style="width:90px;height:94px;"src="./img/user-img3.jpg"-->
								</div>
								<div class="message">
									<p> I love how I can create groups, then upload existing contacts into those groups! Huge time saver!</p>
									<span>- Mark</span>
								</div>
						</div>
			
					</div>
					</div>
					<div class="clear"></div>
					<div id="nav"></div>
							
<script type="text/javascript">
$(function() {

    $('#slideshow').cycle({
        fx:      'scrollHorz',
        timeout:  8000,
        pager:   '#nav'
    });

   
    
});
</script>