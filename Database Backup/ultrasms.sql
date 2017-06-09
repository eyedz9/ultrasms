SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '16d7a4fca7442dda3ad93c9a726597e4');

-- --------------------------------------------------------

--
-- Table structure for table `answer_subscribers`
--

CREATE TABLE IF NOT EXISTS `answer_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `birthdays`
--

CREATE TABLE IF NOT EXISTS `birthdays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `message` varchar(250) NOT NULL,
  `systemmsg` varchar(255) NOT NULL,
  `days` int(11) NOT NULL,
  `sent` int(11) NOT NULL COMMENT '0=No,1=Yes',
  `sms_type` int(11) NOT NULL COMMENT '0=>sms , 1=>mms',
  `image_url` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_charge` float NOT NULL,
  `free_sms` int(11) NOT NULL,
  `free_voice` int(11) NOT NULL,
  `referral_amount` float NOT NULL,
  `recurring_referral_percent` float NOT NULL,
  `site_url` varchar(250) NOT NULL,
  `sitename` varchar(250) NOT NULL,
  `twilio_accountSid` varchar(100) NOT NULL,
  `twilio_auth_token` varchar(100) NOT NULL,
  `nexmo_key` varchar(255) NOT NULL,
  `nexmo_secret` varchar(255) NOT NULL,
  `plivo_key` varchar(250) DEFAULT NULL,
  `plivo_token` varchar(250) DEFAULT NULL,
  `plivoapp_id` varchar(250) DEFAULT NULL,
  `2CO_account_ID` varchar(250) NOT NULL,
  `2CO_account_activation_prod_ID` int(11) NOT NULL,
  `paypal_email` varchar(100) NOT NULL,
  `support_email` varchar(250) NOT NULL,
  `profilestartdate` int(20) NOT NULL DEFAULT '0',
  `payment_gateway` int(11) NOT NULL COMMENT '1=PayPal,2=Stripe,3=Both',
  `pay_activation_fees` int(11) NOT NULL,
  `mobile_page_limit` int(11) NOT NULL,
  `payment_currency_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `api_type` int(11) NOT NULL DEFAULT '0',
  `AllowTollFreeNumbers` int(11) NOT NULL DEFAULT '0',
  `facebook_appid` varchar(200) NOT NULL,
  `facebook_appsecret` varchar(200) NOT NULL,
  `FBTwitterSharing` int(11) NOT NULL DEFAULT '1',
  `optmsg` varchar(255) NOT NULL,
  `birthday_msg` varchar(250) NOT NULL,
  `name_capture_msg` varchar(250) NOT NULL,
  `email_capture_msg` varchar(250) NOT NULL,
  `bitly_username` varchar(100) NOT NULL,
  `bitly_api_key` varchar(100) NOT NULL,
  `bitly_access_token` varchar(100) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `logout_url` varchar(200) NOT NULL,
  `theme_color` varchar(20) NOT NULL,
  `charge_for_additional_numbers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `registration_charge`, `free_sms`, `free_voice`, `referral_amount`, `recurring_referral_percent`, `site_url`, `sitename`, `twilio_accountSid`, `twilio_auth_token`, `nexmo_key`, `nexmo_secret`, `plivo_key`, `plivo_token`, `plivoapp_id`, `2CO_account_ID`, `2CO_account_activation_prod_ID`, `paypal_email`, `support_email`, `profilestartdate`, `payment_gateway`, `pay_activation_fees`, `mobile_page_limit`, `payment_currency_code`, `country`, `api_type`, `AllowTollFreeNumbers`, `facebook_appid`, `facebook_appsecret`, `FBTwitterSharing`, `optmsg`, `birthday_msg`, `name_capture_msg`, `email_capture_msg`, `bitly_username`, `bitly_api_key`, `bitly_access_token`, `logo`,`logout_url`,`theme_color`,`charge_for_additional_numbers` ) VALUES
(1, 9.99, 50, 10, 3.99, 20, '[YOUR SITE URL]', '[YOUR SITE NAME]', '[YOUR TWILIO SID]', '[YOUR TWILIO AUTH TOKEN]', '[YOUR NEXMO KEY]', '[YOUR NEXMO SECRET]', '[YOUR PLIVO ID]', '[YOUR PLIVO TOKEN]', '', '999999', 4, 'YOURPAYPALEMAIL@EMAIL.COM', 'YOURSUPPORTEMAIL@EMAIL.com', 0, 3, 2, 50, 'USD', '', 1, 0, '', '', 0, 'STOP to end', 'Please provide your Birthday in YYYY-MM-DD format', 'Please provide your Name. Please enter like below example - 

Name: Jim Smith', 'Please provide your Email address. Please enter like below example - 

Email: JimSmith@Email.com', '', '', '', 'logo.png','','l1blue',0);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthday` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  `carrier` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `phone_country` varchar(50) NOT NULL,
  `line_type` varchar(20) NOT NULL,
  `favorite` tinyint(4) NOT NULL DEFAULT '0',
  `un_subscribers` tinyint(4) NOT NULL DEFAULT '0',
  `color` varchar(20) NOT NULL,
  `lastmsg` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`name`),
  INDEX(`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_groups`
--

CREATE TABLE IF NOT EXISTS `contact_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `group_subscribers` varchar(250) NOT NULL,
  `un_subscribers` int(11) NOT NULL,
  `do_not_call` int(11) NOT NULL,
  `subscribed_by_sms` int(11) NOT NULL COMMENT '0=csv,1=sms,2=widget,3=kiosk',
  `active` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`contact_id`),
  INDEX(`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE IF NOT EXISTS `contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_name` varchar(250) NOT NULL,
  `keyword` varchar(250) NOT NULL,
  `system_message` varchar(250) NOT NULL,
  `active` int(1) NOT NULL,
  `totalsubscriber` int(11) NOT NULL,
  `winning_phone_number` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contest_subscribers`
--

CREATE TABLE IF NOT EXISTS `contest_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`phone_number`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbconfigs`
--

CREATE TABLE IF NOT EXISTS `dbconfigs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dbusername` varchar(150) NOT NULL,
  `dbname` varchar(150) NOT NULL,
  `dbpassword` varchar(150) NOT NULL,
  `created` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dbconfigs`
--

INSERT INTO `dbconfigs` (`id`, `dbusername`, `dbname`, `dbpassword`, `created`) VALUES
(1, '', '', '', '2014-09-07');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `group_type` int(11) NOT NULL,
  `auto_message` varchar(100) NOT NULL,
  `system_message` varchar(130) DEFAULT NULL,
  `sms_type` int(11) NOT NULL COMMENT '1=> sms ,2=>mms',
  `bithday_enable` int(11) NOT NULL DEFAULT '0',
  `image_url` text NOT NULL,
  `active` int(11) NOT NULL,
  `totalsubscriber` int(11) NOT NULL,
  `notify_signup` int(11) NOT NULL DEFAULT '0',
  `mobile_number_input` varchar(50) NOT NULL,
  `property_address` varchar(50) DEFAULT NULL,
  `property_price` varchar(20) DEFAULT NULL,
  `property_bed` tinyint(4) DEFAULT NULL,
  `property_bath` varchar(5) DEFAULT NULL,
  `property_description` varchar(200) DEFAULT NULL,
  `property_url` varchar(200) DEFAULT NULL,
  `vehicle_year` varchar(10) DEFAULT NULL,
  `vehicle_make` varchar(30) DEFAULT NULL,
  `vehicle_model` varchar(30) DEFAULT NULL,
  `vehicle_mileage` varchar(30) DEFAULT NULL,
  `vehicle_price` varchar(20) DEFAULT NULL,
  `vehicle_description` varchar(200) DEFAULT NULL,
  `vehicle_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_sms_blasts`
--

CREATE TABLE IF NOT EXISTS `group_sms_blasts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `totals` int(11) NOT NULL,
  `total_successful_messages` int(11) NOT NULL,
  `total_failed_messages` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `responder` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instant_payment_notifications`
--

CREATE TABLE IF NOT EXISTS `instant_payment_notifications` (
  `id` char(36) NOT NULL,
  `notify_version` varchar(64) DEFAULT NULL COMMENT 'IPN Version Number',
  `verify_sign` varchar(127) DEFAULT NULL COMMENT 'Encrypted string used to verify the authenticityof the tansaction',
  `test_ipn` int(11) DEFAULT NULL,
  `address_city` varchar(40) DEFAULT NULL COMMENT 'City of customers address',
  `address_country` varchar(64) DEFAULT NULL COMMENT 'Country of customers address',
  `address_country_code` varchar(2) DEFAULT NULL COMMENT 'Two character ISO 3166 country code',
  `address_name` varchar(128) DEFAULT NULL COMMENT 'Name used with address (included when customer provides a Gift address)',
  `address_state` varchar(40) DEFAULT NULL COMMENT 'State of customer address',
  `address_status` varchar(20) DEFAULT NULL COMMENT 'confirmed/unconfirmed',
  `address_street` varchar(200) DEFAULT NULL COMMENT 'Customer''s street address',
  `address_zip` varchar(20) DEFAULT NULL COMMENT 'Zip code of customer''s address',
  `first_name` varchar(64) DEFAULT NULL COMMENT 'Customer''s first name',
  `last_name` varchar(64) DEFAULT NULL COMMENT 'Customer''s last name',
  `payer_business_name` varchar(127) DEFAULT NULL COMMENT 'Customer''s company name, if customer represents a business',
  `payer_email` varchar(127) DEFAULT NULL COMMENT 'Customer''s primary email address. Use this email to provide any credits',
  `payer_id` varchar(13) DEFAULT NULL COMMENT 'Unique customer ID.',
  `payer_status` varchar(20) DEFAULT NULL COMMENT 'verified/unverified',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT 'Customer''s telephone number.',
  `residence_country` varchar(2) DEFAULT NULL COMMENT 'Two-Character ISO 3166 country code',
  `business` varchar(127) DEFAULT NULL COMMENT 'Email address or account ID of the payment recipient (that is, the merchant). Equivalent to the values of receiver_email (If payment is sent to primary account) and business set in the Website Payment HTML.',
  `item_name` varchar(127) DEFAULT NULL COMMENT 'Item name as passed by you, the merchant. Or, if not passed by you, as entered by your customer. If this is a shopping cart transaction, Paypal will append the number of the item (e.g., item_name_1,item_name_2, and so forth).',
  `item_number` varchar(127) DEFAULT NULL COMMENT 'Pass-through variable for you to track purchases. It will get passed back to you at the completion of the payment. If omitted, no variable will be passed back to you.',
  `quantity` varchar(127) DEFAULT NULL COMMENT 'Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item (e.g., quantity1,quantity2).',
  `receiver_email` varchar(127) DEFAULT NULL COMMENT 'Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.',
  `receiver_id` varchar(13) DEFAULT NULL COMMENT 'Unique account ID of the payment recipient (i.e., the merchant). This is the same as the recipients referral ID.',
  `custom` varchar(255) DEFAULT NULL COMMENT 'Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer.',
  `invoice` varchar(127) DEFAULT NULL COMMENT 'Pass through variable you can use to identify your invoice number for this purchase. If omitted, no variable is passed back.',
  `memo` varchar(255) DEFAULT NULL COMMENT 'Memo as entered by your customer in PayPal Website Payments note field.',
  `option_name1` varchar(64) DEFAULT NULL COMMENT 'Option name 1 as requested by you',
  `option_name2` varchar(64) DEFAULT NULL COMMENT 'Option 2 name as requested by you',
  `option_selection1` varchar(200) DEFAULT NULL COMMENT 'Option 1 choice as entered by your customer',
  `option_selection2` varchar(200) DEFAULT NULL COMMENT 'Option 2 choice as entered by your customer',
  `tax` decimal(10,2) DEFAULT NULL COMMENT 'Amount of tax charged on payment',
  `auth_id` varchar(19) DEFAULT NULL COMMENT 'Authorization identification number',
  `auth_exp` varchar(28) DEFAULT NULL COMMENT 'Authorization expiration date and time, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  `auth_amount` int(11) DEFAULT NULL COMMENT 'Authorization amount',
  `auth_status` varchar(20) DEFAULT NULL COMMENT 'Status of authorization',
  `num_cart_items` int(11) DEFAULT NULL COMMENT 'If this is a PayPal shopping cart transaction, number of items in the cart',
  `parent_txn_id` varchar(19) DEFAULT NULL COMMENT 'In the case of a refund, reversal, or cancelled reversal, this variable contains the txn_id of the original transaction, while txn_id contains a new ID for the new transaction.',
  `payment_date` varchar(28) DEFAULT NULL COMMENT 'Time/date stamp generated by PayPal, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  `payment_status` varchar(20) DEFAULT NULL COMMENT 'Payment status of the payment',
  `payment_type` varchar(10) DEFAULT NULL COMMENT 'echeck/instant',
  `pending_reason` varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=pending',
  `reason_code` varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=reversed',
  `remaining_settle` int(11) DEFAULT NULL COMMENT 'Remaining amount that can be captured with Authorization and Capture',
  `shipping_method` varchar(64) DEFAULT NULL COMMENT 'The name of a shipping method from the shipping calculations section of the merchants account profile. The buyer selected the named shipping method for this transaction',
  `shipping` decimal(10,2) DEFAULT NULL COMMENT 'Shipping charges associated with this transaction. Format unsigned, no currency symbol, two decimal places',
  `transaction_entity` varchar(20) DEFAULT NULL COMMENT 'Authorization and capture transaction entity',
  `txn_id` varchar(19) DEFAULT '' COMMENT 'A unique transaction ID generated by PayPal',
  `txn_type` varchar(20) DEFAULT NULL COMMENT 'cart/express_checkout/send-money/virtual-terminal/web-accept',
  `exchange_rate` decimal(10,2) DEFAULT NULL COMMENT 'Exchange rate used if a currency conversion occured',
  `mc_currency` varchar(3) DEFAULT NULL COMMENT 'Three character country code. For payment IPN notifications, this is the currency of the payment, for non-payment subscription IPN notifications, this is the currency of the subscription.',
  `mc_fee` decimal(10,2) DEFAULT NULL COMMENT 'Transaction fee associated with the payment, mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either ofthose p',
  `mc_gross` decimal(10,2) DEFAULT NULL COMMENT 'Full amount of the customer''s payment',
  `mc_handling` decimal(10,2) DEFAULT NULL COMMENT 'Total handling charge associated with the transaction',
  `mc_shipping` decimal(10,2) DEFAULT NULL COMMENT 'Total shipping amount associated with the transaction',
  `payment_fee` decimal(10,2) DEFAULT NULL COMMENT 'USD transaction fee associated with the payment',
  `payment_gross` decimal(10,2) DEFAULT NULL COMMENT 'Full USD amount of the customers payment transaction, before payment_fee is subtracted',
  `settle_amount` decimal(10,2) DEFAULT NULL COMMENT 'Amount that is deposited into the account''s primary balance after a currency conversion',
  `settle_currency` varchar(3) DEFAULT NULL COMMENT 'Currency of settle amount. Three digit currency code',
  `auction_buyer_id` varchar(64) DEFAULT NULL COMMENT 'The customer''s auction ID.',
  `auction_closing_date` varchar(28) DEFAULT NULL COMMENT 'The auction''s close date. In the format: HH:MM:SS DD Mmm YY, YYYY PSD',
  `auction_multi_item` int(11) DEFAULT NULL COMMENT 'The number of items purchased in multi-item auction payments',
  `for_auction` varchar(10) DEFAULT NULL COMMENT 'This is an auction payment - payments made using Pay for eBay Items or Smart Logos - as well as send money/money request payments with the type eBay items or Auction Goods(non-eBay)',
  `subscr_date` varchar(28) DEFAULT NULL COMMENT 'Start date or cancellation date depending on whether txn_type is subcr_signup or subscr_cancel',
  `subscr_effective` varchar(28) DEFAULT NULL COMMENT 'Date when a subscription modification becomes effective',
  `period1` varchar(10) DEFAULT NULL COMMENT '(Optional) Trial subscription interval in days, weeks, months, years (example a 4 day interval is 4 D',
  `period2` varchar(10) DEFAULT NULL COMMENT '(Optional) Trial period',
  `period3` varchar(10) DEFAULT NULL COMMENT 'Regular subscription interval in days, weeks, months, years',
  `amount1` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 1 for USD',
  `amount2` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 2 for USD',
  `amount3` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription  period 1 for USD',
  `mc_amount1` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 1 regardless of currency',
  `mc_amount2` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 2 regardless of currency',
  `mc_amount3` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription period regardless of currency',
  `recurring` varchar(1) DEFAULT NULL COMMENT 'Indicates whether rate recurs (1 is yes, blank is no)',
  `reattempt` varchar(1) DEFAULT NULL COMMENT 'Indicates whether reattempts should occur on payment failure (1 is yes, blank is no)',
  `retry_at` varchar(28) DEFAULT NULL COMMENT 'Date PayPal will retry a failed subscription payment',
  `recur_times` int(11) DEFAULT NULL COMMENT 'The number of payment installations that will occur at the regular rate',
  `username` varchar(64) DEFAULT NULL COMMENT '(Optional) Username generated by PayPal and given to subscriber to access the subscription',
  `password` varchar(24) DEFAULT NULL COMMENT '(Optional) Password generated by PayPal and given to subscriber to access the subscription (Encrypted)',
  `subscr_id` varchar(19) DEFAULT NULL COMMENT 'ID generated by PayPal for the subscriber',
  `case_id` varchar(28) DEFAULT NULL COMMENT 'Case identification number',
  `case_type` varchar(28) DEFAULT NULL COMMENT 'complaint/chargeback',
  `case_creation_date` varchar(28) DEFAULT NULL COMMENT 'Date/Time the case was registered',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `txnid` varchar(250) NOT NULL,
  `type` int(11) NOT NULL,
  `package_name` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_sms_id` int(11) NOT NULL,
  `sms_id` varchar(250) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `group_id` int(11) DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email_to_sms_number` varchar(20) NOT NULL,
  `text_message` text,
  `image_url` text NOT NULL,
  `voice_url` varchar(255) DEFAULT NULL,
  `call_duration` varchar(15) DEFAULT NULL,
  `route` enum('inbox','outbox') NOT NULL DEFAULT 'inbox',
  `msg_type` enum('text','voice','broadcast','callforward') NOT NULL DEFAULT 'text',
  `inbox_type` tinyint(4) NOT NULL DEFAULT '1',
  `sms_status` varchar(250) NOT NULL,
  `error_message` text,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `msgsound` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`group_sms_id`),
  INDEX(`sms_id`),
  INDEX(`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_pages`
--

CREATE TABLE IF NOT EXISTS `mobile_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `header_logo` varchar(250) DEFAULT NULL,
  `header_color` varchar(250) NOT NULL,
  `headerfont_color` varchar(250) NOT NULL,
  `bodybg_color` varchar(250) NOT NULL,
  `footer_color` varchar(250) NOT NULL,
  `footertext_color` varchar(250) NOT NULL,
  `footertext` varchar(250) NOT NULL,
  `map_url` text NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_packages`
--

CREATE TABLE IF NOT EXISTS `monthly_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(50) NOT NULL,
  `package_name` varchar(250) NOT NULL,
  `amount` float NOT NULL,
  `text_messages_credit` varchar(20) NOT NULL,
  `voice_messages_credit` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  `user_country` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `optiona` varchar(250) NOT NULL,
  `optionb` varchar(50) NOT NULL,
  `autorsponder_message` varchar(250) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `credit` int(11) NOT NULL,
  `type` enum('text','voice') NOT NULL DEFAULT 'text',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_country` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `stripe config`
--

CREATE TABLE IF NOT EXISTS `stripes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secret_key` varchar(255) NOT NULL,
  `publishable_key` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stripe config`
--

INSERT INTO `stripes` (`id`, `secret_key`, `publishable_key`, `created`) VALUES
(1, '', '', now());

-- --------------------------------------------------------

--
-- Table structure for table `paypal_items`
--

CREATE TABLE IF NOT EXISTS `paypal_items` (
  `id` varchar(36) NOT NULL,
  `instant_payment_notification_id` varchar(36) NOT NULL,
  `item_name` varchar(127) DEFAULT NULL,
  `item_number` varchar(127) DEFAULT NULL,
  `quantity` varchar(127) DEFAULT NULL,
  `mc_gross` float(10,2) DEFAULT NULL,
  `mc_shipping` float(10,2) DEFAULT NULL,
  `mc_handling` float(10,2) DEFAULT NULL,
  `tax` float(10,2) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qrcods`
--

CREATE TABLE IF NOT EXISTS `qrcods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `active` int(11) NOT NULL,
  `autoreply_message` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'new_user_id',
  `referred_by` int(11) NOT NULL COMMENT 'referred_by',
  `url` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL,
  `paid_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-unpaid,1-paid',
  `type` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `account_activated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responders`
--

CREATE TABLE IF NOT EXISTS `responders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `message` varchar(250) NOT NULL,
  `systemmsg` varchar(255) NOT NULL,
  `days` int(11) NOT NULL,
  `sent` int(11) NOT NULL COMMENT '0=No,1=Yes',
  `sms_type` int(11) NOT NULL COMMENT '0=>sms , 1=>mms',
  `image_url` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_messages`
--

CREATE TABLE IF NOT EXISTS `schedule_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `systemmsg` varchar(255) NOT NULL,
  `send_on` datetime NOT NULL,
  `sent` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `rotate_number` int(11) NOT NULL,
  `msg_type` int(11) NOT NULL,
  `mms_text` text NOT NULL,
  `pick_file` varchar(200) NOT NULL,
  `throttle` int(11) NOT NULL,
  `alphasender` tinyint(4) NOT NULL,
  `alphasender_input` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_message_groups`
--

CREATE TABLE IF NOT EXISTS `schedule_message_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_sms_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shortlinks`
--

CREATE TABLE IF NOT EXISTS `shortlinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shortname` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  `short_url` varchar(250) NOT NULL,
  `clicks` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `single_schedule_messages`
--

CREATE TABLE IF NOT EXISTS `single_schedule_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_sms_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `smstemplates`
--

CREATE TABLE IF NOT EXISTS `smstemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `messagename` varchar(250) NOT NULL,
  `message_template` varchar(1588) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `recurring_paypal_email` varchar(250) DEFAULT NULL,
  `recurring_checkout_email` varchar(250) NOT NULL,
  `stripe_customer_id` varchar(50) DEFAULT NULL,
  `monthly_stripe_subscription_id` varchar(50) DEFAULT NULL,
  `monthly_number_subscription_id` varchar(50) DEFAULT NULL,
  `sms_balance` int(11) DEFAULT '0',
  `voice_balance` int(11) DEFAULT '0',
  `assigned_number` varchar(15) DEFAULT '0',
  `phone_sid` varchar(250) DEFAULT NULL,
  `country_code` varchar(20) DEFAULT NULL,
  `user_country` varchar(50) NOT NULL,
  `defaultgreeting` varchar(250) NOT NULL DEFAULT 'Leave a message for THIS VOICEMAIL at the beep and Press the star key when finished.',
  `active` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `voicemailnotifymail` varchar(50) NOT NULL,
  `apikey` varchar(50) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `email_alerts` int(11) NOT NULL COMMENT '1=''As they happen'',2=''dally summary''',
  `email_alert_options` int(11) NOT NULL COMMENT '0=on,1=off',
  `low_sms_balances` varchar(250) NOT NULL,
  `email_alert_credit_options` int(11) NOT NULL COMMENT '0=on,1=off',
  `sms_credit_balance_email_alerts` int(11) NOT NULL COMMENT '0=no,1=yes',
  `low_voice_balances` varchar(250) NOT NULL,
  `VM_credit_balance_email_alerts` int(11) NOT NULL COMMENT '0=no,1=yes',
  `account_activated` varchar(250) NOT NULL,
  `register` int(11) NOT NULL,
  `package` int(11) NOT NULL,
  `next_renewal_dates` date NOT NULL,
  `number_package` int(11) NOT NULL DEFAULT '0',
  `number_next_renewal_dates` date NOT NULL,
  `number_limit_set` int(11) NOT NULL DEFAULT '0' COMMENT '0=off,1=on',
  `pay_activation_fees_active` int(11) NOT NULL,
  `incomingsms_alerts` int(11) NOT NULL DEFAULT '1' COMMENT '0=on,1=off',
  `incomingsms_emailalerts` int(11) NOT NULL COMMENT '1=''Email'',2=''SMS''',
  `smsalerts_number` varchar(15) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `welcome_msg_type` int(11) NOT NULL,
  `mp3` varchar(255) NOT NULL,
  `api_type` int(11) NOT NULL,
  `sms` int(11) NOT NULL,
  `mms` int(11) NOT NULL,
  `voice` int(11) NOT NULL,
  `number_limit` int(11) NOT NULL DEFAULT '1',
  `number_limit_count` int(11) NOT NULL DEFAULT '0',
  `birthday_wishes` int(11) NOT NULL DEFAULT '1',
  `capture_email_name` int(11) NOT NULL DEFAULT '1',
  `broadcast` varchar(20) NOT NULL,
  `email_to_sms` int(11) NOT NULL DEFAULT '1',
  `api_url` varchar(250) DEFAULT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `partnerid` varchar(100) DEFAULT NULL,
  `partnerpassword` varchar(150) DEFAULT NULL,
  `incomingcall_forward` int(11) NOT NULL DEFAULT '1',
  `assign_callforward` varchar(20) DEFAULT NULL,
  `callforward_number` varchar(20) DEFAULT NULL,
  `IP_address` varchar(20) NOT NULL,
  `autoresponders` tinyint(4) NOT NULL DEFAULT '1',
  `importcontacts` tinyint(4) NOT NULL DEFAULT '1',
  `shortlinks` tinyint(4) NOT NULL DEFAULT '1',
  `voicebroadcast` tinyint(4) NOT NULL DEFAULT '1',
  `polls` tinyint(4) NOT NULL DEFAULT '1',
  `contests` tinyint(4) NOT NULL DEFAULT '1',
  `loyaltyprograms` tinyint(4) NOT NULL DEFAULT '1',
  `kioskbuilder` tinyint(4) NOT NULL DEFAULT '1',
  `birthdaywishes` tinyint(4) NOT NULL DEFAULT '1',
  `mobilepagebuilder` tinyint(4) NOT NULL DEFAULT '1',
  `webwidgets` tinyint(4) NOT NULL DEFAULT '1',
  `alphasender` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX(`username`),
  INDEX(`first_name`),
  INDEX(`last_name`),
  INDEX(`email`),
  INDEX(`phone`),
  INDEX(`assigned_number`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_numbers`
--

CREATE TABLE IF NOT EXISTS `user_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `phone_sid` varchar(250) NOT NULL,
  `api_type` int(11) NOT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `sms` int(11) NOT NULL DEFAULT '0',
  `mms` int(11) NOT NULL,
  `voice` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voice_messages`
--

CREATE TABLE IF NOT EXISTS `voice_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `message_type` int(11) NOT NULL,
  `text_message` text NOT NULL,
  `audio` varchar(200) NOT NULL,
  `created` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `smsloyalties`
--

CREATE TABLE IF NOT EXISTS `smsloyalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `program_name` varchar(250) NOT NULL,
  `group_id` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `coupancode` varchar(100) NOT NULL,
  `codestatus` varchar(100) NOT NULL,
  `reachgoal` int(11) NOT NULL,
  `addpoints` text NOT NULL,
  `reachedatgoal` text NOT NULL,
  `checkstatus` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1=>SMS,2=>MMS',
  `image` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `notify_punch_code` int(11) NOT NULL DEFAULT '0',
  `my_email_address` int(11) NOT NULL DEFAULT '0',
  `email_address` int(11) NOT NULL DEFAULT '0',
  `email_address_input` varchar(50) NOT NULL,
  `mobile_number` int(11) NOT NULL DEFAULT '0',
  `mobile_number_input` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalty_users`
--

CREATE TABLE IF NOT EXISTS `smsloyalty_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_key` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sms_loyalty_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `count_trial` int(11) NOT NULL,
  `is_winner` int(11) NOT NULL,
  `redemptions` int(11) NOT NULL,
  `msg_date` date NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- -------------------------------------------------------

--
-- Table structure for table `kiosks`
--

CREATE TABLE IF NOT EXISTS `kiosks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loyalty_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `background_color` varchar(100) DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `business_logo` varchar(255) NOT NULL,
  `textheader` text NOT NULL,
  `alignment` varchar(100) DEFAULT NULL,
  `font` varchar(100) DEFAULT NULL,
  `fontsize` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbuttons` int(11) DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `punchcard` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `checkpoints` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbutton` varchar(100) DEFAULT NULL,
  `checkin` varchar(100) DEFAULT NULL,
  `mypoints` varchar(100) DEFAULT NULL,
  `buttoncolor` varchar(100) DEFAULT NULL,
  `textcolor` varchar(100) DEFAULT NULL,
  `keypad_button_color` varchar(100) DEFAULT NULL,
  `keypad_text_color` varchar(100) DEFAULT NULL,
  `bottom_text` text,
  `bottom_text_alignment` varchar(100) DEFAULT NULL,
  `bottom_text_font` varchar(100) DEFAULT NULL,
  `bottom_text_size` varchar(100) DEFAULT NULL,
  `bottom_text_color` varchar(100) DEFAULT NULL,
  `bottom_text_styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `bottom_text_styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `bottom_text_styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `firstname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `lastname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `email` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `dob` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `created` datetime NOT NULL COMMENT '0=>No,1=>Yes',
  PRIMARY KEY (`id`),
  INDEX(`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_number_packages`
--

CREATE TABLE IF NOT EXISTS `monthly_number_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan` varchar(50) NOT NULL,
  `package_name` varchar(250) NOT NULL,
  `amount` float NOT NULL,
  `total_secondary_numbers` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
