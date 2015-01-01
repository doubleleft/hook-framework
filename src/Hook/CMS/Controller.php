<?php namespace Hook\CMS;

use Hook\Framework\Controller as BaseController;

use Hook\Http\Router;
use Hook\Http\Cookie;
use Hook\Http\Request;

class Controller extends BaseController
{

    protected function before()
    {
        if (Cookie::get('is_admin') == 1) {
            $this->view->set('is_admin', 1);
        }

        $this->view->helpers->set('block', 'Hook\\CMS\\Helper::block');
        $this->view->setTemplatesDirectory(__DIR__ . '/views');
    }

    public function index()
    {
        Cookie::set('admin', 1);
        Request::redirect('/hook-framework');
    }

}
