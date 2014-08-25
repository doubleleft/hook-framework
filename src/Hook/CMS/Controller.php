<?php namespace Hook\CMS;

use Hook\Platform\Controller as BaseController;

use Hook\Http\Router;
use Hook\Http\Cookie;
use Hook\Http\Request;

class Controller extends BaseController
{

    public function before()
    {
        if (Cookie::get('admin') == 1) {
            $this->view->set('admin', 1);
        }

        $this->view->helpers->set('block', 'Hook\\CMS\\Helper::block');
    }

    public function index()
    {
        Cookie::set('admin', 1);
        Request::redirect('/hook-platform');
    }

}
