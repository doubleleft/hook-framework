<?php namespace Hook\Platform;

use Hook\Exceptions\NotFoundException;

use LightnCandy;
use LCRun3;

class View extends \Slim\View
{
    public $parserDirectory = null;
    public $options = array();
    protected $extensions = array('.html', '.mustache', '.hbs', '.handlebars');
    private $engine = null;

    public function render($name, $data = null)
    {
        $php = LightnCandy::compile($this->getTemplate($name), array(
            'flags' => LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_ERROR_LOG |
                LightnCandy::FLAG_INSTANCE |
                LightnCandy::FLAG_MUSTACHE |
                LightnCandy::FLAG_HANDLEBARS,
            'basedir' => $this->getTemplatesDirectory(),
            'fileext' => $this->extensions,
            'helpers' => $this->getHelpers()
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

    protected function getHelpers() {
        $app = \Slim\Slim::getInstance();

        $helpers = array(
            // string helpers
            'str_plural' => 'Hook\\Platform\\Helper::str_plural',
            'str_singular' => 'Hook\\Platform\\Helper::str_singular',
            'uppercase' => 'Hook\\Platform\\Helper::uppercase',
            'lowercase' => 'Hook\\Platform\\Helper::lowercase',

            // integer helpers
            'count' => 'Hook\\Platform\\Helper::count'
        );

        $helper_files = glob($app->config('templates.helpers_path') . '/*');
        foreach($helper_files as $helper) {
            $helpers = array_merge($helpers, require($helper));
        }

        return $helpers;
    }

}
