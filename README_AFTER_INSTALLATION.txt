Thanks again for the purchase of UltraSMSScript! Just a few notes to read AFTER installing the software on your domain.



If you haven't already done so, create your Twilio, Nexmo, Plivo, or slooce account
=============================================================================================================================


TWILIO
---------------

In order for the UltraSMSScript to function correctly, you MUST possess an upgraded Twilio account.

Create your Twilio account for free by navigating to the following link.

https://www.twilio.com/try-twilio

When upgrading your account, you are required to deposit into your Twilio account a minimum of $20 to get started. 
Any SMS and voicemail costs is simply taken from the balance of your Twilio account.


If you wish for your users to send global SMS, make sure you have all global permissions enabled for your 
Twilio account

You can check here: 
https://www.twilio.com/user/account/settings/international/sms 

If you want to lock it down to a specific country or countries, then make sure only those countries are checked.


1. Whilst logged into your Twilio account, you should notice an AccountSID and Auth Token, note these down.

2. Whilst logged into your scripts administrative panel, click on 'Config' and 'Edit'. 
   
3. Select Twilio API type, then

   Enter your unique 
   * AccountSID
   * Auth number
   * site URL (example http://yoursiteurl.com) ** Exact URL where script is installed. MUST include "http://" or "https://" in URL.
   * site name
   * Paypal email address (If you plan on accepting PayPal payments for your credit packages, this must be entered)
   * Support email address (Email address created in the "Email to SMS Configuration" section. Should be a domain related email address)
   * click save.



NEXMO
--------------

Create your Nexmo account by navigating to the following link:

https://dashboard.nexmo.com/register

** You will have to deposit money in order to purchase and assign your number in the platform.

Like Twilio, any SMS and voicemail costs is simply taken from the balance of your Nexmo account.


1. Whilst logged into your Nexmo account, you should notice an Nexmo Key and Nexmo Secret, note these down.

2. Whilst logged into your scripts administrative panel, click on 'Config' and 'Edit'. 

3. Select Nexmo API type, then

   Enter your unique 
   * Nexmo Key
   * Nexmo Secret
   * site URL (example http://yoursiteurl.com) ** Exact URL where script is installed. MUST include "http://" or "https://" in URL.
   * site name
   * Paypal email address (If you plan on accepting PayPal payments for your credit packages, this must be entered)
   * Support email address (Email address created in the "Email to SMS Configuration" section. Should be a domain related email address)
   * click save.



PLIVO
--------------

Create your Plivo account by navigating to the following link:

https://manage.plivo.com/accounts/register/

** You will have to deposit money in order to purchase and assign your number in the platform.

Like Twilio, any SMS and voicemail costs is simply taken from the balance of your Nexmo account.


1. Whilst logged into your Plivo account, you should notice an Auth ID and Auth Token, note these down.

2. Whilst logged into your scripts administrative panel, click on 'Config' and 'Edit'. 

3. Select Plivo API type, then

   Enter your unique 
   * Plivo ID
   * Plivo Token
   * site URL (example http://yoursiteurl.com) ** Exact URL where script is installed. MUST include "http://" or "https://" in URL.
   * site name
   * Paypal email address (If you plan on accepting PayPal payments for your credit packages, this must be entered)
   * Support email address (Email address created above in the "Email to SMS Configuration" section)
   * click save.



SLOOCE
---------------

Create your Slooce account by navigating to the following link:

http://www.sloocetech.com

** Slooce is used only in the US and for those wanting to use their shared short code 47711

IMPORTANT: Mention to Behfar Razavi(CEO of SlooceTech) that this is for use in UltraSMSScript.

Behfar will create your Slooce account and provide you with 5 things that will need to be added 
when creating a user account in the platform through the admin panel.

1. Slooce API URL
2. Slooce Keyword(this is actually the keyword you will register with them)
3. Slooce partner ID
4. Slooce partner password
5. Short code(47711)


IMPORTANT - SLOOCE ONLY: Your host needs to allow outbound connections on port 8084. Contact your host and have them open port 8084
for outbound connections.


1. Whilst logged into your scripts administrative panel, click on 'Config' and 'Edit'. 

2. Select Slooce API type, then

   Enter your unique 
   * site URL (example http://yoursiteurl.com) ** Exact URL where script is installed. MUST include "http://" or "https://" in URL.
   * site name
   * Paypal email address (If you plan on accepting PayPal payments for your credit packages, this must be entered)
   * Support email address (Email address created in the "Email to SMS Configuration" section. Should be a domain related email address)
   * click save.

3. Create a user account in the platform through the admin panel, click on 'User' and 'Add User'. Enter all info including the 5 Slooce fields
   obtained above when creating your Slooce account.




It is highly recommended that you create your own FilePicker Account
==============================================================================================================================

FilePicker is the blue "Pick File" button you see on the Bulk SMS page. This service allows you and your users
to select files from 20 cloud sources. Filepicker.io connects to all the places where your users have files, from 
Facebook and Instagram to Dropbox and Google Drive to Github and Gmail attachments. The free plan allows you to 
pick 500 files per month. You can always upgrade to a bigger plan if you need it at a future date.

1. Sign up for the free plan at https://dev.filestack.com/register/free
2. Copy the API key from your development portal inside your filepicker account.
3. Go to the cake/bootstrap.php file of your script installation.
4. Find the line "define('FILEPICKERAPIKEY', 'XXXXXXXXXXXXXXXX');" and replace 'XXXXXXXXXXXXXXXX' with
   your API key you copied in step #2.


** If you don't want to use the File Picker, then you can hide that button. Directly under the line in #4 above, 
   you will see "define('FILEPICKERON', 'Yes');" Just replace 'Yes' with 'No'.




Bitly.com account creation for using the link shortener
==============================================================================================================================

In order to use bitly.com to shorten and track link clicks in the platform, you will need to create an account with them to 
obtain an API key and access token.

1. Go to https://bitly.com/a/sign_up to create an account.
2. Verify your email address from the email that they send you.
3. Log in to your account and go to settings/advanced tab at https://bitly.com/a/settings/advanced.
4. Scroll down and note down your username and API key.
5. Next go to https://bitly.com/a/oauth_apps and click the "Generate Token" button.
6. Note down your Generic Access Token.
7. Whilst logged into your scripts administrative panel, click on 'Config' and 'Edit'. 
8. Scroll to the bottom and enter in your bitly username, bitly API key and bitly access token from the steps above.
9. Click Submit.




Facebook Integration Setup - Social Sharing on Bulk SMS
==============================================================================================================================

If you want the ability for you and your users to share SMS messages to their Facebook accounts, some inital setup
is required. This is the Facebook checkbox on the Bulk SMS page. When sending Bulk SMS, you can share these messages 
to your Facebook account for further sharing and increase participation.

1. Go to https://developers.facebook.com/docs/apps/register and follow all the instructions and steps to create your app.
2. On the App Dashboard, click on the "App Review" menu, under the "Submit Items for Approval" section, click "Start a Submission" button.
3. On the pop-up window, select the "Publish_Actions" checkbox and submit this item for approval.
4. On the App Dashboard, you will see your App ID and App Secret. Then login to your Admin Panel of the script platform and 
   updates these values in the Config section:
	
        * Facebook Appid
	* Facebook Appsecret

5. That's it! It may take a few days before they approve it, but until they do, just leave the "FBTwitterSharing" setting in your admin
   panel to "Off".




If you haven't already done so, configure your helpdesk
==============================================================================================================================

Your helpdesk is already installed, however you need to setup your helpdesk and configure your site URL, 
support email address, name, signature, and change your password, please go to the admin here (modify to your URL):

http://www.site.com/helpdesk/admin/

If asked for a password login using default information:
-> Default username: Administrator
-> Default password: admin

TIP: Passwords are CaSe SeNSiTiVe ("ADMIN" is not the same as "admin") while usernames are not.

1. Click the "Settings" link in the top menu to get to the settings page (if not there already)

   Take some time and get familiar with all the available settings. Most should be self-explanatory, for additional 
   information about each setting, click the [?] link for help about the current setting.

   NOTE: Don't forget to click the "Save changes" button at the bottom of the settings page to save your settings!

2. Click the "Profile" link to set your name, e-mail, signature and *** CHANGE YOUR PASSWORD ***.


Customers can submit tickets and browse knowledgebase by visiting the main folder, for example:

http://www.site.com/helpdesk




To make any changes to the design or front-end elements
===============================================================================================================================


- You can find the individual layouts for each page here under app/views. Under views would be contacts for the contacts 
  page, groups for the groups page, etc…

- To add your own content to the "Terms and Conditions", "Privacy Policy", and "FAQ" pages go here:

  app/views/users/terms_conditions.ctp
  app/views/users/privacy_policy.ctp
  app/views/users/faq.ctp



================================================================================================================================

>>> PLEASE refer to STRIPE_SETUP.txt and Application User Guide.pdf <<<

================================================================================================================================



