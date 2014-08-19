<?php

class HomeController extends Hook\Platform\Controller {

    public function __construct() { }

    public function index() {
        if (Request::isPost()) {
            var_dump(Input::get());
        }

        Hook\Model\App::collection('items')->create(array(
            'name' => "Item " . rand()
        ));

        $items = Hook\Model\App::collection('items');
        $this->view('index', array(
            'item' => $items->first(),
            'items' => $items->paginate(),
        ));
    }

}
