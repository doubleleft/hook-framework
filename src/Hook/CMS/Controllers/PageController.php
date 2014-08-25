<?php namespace Hook\CMS\Controllers;

use Hook\CMS\Controller;
use Hook\CMS\Models\Page;
use Hook\Model\App;

use Hook\Http\Router;
use Hook\Http\Cookie;
use Hook\Http\Request;

class PageController extends Controller
{
    const PAGES_COLLECTION = 'cms_pages';
    public static $page;

    public static function mounted()
    {
        // Router::hook('slim.before.router', function() {
        //     $page = App::collection(self::PAGES_COLLECTION)->where('slug', Request::path())->first();
        //     if (!$page) {
        //         $page = App::collection(self::PAGES_COLLECTION)->where('identifier', '404')->first();
        //     }
        //     Router::get($page->slug, 'Hook\\CMS\\Controllers\\PageController:show');
        //     PageController::$page = $page;
        // });
    }

    public function show()
    {
        $page = static::$page;
        $this->setLayout($page->layout);

        $items = App::collection('items');
        $this->render('index', array(
            'item' => $items->first(),
            'items' => $items->paginate()
        ));

    }
}
