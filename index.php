<?php
// Is running on development server?
// php -S localhost index.php
if (isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/Development Server/', $_SERVER['SERVER_SOFTWARE'])) {
    // allow to serve static files
    $path = __DIR__ . $_SERVER["REQUEST_URI"];
    if (file_exists($path) && is_file($path)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/vendor/doubleleft/hook/src/Hook.php';

$app->config('database', require(__DIR__ . '/config/database.php'));
$app->config('paths', require(__DIR__ . '/config/paths.php'));

require __DIR__ . '/vendor/doubleleft/hook/src/bootstrap/connection.php';

// setup custom pagination environment
$connection = \DLModel::getConnectionResolver()->connection();
$connection->setPaginator(new Hook\Platform\Environment());

// Set application key
$app_key = null;
$config_keys = __DIR__ . '/config/keys.php';

// auto-set app_id/key
if (!$app->request->headers->get('X-App-Id'))
{
    // Create default application if it doesn't exists
    if (Hook\Model\AppKey::count() == 0)
    {
        $application = Hook\Model\App::create(array('name' => "Application"));

        // Migrate application tables
        Hook\Database\AppContext::setKey($application->keys[0]);
        Hook\Database\AppContext::migrate();
        Hook\Database\AppContext::setTablePrefix('');
    }

    if ($app->request->isAjax()) {
        $app_key = Hook\Model\AppKey::where('type', Hook\Model\AppKey::TYPE_BROWSER)->first();
    } else {
        $app_key = Hook\Model\AppKey::where('type', Hook\Model\AppKey::TYPE_SERVER)->first();
    }

    $app->request->headers->set('X-App-Id', $app_key->app_id);
    $app->request->headers->set('X-App-Key', $app_key->key);
}

Hook\Http\Router::setInstance($app);
Hook\Http\Router::setup($app);

class_alias('Hook\\Http\\Input', 'Input');
class_alias('Hook\\Http\\Request', 'Request');

$app->config("templates.path", $app->config('paths')['root'] . "views");
$app->config("templates.helpers_path", __DIR__ . '/helpers');
$app->config("view", new Hook\Platform\View());
require 'routes.php';
$app->run();
