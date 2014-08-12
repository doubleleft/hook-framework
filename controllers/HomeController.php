<?php

class HomeController extends Hook\Platform\Controller {

    public function __construct() { }

    public function index() {
        $wats = Hook\Model\App::collection('wats')->all();
        $this->view('index', array(
            'item' => $wats[0],
            'items' => $wats,
        ));
    }

}
