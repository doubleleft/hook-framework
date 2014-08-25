<?php

class HomeController extends Hook\CMS\Controller {
    protected $layout = 'main';

    public function index() {
        if (Request::isPost()) {
            var_dump(Input::get());
        }

        App::collection('items')->create(array(
            'name' => "Item " . rand()
        ));

        $items = App::collection('items');
        $this->render('index', array(
            'item' => $items->first(),
            'items' => $items->paginate()
        ));
    }

}
