<?php

class HomeController extends Hook\Controllers\HookController {

    public function __construct() { }

    public function index() {
        $this->view('index', array(
            'items' => Hook\Model\App::collection('wats')->all()
        ));
    }

}
