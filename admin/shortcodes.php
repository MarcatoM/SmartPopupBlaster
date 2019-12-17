<?php

function spb_button($atts)
{

    $atts = shortcode_atts(array(
        'id'    => null,
        'text'  => 'Click Me!',
        'class' => '',
    ), $atts, 'spb-button');

    $button = '';
    $button .= '<button';
    $button .= ' class="show-popup ' . $atts['class'] . '"';
    $button .= ' data-id="' . $atts['id'] . '">';
    $button .= $atts['text'];
    $button .= '</button>';

    return $button;
}

add_shortcode('spb-button', 'spb_button');
