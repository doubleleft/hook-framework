<?php namespace Hook\Platform;

use Hook\Http\Input;
use Hook\Pagination\Paginator;
use Hook\Http\Router;

class Environment
{
    protected $paginator;
    protected $currentPage;

    public function getCurrentPage()
    {
        $page = (int) $this->currentPage ?: Input::get('page', 1);

        if ($page < 1 || filter_var($page, FILTER_VALIDATE_INT) === false) {
            return 1;
        }

        return $page;
    }

    public function make(array $items, $total, $perPage)
    {
        $this->paginator = new Paginator($this, $items, $total, $perPage);

        return $this->paginator->setupPaginationContext();
    }

    public function getPaginationView($paginator, $view)
    {
        $has_first = true;
        $has_last = true;
        $links = array();

        for($i=1; $i<$paginator->getLastPage(); $i++) {
            array_push($links, array(
                'i' => $i,
                'label' => $i,
                'current' => $i == $paginator->getCurrentPage()
            ));
        }

        $instance = Router::getInstance()->view;
        $instance->setData(array(
            'first' => current($links),
            'last' => end($links),
            'links' => $links
        ));
        return $instance->fetch("pagination/links");
    }

}
