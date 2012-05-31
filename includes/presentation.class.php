<?php

$flowsplit_presentation = new FlowSplit_Presentation();

class FlowSplit_Presentation{

    function __construct(){

        add_action( 'wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts') );
        add_action('wp_ajax_flowsplit_show_content', array(&$this, 'show_content'));
        add_action('wp_ajax_nopriv_flowsplit_show_content', array(&$this, 'show_content'));
        add_action('wp_ajax_flowsplit_reward', array(&$this, 'reward'));
        add_action('wp_ajax_nopriv_flowsplit_reward', array(&$this, 'reward'));

    }

    function wp_enqueue_scripts(){

        wp_register_style( 'flowsplit_css', WP_PLUGIN_URL . '/flowsplit/css/flowsplit.css' );
        wp_enqueue_style( 'flowsplit_css' );

        wp_enqueue_script(
       		'flowsplit_presentation',
            WP_PLUGIN_URL . '/flowsplit/js/presentation.js',
       		array('jquery')
       	);

        wp_localize_script( 'flowsplit_presentation', 'flowsplit_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    }

    function show_content(){

        $options = $_POST['options'];
        $id = esc_attr($_POST['id']);

        $selected = $this->choose($id, $options);

        echo "jQuery('.flowsplit_content[data-flowsplit_id=" . $id . "][data-flowsplit_option=" .$selected. "]').show()";
        die();

    }

    function choose($id, $options){

        $storage = get_transient( 'flowsplit_' . $id );
        //$storage=array();

        if (!$storage)
            $storage = array();

        $highscore = 0;
        $highscore_index = 0;

        //calculate the expectation of reward.
        foreach($options as $option){

            $storage[$option]['value'] = $option;
            if (((int)$storage[$option]['rewards'])==0) $storage[$option]['rewards'] = 1;
            if (((int)$storage[$option]['trials'])==0) $storage[$option]['trials'] = 1;

            $storage[$option]['excpected_reward'] = (int)$storage[$option]['rewards'] / (int)$storage[$option]['trials'];

            if ($storage[$option]['excpected_reward']>$highscore){
                $highscore = $storage[$option]['excpected_reward'];
                $highscore_index = $option;
            }

        }

        //choose a random lever 10% of the time.
        if (rand(1,10)==1){
            $highscore_index = $options[rand(0,sizeof($options)-1)];
        }

        $storage[$highscore_index]['trials'] = ((int)$storage[$highscore_index]['trials']) + 1;
        set_transient('flowsplit_'.$id,$storage);

        //error_log(print_r($storage,true));

        return $highscore_index;

    }

    function reward(){

        $id = esc_attr($_POST['id']);
        $option = esc_attr($_POST['option']);

        $storage = get_transient( 'flowsplit_' . $id );
        $storage[$option]['rewards']++;
        set_transient('flowsplit_'.$id,$storage);

        //error_log(print_r($storage,true));
    }



}

?>