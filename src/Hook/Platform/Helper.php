<?php namespace Hook\Platform;

class Helper {

    public static function count($args) {
        return count($args[0]);
    }

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

    public static function paginate($args, $named) {
        $collection = $args[0];

        if (!method_exists($collection, 'links')) {
            return "paginate: must have 'links' method.";
        }

        return $args[0]->links();
    }

}
