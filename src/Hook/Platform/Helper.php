<?php namespace Hook\Platform;

class Helper {

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
        $tag_attributes = "";
        foreach ($attributes as $key => $value) {
            $tag_attributes .= ' ' . $key . '="' . $value . '"';
        }
        return array('<a href="/'.$args[0].'"' . $tag_attributes . '>' . $args[1] . '</a>', 'raw');
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
