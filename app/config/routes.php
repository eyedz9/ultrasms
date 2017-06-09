<?php

/**

 * Routes configuration

 *

 * In this file, you set up routes to your controllers and their actions.

 * Routes are very important mechanism that allows you to freely connect

 * different urls to chosen controllers and their actions (functions).

 *

 * PHP versions 4 and 5

 *

 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)

 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)

 *

 * Licensed under The MIT License

 * Redistributions of files must retain the above copyright notice.

 *

 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)

 * @link          http://cakephp.org CakePHP(tm) Project

 * @package       cake

 * @subpackage    cake.app.config

 * @since         CakePHP(tm) v 0.2.9

 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)

 */

/**

 * Here, we are connecting '/' (base path) to controller called 'Pages',

 * its action called 'display', and we pass a param to select the view file

 * to use (in this case, /app/views/pages/home.ctp)...

 */

	Router::connect('/', array('controller' => 'users', 'action' => 'home'));

	Router::connect('/admin', array('controller' => 'admin_users', 'action' => 'login'));

/**

 * ...and connect the rest of 'Pages' controller's urls.

 

 

 */

 Router::connect('/weblinks', array('controller' => 'webwidgets'));

	Router::connect('/weblinks/:action/*', array('controller' => 'webwidgets', 'action' => 'index'));

	//Router::connect('/pages/*', array('controller' => 'pages'));

	Router::connect('/page1', array('controller' => 'pages', 'action' => 'page1'));

	

	Router::connect('/page2', array('controller' => 'pages', 'action' => 'page2'));

	

	/* Paypal IPN plugin */

	  Router::connect('/paypal_ipn/process', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'process'));

	  Router::connect('/paypal_ipn/purchase_credit/*', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'purchase_credit'));

Router::connect('/paypal_ipn/purchase_subscription/*', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'purchase_subscription'));

Router::connect('/paypal_ipn/purchase_subscription_numbers/*', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'purchase_subscription_numbers'));


	  /* Optional Route, but nice for administration */

	  Router::connect('/paypal_ipn/:action/*', array('admin' => 'true', 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'index'));

  /* End Paypal IPN plugin */

