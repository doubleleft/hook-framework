<?php

class CmsController extends Hook\Platform\Controller {

    public function index() {
        Hook\Http\Cookie::set('admin', 1);
        Hook\Http\Request::redirect('/hook-platform');
    }

}

