<?php namespace Hook\Admin;

use Hook\Http\Router;

class Routes
{

    public static function mounted($path) {
        Router::mount('/admin', '\\Hook\\Admin\\Controllers\\AdminController');
    }

}
