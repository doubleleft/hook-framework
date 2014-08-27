<?php namespace Hook\Platform;

use Hook\Http\Router;
use Hook\Exceptions\NotFoundException;

use LightnCandy;
use LCRun3;

use SplStack;

class View extends \Slim\View
{
    /**
     * helpers
     *
     * @var \Slim\Helper\Set
     */
    public $helpers;

    /**
     * block_helpers
     *
     * @var \Slim\Helper\Set
     */
    public $block_helpers;

    /**
     * context
     *
     * @var SplStack
     */
    public $context;

    protected $extensions = array('.hbs', '.handlebars', '.mustache', '.html');
    protected $directories = array();

    public function __construct() {
        parent::__construct();

        $this->context = new SplStack();
        $this->helpers = new \Slim\Helper\Set($this->getHelpers());
        $this->block_helpers = new \Slim\Helper\Set($this->getBlockHelpers());
    }

    public function setTemplatesDirectory($directory) {
        array_push($this->directories, $directory);
        return $this;
    }

    public function render($name, $data = null) {
        $php = LightnCandy::compile($this->getTemplate($name), array(
            'flags' => LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_ERROR_LOG |
                LightnCandy::FLAG_INSTANCE |
                LightnCandy::FLAG_MUSTACHE |
                LightnCandy::FLAG_HANDLEBARS,
            'basedir' => $this->directories,
            'fileext' => $this->extensions,
            'helpers' => $this->helpers->all(),
            'hbhelpers' => $this->block_helpers->all()
        ));

        $renderer = LightnCandy::prepare($php);
        return $renderer($data ?: $this->all(), LCRun3::DEBUG_ERROR_LOG);
    }

    protected function getTemplate($name) {
        foreach ($this->directories as $dir) {
            foreach ($this->extensions as $ext) {
                $path = $dir . DIRECTORY_SEPARATOR . ltrim($name . $ext, DIRECTORY_SEPARATOR);
                if (file_exists($path)) {
                    return file_get_contents($path);
                }
            }
        }
        throw new NotFoundException("Template not found.");
    }

    protected function getHelpers() {
        $helpers = array(
            // string helpers
            'str_plural' => 'Hook\\Platform\\Helper::str_plural',
            'str_singular' => 'Hook\\Platform\\Helper::str_singular',
            'uppercase' => 'Hook\\Platform\\Helper::uppercase',
            'lowercase' => 'Hook\\Platform\\Helper::lowercase',
            'camel_case' => 'Hook\\Platform\\Helper::camel_case',
            'snake_case' => 'Hook\\Platform\\Helper::snake_case',

            // url helpers
            'link_to' => 'Hook\\Platform\\Helper::link_to',

            // form helpers
            'input' => 'Hook\\Platform\\Helper::input',
            'select' => 'Hook\\Platform\\Helper::select',

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

    protected function getBlockHelpers() {
        return array(
            'form' => 'Hook\\Platform\\BlockHelper::form',
            'form_for' => 'Hook\\Platform\\BlockHelper::form_for'
        );
    }

}
