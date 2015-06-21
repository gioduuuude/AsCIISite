<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

	CakePlugin::loadAll(); // Loads all plugins at once
	CakePlugin::load('DebugKit');
	// Utility should be loaded first
	// The Utility and Admin plugin must be loaded before the Forum
	CakePlugin::load('Utility', array('bootstrap' => true, 'routes' => true));
	CakePlugin::load('Admin', array('bootstrap' => true, 'routes' => true));
	CakePlugin::load('Forum', array('bootstrap' => true, 'routes' => true));

	// Configure the plugin after it has been loaded
	Configure::write('Forum.settings', array(
		'name' => 'AsCII Forum',
		'email' => 'email@website.com',
		'url' => ''
	) + Configure::read('Forum.settings'));

/**
 * If the users table has different column names than the ones defined in the plugin, 
 * one can override the settings in the bootstrap. The User.fieldMap (Forum.userMap in v3.x) 
 * contains a mapping of specific fields that the plugin uses, while the User.statusMap (Forum.statusMap in v3.x) 
 * are mappings of a users state. Below is an example of some custom mappings.
 */
//	Configure::write('User.fieldMap', array(
//		'username' => 'user',
//		'password' => 'pass',
//		'email'    => 'mail',
//		'status'   => 'active',
//		'avatar'   => 'picture'
//	) + Configure::read('User.fieldMap'));
//
//	Configure::write('User.statusMap', array(
//		'pending' => 0,
//		'active'  => 1,
//		'banned'  => 2
//	) + Configure::read('User.statusMap'));

//	To change the layout, override the Forum.viewLayout option. The default layout is forum.
//	Configure::write('Forum.viewLayout', 'default');

/**
 * The forum comes pre-bundled with a list of settings that include flood intervals, page limits, site title, 
 * site email, security questions and many more. One can modify these settings by overriding Forum.settings 
 * with Configure. The full list of settings can be found within the plugins bootstrap file. When editing multiple 
 * settings with an array, be sure to merge in the current settings array.
 */
	Configure::write('Forum.settings.whosOnlineInterval', '-5 minutes');

	Configure::write('Forum.settings', array(
		'topicsPerPage' => 15,
		'postsPerPage' => 10
	) + Configure::read('Forum.settings'));

/**
 * The transform settings should define an array for path_thumb and path_large. Here are the default settings. 
 * There are 2 possible values to use for nameCallback: formatName which is an MD5 of the original file name, 
 * and formatTransformName which uses the original files name without appended or prepended strings.
 */
	Configure::write('Admin.uploads.transforms', array(
		'path_thumb' => array(
			'method' => 'crop',
			'nameCallback' => 'formatTransformName',
			'append' => '-thumb',
			'overwrite' => true,
			'width' => 250,
			'height' => 150
		),
		'path_large' => array(
			'method' => 'resize',
			'nameCallback' => 'formatTransformName',
			'append' => '-large',
			'overwrite' => true,
			'aspect' => true,
			'width' => 800,
			'height' => 600
		)
	));
/**
 * The transport settings should define an array of remote storage settings. Here's a quick example for Amazon S3.
 */
//	Configure::write('Admin.uploads.transport', array(
//		'class' => 's3',
//		'accessKey' => '<access>',
//		'secretKey' => '<secret>',
//		'region' => 'us-east-1',
//		'bucket' => '<bucket>',
//		'folder' => 'uploads/'
//	));

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
