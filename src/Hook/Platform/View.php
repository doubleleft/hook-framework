<?php namespace Hook\Platform;

use Hook\Http\Router;
use Hook\Exceptions\NotFoundException;

use LightnCandy;
use LCRun3;

class View extends \Slim\View
{
    /**
     * helpers
     *
     * @var \Slim\Helper\Set
     */
    public $helpers;

    protected $extensions = array('.html', '.mustache', '.hbs', '.handlebars');

    public function __construct() {
        parent::__construct();
        $this->helpers = new \Slim\Helper\Set($this->getDefaultHelpers());
    }

    public function render($name, $data = null)
    {
        $php = LightnCandy::compile($this->getTemplate($name), array(
            'flags' => LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_ERROR_LOG |
                LightnCandy::FLAG_INSTANCE |
                LightnCandy::FLAG_MUSTACHE |
                LightnCandy::FLAG_HANDLEBARS,
            'basedir' => $this->getTemplatesDirectory(),
            'fileext' => $this->extensions,
            'helpers' => $this->helpers->all()
        ));

        $renderer = LightnCandy::prepare($php);
        return $renderer($this->all(), LCRun3::DEBUG_ERROR_LOG);
    }

    protected function getTemplate($name) {
        foreach ($this->extensions as $ext) {
            $path = $this->getTemplatePathname($name . $ext);
            if (file_exists($path)) {
                return file_get_contents($path);
            }
        }

        throw new NotFoundException("Template not found.");
    }

    protected function getDefaultHelpers() {
        $helpers = array(
            // string helpers
            'str_plural' => 'Hook\\Platform\\Helper::str_plural',
            'str_singular' => 'Hook\\Platform\\Helper::str_singular',
            'uppercase' => 'Hook\\Platform\\Helper::uppercase',
            'lowercase' => 'Hook\\Platform\\Helper::lowercase',
            'camel_case' => 'Hook\\Platform\\Helper::camel_case',
            'snake_case' => 'Hook\\Platform\\Helper::snake_case',

            // integer helpers
            'count' => 'Hook\\Platform\\Helper::count',

            // miscelaneous
            'paginate' => 'Hook\\Platform\\Helper::paginate'
        );

        $helper_files = glob(Router::config('templates.helpers_path') . '/*');
        foreach($helper_files as $helper) {
            $helpers = array_merge($helpers, require($helper));
        }

        return $helpers;
    }

}
