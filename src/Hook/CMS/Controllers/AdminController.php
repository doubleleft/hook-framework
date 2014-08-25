<?php namespace Hook\CMS\Controllers;

use Hook\CMS\Controller;

use Hook\Http\Router;
use Hook\Http\Cookie;
use Hook\Http\Request;

class AdminController extends Controller
{
    public static function mounted($path)
    {
        Router::mount("{$path}/pages", 'Hook\CMS\Controllers\PageController');
    }

    public function index()
    {
        if (Request::isPost()) {
            Cookie::set('is_admin', 1);
            Request::redirect('/');

        } else {
            $this->render('cms/login');
        }
    }

    public function logout()
    {
        Cookie::delete('is_admin');
        Request::redirect('/');
    }

}
