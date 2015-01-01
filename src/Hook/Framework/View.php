<?php namespace Hook\Framework;

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
    public $yield_blocks;

    protected $extensions = array('.hbs', '.handlebars', '.mustache', '.html');
    protected $directories = array();

    public function __construct() {
        parent::__construct();

        $this->context = new SplStack();
        $this->yield_blocks = array();
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
            // core helpers
            'yield' => 'Hook\\Framework\\Helper::yield',

            // string helpers
            'str_plural' => 'Hook\\Framework\\Helper::str_plural',
            'str_singular' => 'Hook\\Framework\\Helper::str_singular',
            'uppercase' => 'Hook\\Framework\\Helper::uppercase',
            'lowercase' => 'Hook\\Framework\\Helper::lowercase',
            'camel_case' => 'Hook\\Framework\\Helper::camel_case',
            'snake_case' => 'Hook\\Framework\\Helper::snake_case',

            // url helpers
            'link_to' => 'Hook\\Framework\\Helper::link_to',
            'stylesheet' => 'Hook\\Framework\\Helper::stylesheet',
            'javascript' => 'Hook\\Framework\\Helper::javascript',

            // form helpers
            'input' => 'Hook\\Framework\\Helper::input',
            'select' => 'Hook\\Framework\\Helper::select',

            // integer helpers
            'count' => 'Hook\\Framework\\Helper::count',

            // miscelaneous
            'paginate' => 'Hook\\Framework\\Helper::paginate'
        );

        $helper_files = glob(Router::config('templates.helpers_path') . '/*');
        foreach($helper_files as $helper) {
            $helpers = array_merge($helpers, require($helper));
        }

        return $helpers;
    }

    protected function getBlockHelpers() {
        return array(
            // core helpers
            'content_for' => 'Hook\\Framework\\BlockHelper::content_for',

            // url helpers
            'link_to' => 'Hook\\Framework\\BlockHelper::link_to',

            // form helpers
            'form' => 'Hook\\Framework\\BlockHelper::form',
            'form_for' => 'Hook\\Framework\\BlockHelper::form_for'
        );
    }

}
