<?php namespace Hook\Framework;

use Hook\Controllers\HookController;
use Hook\Http\Router;

class Controller extends HookController
{
    protected $layout;

    protected function view($template, $data = array())
    {
        if (!$this->layout) {
            return parent::render($template, $data);

        } else {
            $this->view->yield_blocks['__yield__'] = $this->view->render($template, $data);
            return parent::render('layouts/' . $this->layout);
        }
    }

    protected function setLayout($name) {
        $this->layout = $name;
    }

}
