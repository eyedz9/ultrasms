<style>
.loginner {
    float: left;
    margin: 30px;
}
.loginner {
    border: 1px solid #EB5930;
    border-radius: 6px 6px 6px 6px;
    box-shadow: 0 0 2px #EB5930;
    margin: 30px auto;
    padding: 35px;
    width: 331px;
}
</style>

<script>
 /* <![CDATA[ */
            jQuery(function(){
			 jQuery("#group_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Group Name"
                });
				jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Group Text"
                })
            });
            /* ]]> */	

var count = "158";


function update(){
var tex = $("#message").val();
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
var message = $("#Preview").val();
var lenth = message.length;
$("#message").val(tex);
if(lenth > count){
tex = tex.substring(0,count);
$("#message").val(tex);
$("#Preview").val(tex);
return false;
}
$("#message").val(tex);
$("#limit").val(count-lenth);
$("#Preview").val(tex) ;
}
function update1(){


var tex = $("#group_name").val();
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





$("#group_name").val(tex);

}

</script>


<style type="text/css">
.feildbox label {width: 101px;}
.ValidationErrors {  margin-left: 100px;}
tr.odd.gradeX td.sorting_1 {
    background-color: #FC981E;

</style>
<?php //echo $this->Session->flash(); ?>
<h2><?php __('Import Subscriber');?></h2>
<!-- login box-->
<?php // pr ($data);?>
	<div class="loginner">
	<?php //echo $this->Session->flash(); ?>
		<!--Javascript validation goes Here---------->
	
		<div class="login-left">
		
		
			
		</div>

		</div>
		
	


<!-- login box-->


