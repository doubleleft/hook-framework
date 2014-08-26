<?php namespace Hook\CMS\Controllers;

use Hook\CMS\Controller;
use Hook\CMS\Models\Page;
use Hook\Model\App;

use Hook\Http\Router;
use Hook\Http\Cookie;
use Hook\Http\Request;
use Hook\Http\Input;

use Hook\Database\Schema\Builder as SchemaBuilder;

class PageController extends Controller
{
    const PAGES_COLLECTION = 'cms_pages';
    public static $page;

    public static function mounted()
    {
        Router::hook('slim.before.router', function() {
            if (SchemaBuilder::hasTable(self::PAGES_COLLECTION)) {
                $page = App::collection(self::PAGES_COLLECTION)->where('slug', Request::path())->first() ?:
                    App::collection(self::PAGES_COLLECTION)->where('name', '404')->first();

                Router::any(Request::path(), 'Hook\\CMS\\Controllers\\PageController:show');
                PageController::$page = $page;
            }
        });
    }

    public function show()
    {
        $page = static::$page;

        if (!$page) {
            return $this->show404();
        }

        $this->render("layouts/{$page->layout}");
    }

    public function show404()
    {
        if (Request::isPost()) {
            $page = App::collection(self::PAGES_COLLECTION)->create(Input::get('page'));
            Request::redirect($page->slug);
        }

        $layouts = array();
        $layout_files = glob(Router::config('paths')['root'] . 'app/views/layouts/*');

        foreach ($layout_files as $layout_file) {
            $layout_ext = pathinfo($layout_file, PATHINFO_EXTENSION);
            $layout_name = basename($layout_file, '.' . $layout_ext);
            $words = preg_split('/_/', $layout_name);
            $layout_label = join(' ', array_map(function($word) {
                return ucfirst($word);
            }, $words));

            $layouts[$layout_name] = $layout_label;
        }

        $this->render('cms/404', array(
            'request_path' => Request::path(),
            'layouts' => $layouts
        ));
    }

}
