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

        $result = $content;

    	extract( shortcode_atts( array(
    		'id' => '0',
            'option' => '0',
            'onclick' => '0',
    	), $atts ) );

        if (!$atts['id']){

            $result = "FlowSplit Error: flowsplit parameter 'id' is missing!";
            return $result;

        }

        if (!$atts['onclick'] && !$atts['option']){

            $result = "FlowSplit Error: flowsplit parameter 'option' or 'onclick' is missing!";
            return $result;
        }

        if ($onclick = $atts['onclick']){

        }

        if ($onclick = $atts['option']){

            $result = '<div data-flowsplit_id="'. $atts['id'] .'" data-flowsplit_option="'. $atts['option'] .'" class="flowsplit_content">' . $content . '</div>';

        }

    	return $result;
    }


}


?>