<p>Hello <?php echo $firstname; ?>,</p>

<p>
Your account with <?php echo SITENAME ?> hasn't been activated yet. Please see the status and reason below.
</p>

<p>
<b>PayPal payment status:</b> <?php echo $status; ?><br/>
<b>PayPal pending reason:</b> <?php echo $reason; ?><br/>
</p>

<p>
Once payment clears and is marked "Completed" in PayPal, your account will automatically be activated.
</p>

<p>
Regards and happy marketing!
</p>