<?php namespace Hook\Platform;

use Hook\Http\Router;
use Hook\Platform\Helper;

class BlockHelper {

    public static function form_for($context, $options) {
        if (!isset($options['hash'])) { $options['hash']['method'] = 'get'; }

        Router::getInstance()->view->context->push($context);
        $html = '<form' . html_attributes($options['hash']) . '>' . "\n" .
            $options['fn']() .
        '</form>';
        Router::getInstance()->view->context->pop();

        return $html;
    }

}

