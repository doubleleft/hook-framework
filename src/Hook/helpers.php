<?php

/**
 * html_attributes
 *
 * @param array $attributes
 * @return string
 */
function html_attributes($attributes) {
    $tag_attributes = "";
    foreach ($attributes as $key => $value) {
        $tag_attributes .= ' ' . $key . '="' . $value . '"';
    }
    return $tag_attributes;
}
