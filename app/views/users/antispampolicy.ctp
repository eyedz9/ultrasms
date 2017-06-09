<style>

.leftcolumn {width: 100%;margin-left:0px;}
.rightcolumn {display:none;}
h1 {display:block;text-align:center;font-size:30px;}

.wrapper {width: 80%;}

</style>
    <div class="container" style="margin-top:50px">
      <div class="row">
<!-- login box-->

<div class="loginbox">
	<div>
	<font style="font-size: 18px"><h2 style="display:block"><strong><?php echo SITENAME?> Anti-Spam Policy</strong></h2></font>
                  <p>SMS Spam, Mobile Spam, Text Messaging Spam...<?php echo SITENAME?>  is determined to keep it off of your mobile phone.</p>
                    <br><h3><strong>Our Policy</strong></h3>
                    <p>The <?php echo SITENAME?> text messaging system allows only verified opt-in subscriptions. It ensures that all subscribers can opt-out  quickly, easily, and permanently from unwanted SMS communications. This is done either from our website or by sending a text message from your phone to the  <?php echo $loggedUser['User']['assigned_number']?> with the word 'STOP'.</p>
                    <br><h3><strong>Our Definition of Spam</strong></h3>
                    <p>We consider any unsolicited, unexpected, or unwanted text  message as Spam. We do NOT allow use of 3rd party lists, whether consent has  been gathered or not. We believe that any type of communication sent to a subscriber  about an unrelated subject, that the subscriber did not request, to be Spam.</p>
                    <br><h3><strong><?php echo SITENAME?> Anti-Spam policy:</strong></h3>
                    <p><?php echo SITENAME?> follows an ANTI-SPAM policy for all of its  communications protocols. This means that we do not condone UNSOLICITED TEXT  MESSAGES; NOTIFICATIONS; ALERTS; OR ANY MESSAGES THAT YOU MAY RECEIVE FROM  SOMEONE WHO SHOULD NOT HAVE YOUR MOBILE NUMBER. Please let us know about any  abuse, including the sender ID, your Mobile Number (to be removed); the date  and time you received it and the contents of the message. To report any abuse  or violations of inappropriate use of our service, please contact us with your  comments/complaints. Your report will be registered and the Client will be  investigated for violations of our Anti-Spam Policy. The identity of any  individual reporting abuse will be kept confidential.</p>
                    <br><h3><strong>SMS Text Messaging API</strong></h3>
                    <p>On our terms and conditions agreement, our Clients  specifically agree NOT to use <?php echo SITENAME?> to send unsolicited Text  Messages or Spam. Upon incorporating a list Clients agree on the Opt-In only  policy to their list. All Text Messages sent using <?php echo SITENAME?> can be  tracked and so removed from the sender list. We support OPT-OUT / REMOVE /  UNSUBSCRIBE, Clients have the ability to remove mobile numbers. If we suspect a violation of our Anti-Spam policy, we will contact the  Client and discuss the Client options, which may range from a warning, to  termination of service for that Client. To the best of our knowledge, our  system adheres to ALL state and national laws regarding sending unsolicited  bulk / group text messages.</p>
                    <br><h3><strong>SPAM and ABUSE REPORT: OPT-OUT / REMOVE / UNSUBSCRIBE</strong></h3>
                    <p>If you wish to remove yourself from any list, please email  us (at <?php echo SUPPORT_EMAIL ?>)  with an &quot;OPT-OUT&quot;, &quot;UNSUBSCRIBE&quot;, &quot;STOP&quot; or  &quot;REMOVE&quot; in the subject line. You also may reply to the text Message  received by replying with STOP. Finally, you may send a  text message containing the word &quot;STOP&quot; to the number <?php echo $loggedUser['User']['assigned_number']?>  from your phone.<br />
                      &nbsp;<br />
                      Please let us know about any abuse, including your Mobile Number, the date and  time you received it and the contents of the message. You will be removed from  that list and the Client will be restricted from adding your mobile number to  their list in the future. </p></td>
       		
	</div>
</div>
</div>
</div>
<!-- login box-->