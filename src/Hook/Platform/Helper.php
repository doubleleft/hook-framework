<?php namespace Hook\Platform;

use Hook\Http\Router;

class Helper {

    //
    // Core helpers
    //

    public static function yield($args) {
        $content = isset($args[0]) ? $args[0] : '__yield__';
        return array(Router::getInstance()->view->yield_blocks[$content], 'raw');
    }

    //
    // String helpers
    //

    public static function lowercase($args) {
        return strtolower($string[0]);
    }

    public static function uppercase($args) {
        return strtoupper($string[0]);
    }

    public static function str_singular($args) {
        return str_singular($args[0]);
    }

    public static function str_plural($args) {
        return str_plural($args[0]);
    }

    public static function snake_case($args) {
        return snake_case($args[0]);
    }

    public static function camel_case($args) {
        return camel_case($args[0]);
    }

    //
    // URL helpers
    //

    public static function link_to($args, $attributes) {
        return array('<a href="/'.$args[0].'"' . html_attributes($attributes) . '>' . $args[1] . '</a>', 'raw');
    }

    //
    // Form helpers
    //

    public static function input($args, $attributes) {
        if (!isset($attributes['name']) && isset($args[0])) {
            // TODO: analyse context recursively
            if (Router::getInstance()->view->context->count() > 0) {
                $attributes['name'] = Router::getInstance()->view->context->top() . '['.$args[0].']';
            } else {
                $attributes['name'] = $args[0];
            }
        }

        if (isset($attributes['options'])) {
            return \Hook\Platform\Helper::select($args, $attributes);
        }

        // use 'text' as default input type
        if (!isset($attributes['type'])) {
            $is_type_as_name = in_array($attributes['name'], array('email', 'password', 'date'));
            $attributes['type'] = $is_type_as_name ? $attributes['name'] : 'text';
        }

        return array('<input' . html_attributes($attributes) . ' />', 'raw');
    }

    public static function select($args, $attributes) {
        $options = array_remove($attributes, 'options');
        $selected_option = array_remove($attributes, 'selected');

        $html_options = '';
        foreach($options as $key => $value) {
            $key = isset($value['_id']) ? $value['_id'] : $key;
            $value = isset($value['name']) ? $value['name'] : $value;
            $is_selected = ($selected_option == $value) ? ' selected="selected"' : '';
            $html_options .= '<option value="' . $key . '"' . $is_selected . '>' . $value . '</option>';
        }

        return array('<select' . html_attributes($attributes) . '>'.$html_options.'</select>', 'raw');
    }

    //
    // Integer helpers
    //

    public static function count($args) {
        return count($args[0]);
    }

    //
    // Miscelaneous helpers
    //

    public static function paginate($args, $named) {
        $collection = $args[0];

        if (!method_exists($collection, 'links')) {
            return "paginate: must have 'links' method.";
        }

        return $args[0]->links();
    }

}
