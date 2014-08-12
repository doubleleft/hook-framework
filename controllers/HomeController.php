<?php

class HomeController extends Hook\Platform\Controller {

    public function __construct() { }

    public function index() {
        if (Request::isPost()) {
            var_dump(Input::get());
        }

        $wats = Hook\Model\App::collection('wats')->all();
        $this->view('index', array(
            'item' => $wats[0],
            'items' => $wats,
        ));
    }

}
