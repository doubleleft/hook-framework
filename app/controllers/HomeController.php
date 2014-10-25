<?php

class HomeController extends Controller {
    protected $layout = 'main';

    public function index() {
        if (Request::isPost()) {
            var_dump(Input::get());
        }

        App::collection('items')->create(array(
            'name' => "Item " . rand()
        ));

        $items = App::collection('items');
        $this->view('index', array(
            'item' => App::collection('posts')->all(),
            'items' => App::collection('posts')->paginate()
        ));
    }

}
