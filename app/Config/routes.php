<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/goods', array('controller' => 'goods', 'action' => 'index'));
	Router::connect('/goods/add/*', array('controller' => 'goods', 'action' => 'add'));
	Router::connect('/goods/edit/*', array('controller' => 'goods', 'action' => 'edit'));
	Router::connect('/goods/delete/*', array('controller' => 'goods', 'action' => 'delete'));
	Router::connect('/goods/*', array('controller' => 'goods', 'action' => 'view'));

	Router::connect('/users', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/users/add/*', array('controller' => 'users', 'action' => 'add'));
	Router::connect('/users/edit/*', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/users/delete/*', array('controller' => 'users', 'action' => 'delete'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/users/*', array('controller' => 'users', 'action' => 'view'));

	Router::connect('/roles', array('controller' => 'roles', 'action' => 'index'));
	Router::connect('/roles/add/*', array('controller' => 'roles', 'action' => 'add'));
	Router::connect('/roles/edit/*', array('controller' => 'roles', 'action' => 'edit'));
	Router::connect('/roles/delete/*', array('controller' => 'roles', 'action' => 'delete'));
	Router::connect('/roles/*', array('controller' => 'roles', 'action' => 'view'));		

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
