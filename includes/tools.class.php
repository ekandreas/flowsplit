<?php

$flowsplit_tools = new FlowSplit_Tools();

class FlowSplit_Tools{

    function public_display(){

        $this->admin_head(true);
        $this->display_charts(false);

    }

    function __construct() {

        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts') );
        add_action('admin_head', array(&$this, 'admin_head') );

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

    function admin_head($public=false){

        if ($public){
            echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
        }

        echo "<script type=\"text/javascript\">
            // Load the Visualization API and the piechart package.
            google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});";

        $splits = get_transient('flowsplit');
        foreach($splits as $key => $split){

            $storage = get_transient( 'flowsplit_' . $split );

            echo "google.setOnLoadCallback(flowsplit_".$split."_presentations_chart);

                function flowsplit_".$split."_presentations_chart(){
                    var data = google.visualization.arrayToDataTable([
                      ['Options', 'Presentations'],";

            foreach($storage as $s){

                echo "['" . $s['value'] . "', " . $s['trials'] . "],";

            }

            echo "]);

                    var options = {
                      title: 'Presentations',
                        backgroundColor: '#F6F6F6'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('flowsplit_" . $split . "_chart_presentations'));
                    chart.draw(data, options);
                }
            ";

            echo "google.setOnLoadCallback(flowsplit_".$split."_rewards_chart);

                function flowsplit_".$split."_rewards_chart(){
                    var data = google.visualization.arrayToDataTable([
                      ['Options', 'Rewards'],";

            foreach($storage as $s){

                echo "['" . $s['value'] . "', " . $s['rewards'] . "],";

            }

            echo "]);

                    var options = {
                      title: 'Rewards',
                        backgroundColor: '#F6F6F6'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('flowsplit_" . $split . "_chart_rewards'));
                    chart.draw(data, options);
                }
            ";


        }

        echo "</script>";

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

                    <?php

                    if (isset($_GET['delete'])){

                        $id = esc_attr($_GET['delete']);
                        $option = esc_attr($_GET['option']);

                        if ($option && $id){

                            $storage = get_transient( 'flowsplit_' . $id );
                            unset($storage[$option]);
                            set_transient('flowsplit_'.$id,$storage);

                        }
                        else if ($id){

                            delete_transient( 'flowsplit_' . $id );
                            $splits = get_transient( 'flowsplit' );
                            foreach($splits as $key=>$split){
                                if ($split == $id) unset($splits[$key]);
                            }
                            set_transient('flowsplit',$splits);

                        }

                    }

                    ?>

                    &nbsp;

                    <?php

                    $this->display_charts(true);

                    ?>




                </div>

                <?php

    }

    function display_charts($admin=false){

        $splits = get_transient('flowsplit');
        error_log(print_r($splits,true));
        foreach($splits as $key => $split){
            ?>
            <div class="postbox">
            <h3 style="padding:10px;"><?php echo $split; ?></h3>
            <div class="inside">
                <div id="flowsplit_<?php echo $split; ?>_chart_presentations" style="width: 400px; height: 300px; float:left;"></div>
                <div id="flowsplit_<?php echo $split; ?>_chart_rewards" style="width: 400px; height: 300px; float:left;"></div>

                <?php
                if ($admin){
                    ?>

                    <div style="clear:both;"></div>

                    Clear all stats for <a href="?page=flowsplit_tools&delete=<?php echo $split; ?>" onclick="return confirm('Please, confirm delete stats for <?php echo $split; ?>');">[<?php echo $split; ?>]</a><br/>
                    Clear stats for tag:
                    <?php
                    $storage = get_transient( 'flowsplit_' . $split );
                    foreach($storage as $s){
                        ?>
                        <a href="?page=flowsplit_tools&delete=<?php echo $split; ?>&option=<?php echo $s['value']; ?>" onclick="return confirm('Please, confirm delete stats for <?php echo $s['value']; ?>');">[<?php echo $s['value'] ?>] </a>
                        <?php
                    }
                }
                ?>

            </div>
            </div>
            <?php
        }


    }

}

?>