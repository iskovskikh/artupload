<?php
$starttime = microtime(true);
define('VERSION', '2.3.0.2');

// Configuration
if (is_file('../config.php')) {
	require_once('../config.php');
}

require_once(DIR_SYSTEM . 'startup.php');

$application_config = 'admin';

$starttime = microtime(true);
$registry = new Registry();

// Config
$config = new Config();
$config->load('default');
$config->load($application_config);
$registry->set('config', $config);

// Timezone
if (!ini_get('date.timezone') && $config->has('config_timezone')) {
	date_default_timezone_set($config->get('config_timezone'));
}

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		$event->register($key, new Action($value));
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {
	$registry->set('db', new DB($config->get('db_type'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
}

// Session
$session = new Session();

if ($config->get('session_autostart')) {
	$session->start();
}

$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

// Url
if ($config->get('url_autostart')) {
	$registry->set('url', new Url($config->get('site_base'), $config->get('site_ssl')));
}

// Language
$language = new Language($config->get('language_default'));
$language->load($config->get('language_default'));
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Config Autoload
if ($config->has('config_autoload')) {
	foreach ($config->get('config_autoload') as $value) {
		$loader->config($value);
	}
}

// Language Autoload
if ($config->has('language_autoload')) {
	foreach ($config->get('language_autoload') as $value) {
		$loader->language($value);
	}
}

// Library Autoload
if ($config->has('library_autoload')) {
	foreach ($config->get('library_autoload') as $value) {
		$loader->library($value);
	}
}

// Model Autoload
if ($config->has('model_autoload')) {
	foreach ($config->get('model_autoload') as $value) {
		$loader->model($value);
	}
}

// Front Controller
$controller = new Front($registry);

// Pre Actions
if ($config->has('action_pre_action')) {
	foreach ($config->get('action_pre_action') as $value) {
		$controller->addPreAction(new Action($value));
	}
}

// Dispatch
$controller->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));


class Api{
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
};

$api = new Api($registry);


$api->load->model('catalog/category');

$data = [
    'parent_id'		=> $api->model_catalog_category->getCategoryIdByHash(md5($parentid)),
	'top'			=> '0',
	'column'		=> '0',
	'sort_order'	=> 0,
	'status'		=> '1',
	'myhash' 		=> md5($id),
	'language_id' 	=> '2',
	#'keyword' 		=> ($keyword == '') ? Translit::toUrl($name) : $keyword,

	'category_description' => [
		'2' => [
			'name' 				=> $name,
			'meta_title' 		=> $name,
			'meta_description' 	=> $description,
			'meta_keyword' 		=> $name,
			'description' 		=> $description,
			],
		],
	'category_filter' => [],
	'category_layout' => [
		'0' => '0',
		],
	'category_store' => [
		'0' => '0',
		],
	];
]

//$api->model_catalog_category->addCategories($data);



$endtime = microtime(true);
print('Программа выполнена за ' . ($endtime-$starttime) . PHP_EOL);