<?php namespace Hook\Admin\Controllers;

use Hook\Framework\Controller;

use Hook\Http\Request;
use Hook\Http\Cookie;

class AdminController extends Controller
{
    protected function before() {
        $this->view->setTemplatesDirectory(__DIR__ . '/../views');
        $this->setLayout( Cookie::get('is_admin') == 1 ? 'admin/authenticated' : 'admin/unauthenticated' );
    }

    public function index() {
        if (Request::isPost()) {
            Cookie::set('is_admin', 1);
            Request::redirect('admin');
        }

        $this->render('admin/login');
    }

    public function getLogout() {
        Cookie::delete('is_admin');
        Request::redirect('/admin');
    }

}
