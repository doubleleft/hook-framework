<?php namespace Hook\Platform;

use Hook\Controllers\HookController;
use Hook\Http\Router;

class Controller extends HookController
{
    protected $layout;

    public function render($template, $data = array())
    {
        if (!$this->layout) {
            return parent::render($template, $data);

        } else {
            return parent::render('layouts/' . $this->layout, array(
                'yield' => $this->view->render($template, $data)
            ));
        }
    }

    public function setLayout($name) {
        $this->layout = $name;
    }

}
