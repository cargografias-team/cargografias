<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

 	Router::parseExtensions();

	Router::connect('/admin',  array('controller' => 'users', 'action' => 'login', 'users/login'));

	Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'users/login'));

	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', 'users/logout'));

	Router::connect('/proyecto', array('controller' => 'pages', 'action' => 'display', 'proyecto'));

	Router::connect('/open-data', array('controller' => 'pages', 'action' => 'display', 'open-data'));

	Router::connect('/cargografia/*', array('controller' => 'cargografias', 'action' => 'view'));

	Router::connect('/admin/:controller/:action/*', array('admin' => true, 'prefix' => 'admin'));

	Router::connect('/', array('controller' => 'stats', 'action' => 'home'));
	Router::connect('/v2/home', array('controller' => 'newstats', 'action' => 'home'));

	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/personas/:id/:action', array('controller' => 'personas'),
	 	array('id' => '[0-9]+', 'pass' => array('id'))
	);

	Router::connect('/partidos/:id/:action', array('controller' => 'partidos'),
	 	array('id' => '[0-9]+', 'pass' => array('id'))
	);

	//API
/*	Router::connect('/api/partidos/:page', array('controller' => 'partidos', 'action' => 'index'));
	Router::connect('/api/partido/:id', array('controller' => 'partidos', 'action' => 'view'));
*/
	Router::connect('/api/hito/*', array('controller' => 'hitos', 'action' => 'view'));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

//GET	/recipes.format	RecipesController::index()
//GET	/recipes/123.format	RecipesController::view(123)

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
