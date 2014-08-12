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

}
