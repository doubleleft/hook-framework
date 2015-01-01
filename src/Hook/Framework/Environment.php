<?php namespace Hook\Framework;

use Hook\Http\Input;
use Hook\Pagination\Paginator;
use Hook\Http\Router;

class Environment
{
    protected $paginator;
    protected $currentPage;

    /**
     * window - default pagination window
     *
     * @var int
     */
    protected $window = 4;

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

    public function setPaginationWindow($window) {
        $this->window = $window;
    }

    public function getPaginationView($paginator, $view)
    {
        $links = array();

        $current_page = $paginator->getCurrentPage();
        $inside_window = true;

        for($i=1; $i<$paginator->getLastPage(); $i++) {
            $next_inside_window = (abs($current_page - $i) <= $this->window);
            if ($inside_window || $next_inside_window) {
                array_push($links, array(
                    'i' => $i,
                    'label' => $i,
                    'current' => ($i == $current_page),
                    'inside_window' => $next_inside_window
                ));
            }
            $inside_window = $next_inside_window;
        }

        $instance = Router::getInstance()->view;
        $instance->setData(array(
            'first' => true,
            'last' => array('i' => $paginator->getLastPage()),
            'links' => $links
        ));
        return $instance->fetch("pagination/links");
    }

}
