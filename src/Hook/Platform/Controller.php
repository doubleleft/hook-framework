<?php namespace Hook\Platform;

use Hook\Controllers\HookController;
use Hook\Http\Router;

class Controller extends HookController {

    protected function registerHelpers() {
        $target = snake_case(get_class($this));
        if (file_exists(Router::config('root') . "helpers/{$target}.php")) {
        }
    }

}
