<style type="text/css">
    input[type=text], input.submit {
        border:1px solid #CCCCCC;
        color:#333333;
        font-size:24px;
        height:40px;
        margin:0 10px 0 0;
        padding:5px 2px 0 4px;
        width:200px;
    }

    input.submit {
        background-color:#fff;
        margin: 0;
        padding: 0;
        vertical-align:bottom;
    }

    td, a, label {
        color: #000000;
        font-family: <?php echo $data['fontFamily'];?>;
        text-decoration: none;
    }

    div.widget {
        background-color: <?php echo $data['bgColor'];?>;
        width: 520px;
    }

    p.message {
        font-family: <?php echo $font_family[$data['fontFamily']];?>;
        font-size:  <?php echo $data['fontSize'];?>px;
        font-weight: <?php echo $font_weight[$data['fontStyle']];?>;    
	}

    div.widget form label {
        font-size: 18px;
    }

    img.logo {
        max-width: 170px;
    }
</style>

<script>
<!--
function submitAJAXForm(sub) {

    var phoneCheck = document.getElementById('phonenumber').getValue();

    if(phoneCheck.length!=10 || isNaN(phoneCheck)) {
        var msgdialog = new Dialog();
        msgdialog.showMessage('Error', 'Please enter a valid phone number without spaces or dashes.');
        return false;
    }

    sub.setDisabled(true);
    sub.setValue(".. Sending ..");

    var ajax = new Ajax();
    ajax.responseType = Ajax.FBML;

    // confirmation message is msgAfterSub
    ajax.ondone = function(data) {
        var msgdialog = new Dialog();
		var msg='<?php echo $data['msgAfterSub'];?>';
		if(msg=='')
        msgdialog.showMessage('Confirmation', "Thanks for signup");
		else
		msgdialog.showMessage('Confirmation', msg);
		document.getElementById('firstName').setValue('');
		document.getElementById('lastName').setValue('');
		document.getElementById('phonenumber').setValue('');
        sub.setValue("Success!");
        return false;
    }

    ajax.onerror = function() {
        var msgdialog = new Dialog();
        msgdialog.showMessage('Error', 'An error has occurred while trying to join.');
        return false;
    }

    // collect field values
    var queryParams = {
        'firstName' : document.getElementById('firstName').getValue(),
        'lastName' : document.getElementById('lastName').getValue(),
        'phonenumber' : document.getElementById('phonenumber').getValue(),
        'autoresponder' : document.getElementById('autoresponder').getValue()
    };

    // post to widget URL
    ajax.post('https://wyldmobile.com/webwidgets/subscribefb/<?php echo $user_id;?>/<?php echo $group_id;?>', queryParams);
    return false;
}
//-->
</script>

<div class="widget">
    <table>
    <tr>
        <td style="width: 170px;">
                            <img class="logo" src="<?php echo $data['urlLogo'];?>">
                                        <p class="message"><?php echo $data['msgTop'];?></p>
                        
        </td>
        <td style="width: 29px;"></td>
        <td style="width: 321px;">
            <form name="widget" action="https://wyldmobile.com/webwidgets/subscribefb/<?php echo $user_id;?>/<?php echo $group_id;?>" method="post">
                <table>
                    <tr>
                        <td>
                            <label for="firstName">First Name:</label>
                        </td>
                        <td>
                            <input type="text" name="firstName" id="firstName" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="lastName">Last Name:</label>
                        </td>
                        <td>
                            <input type="text" name="lastName" id="lastName" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="phonenumber">Mobile:</label>
                        </td>
                        <td>
                            <input type="text"  maxlength="11" name="phonenumber" id="phonenumber" />
							<input type="text"   name="autoresponder" id="autoresponder" value="<?php echo $data['msg'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <em>ex: 2125551212</em>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="button" class="submit" onclick="submitAJAXForm(this);" value="Join Now" />
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span style="font-size: 10px;">Our service is only available in the United States and Canada.<br />
            <strong>To unsubscribe: Reply 'STOP' to any message you receive.</strong><br />
            <strong>For Help text HELP anytime.</strong><br />
            Your privacy is always protected and your information will not be shared.<br />
            Message &amp; Data Rates May Apply<br />
            <a href="<?=SITEURL?>">Powered By WYLD Mobile.</a></span>
        </td>
    </tr>
    </table>
</div>
