<?php namespace Hook\CMS;

class Helper {

    public static function block($args, $named)
    {
        $block_name = $args[0];
        return $block_name;
    }

}

