<?php

$flowsplit_tools = new FlowSplit_Tools();

class FlowSplit_Tools{


    function __construct() {

        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts') );

    }

    function admin_enqueue_scripts($hook){

        if( 'tools_page_flowsplit_tools' != $hook )
            return;
        wp_enqueue_script( 'jsapi', 'https://www.google.com/jsapi' );
        wp_enqueue_script( 'flowsplit_admin_tools', WP_PLUGIN_URL . '/flowsplit/js/admin_tools.js' );

    }

    function admin_menu(){

        add_management_page( 'flowsplit', 'FlowSplit', 'read', 'flowsplit_tools', array(&$this, 'display')  );

    }

    function display(){

        global $current_user;
        get_currentuserinfo();

        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/flowsplit/flowsplit.php');
        $version = $plugin_data['Version'];

        ?>
                <div class="wrap">
                    <div id="icon-tools" class="icon32"><br></div>
                    <h2><?php _e('FlowSplit Tools', 'flowsplit'); ?>, version <?php echo $version; ?></h2>

                    &nbsp;

                    <script language="text/javascript">
                    </script>

                    <?php
                    $splits = get_transient('flowsplit');
                    foreach($splits as $key => $split){
                        ?>
                        <div class="postbox">
                        <h3><?php echo $split; ?></h3>
                        <div class="inside">
                            <div id="chart_div" style="width: 400px; height: 300px;"></div>
                        </div>
                        </div>
                        <?php
                    }
                    ?>




                </div>

                <?php

    }

}

?>