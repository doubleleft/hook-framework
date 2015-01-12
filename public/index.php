<?php
define('ROOT_DIR', __DIR__ . '/../');

// Is running on development server?
// php -S localhost:4665 -t public
if (isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/Development Server/', $_SERVER['SERVER_SOFTWARE'])) {
    // allow to serve static files
    $path = getcwd() . $_SERVER["REQUEST_URI"];
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    if ($extension !== 'php' && file_exists($path) && is_file($path)) {
        return false;
    }
}

require ROOT_DIR . '/vendor/autoload.php';
require ROOT_DIR . '/vendor/doubleleft/hook/src/bootstrap/helpers.php'; // hook helpers
require ROOT_DIR . '/src/Hook/helpers.php'; // hook-framework helpers

$config = require(ROOT_DIR . '/config/preferences.php');
date_default_timezone_set($config['timezone']);

if ($config['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$config['log.enabled'] = $config['debug'];

// Merge settings with security config
$config = array_merge($config, require(ROOT_DIR . '/config/security.php'));

$app = new \Slim\Slim($config);
$app->config('database', require(ROOT_DIR . '/config/database.php'));
$app->config('paths', require(ROOT_DIR . '/config/paths.php'));
Hook\Http\Router::setInstance($app);

require ROOT_DIR . '/vendor/doubleleft/hook/src/bootstrap/connection.php';

// setup custom pagination environment
$connection = \DLModel::getConnectionResolver()->connection();
$connection->setPaginator(new Hook\Framework\Environment());

// Set application key
$app_key = null;
$config_keys = ROOT_DIR . '/config/keys.php';

// auto-set app_id/key
if (!$app->request->headers->get('X-App-Id'))
{
    // Create default application if it doesn't exists
    if (Hook\Model\AppKey::count() == 0)
    {
        $application = Hook\Model\App::create(array('name' => "Application"));

        $hook_config = $application->keys[0]->toArray();
        $hook_config['endpoint'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
        file_put_contents($app->config('paths')['root'] . '.hook-config', json_encode($hook_config));

        // Migrate application tables
        Hook\Application\Context::setKey($application->keys[0]);
        Hook\Application\Context::migrate();
        Hook\Application\Context::setTablePrefix('');
    }

    if ($app->request->isAjax()) {
        $app_key = Hook\Model\AppKey::where('type', Hook\Model\AppKey::TYPE_BROWSER)->first();
    } else {
        $app_key = Hook\Model\AppKey::where('type', Hook\Model\AppKey::TYPE_SERVER)->first();
    }

    $app->request->headers->set('X-App-Id', $app_key->app_id);
    $app->request->headers->set('X-App-Key', $app_key->key);
}

foreach($app->config('aliases') as $alias => $source) {
    class_alias($source, $alias);
}

// configure view/template library (lightncandy)
$app->config("templates.path", $app->config('paths')['root'] . "app/views");
$app->config("templates.helpers_path", ROOT_DIR . '/app/helpers');
$app->config("view", new Hook\Framework\View());

// bind application routes
require ROOT_DIR . 'config/routes.php';

$app->run();
