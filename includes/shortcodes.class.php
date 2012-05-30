<?php

$flowsplit_shortcodes = new FlowSplit_ShortCodes();

/**
 * ShortCodes initialized
 */
class FlowSplit_ShortCodes{

    function __construct(){

        add_action('init', array(&$this, 'add_shortcodes'));

    }

    function add_shortcodes(){

        add_filter('widget_text', 'do_shortcode');
        add_shortcode( 'flowsplit', array(&$this, 'flowsplit') );

    }

    function flowsplit( $atts, $content = null ) {

    	extract( shortcode_atts( array(
    		'test' => '0',
            'option' => '0',
            'onclick' => '0',
    	), $atts ) );

    	return "Result = " . $content;
    }


}


?>