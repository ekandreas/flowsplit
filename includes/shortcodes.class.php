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
        add_shortcode( 'flowsplit_charts', array(&$this, 'flowsplit_charts') );

    }

    function flowsplit_charts(){

        $tools = new FlowSplit_Tools();
        $tools->public_display();

    }

    function flowsplit( $atts, $content = null ) {

        $result = $content;

    	extract( shortcode_atts( array(
    		'id' => '0',
            'option' => '0'
    	), $atts ) );

        if (!$atts['id']){

            $result = "FlowSplit Error: flowsplit parameter 'id' is missing!";
            return $result;

        }

        if (!$atts['option']){

            $result = "FlowSplit Error: flowsplit parameter 'option' is missing!";
            return $result;
        }

        $change = false;
        $splits = get_transient( 'flowsplit' );
        if (!is_array($splits)) {
            $splits = array();
            $change=true;
        }
        if (!in_array($atts['id'],$splits)) {
            $splits[] = $atts['id'];
            $change=true;
        }
        if ($change) set_transient( 'flowsplit', $splits );

        $result = '<div data-flowsplit_id="'. $atts['id'] .'" data-flowsplit_option="'. $atts['option'] .'" class="flowsplit_content">' . $content . '</div>';

        $result = str_replace('flowsplitreward', 'return flowsplit_reward(\''. $atts['id'] . '\',\''.$atts['option'].'\');', $result);

    	return $result;
    }


}


?>