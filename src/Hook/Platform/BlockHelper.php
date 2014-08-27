<?php namespace Hook\Platform;

use Hook\Http\Router;
use Hook\Platform\Helper;

class BlockHelper {

    public static function form() {
        $args = func_get_args();
        $options = array_pop($args);

        // use GET method as default
        if (!isset($options['hash']['method'])) {
            $options['hash']['method'] = 'get';
        }

        $html = '<form' . html_attributes($options['hash']) . '>' . "\n" .
            $options['fn']() .
        '</form>';

        return $html;
    }

    public static function form_for($context, $options) {
        Router::getInstance()->view->context->push($context);
        $html = \Hook\Platform\BlockHelper::form($context, $options);
        Router::getInstance()->view->context->pop();
        return $html;
    }

}

