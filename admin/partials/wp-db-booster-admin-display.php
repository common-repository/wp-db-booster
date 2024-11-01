<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.wpmaniax.com
 * @since      1.0.0
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/admin/partials
 */
?>

<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.wpoptinbar.com
 * @since      1.0.0
 *
 * @package    Wp_Optin_Bar
 * @subpackage Wp_Optin_Bar/admin/partials
 */
global $wpdb;

$arr_tabs = array(
    'dashboard' => __('Dashboard','wp-db-booster'),
    'dbtables' => __('DB Tables','wp-db-booster'),
    'log' => __('Log','wp-db-booster'),
    'statistics' => __('Statistics','wp-db-booster'),
    'systemstatus' => __('System Status','wp-db-booster'),
    //'settings' => __('Settings','wp-db-booster'),
    //'premium' => __('Premium Features','wp-db-booster'),
);
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
foreach ($arr_tabs as $tab => $title) {
    $active_tab = ($_GET['page'] == 'wp-db-booster-'.$tab) ? $tab : 'dashboard';
    if($active_tab != 'dashboard') break;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <!--    <h2 id="wpobl-title">WP DB Booster</h2>-->

    <div id="welcome-panel" class="welcome-panel"
         style="width:100%;padding:0px !important;background-color: #f9f9f9 !important">
        <div class="welcome-panel-content">
            <img src="<?php echo bloginfo('url') ?>/wp-content/plugins/wp-db-booster/admin/img/wpdbbooster.png"
                 style="margin-top:10px;">

           <?php /*<div class="welcome-panel-column-container">
                <div class="welcome-panel-column" style="width:240px !important;">
                    <h4 class="welcome-screen-margin">
                        Get Started </h4>
                    <a class="button button-primary button-hero" href="#">
                        Watch Clean Up Video! </a>

                    <p>or,
                        <a target="_blank" href="http://tech-banker.com/products/wp-clean-up-optimizer/knowledge-base/">
                            read documentation here </a>
                    </p>
                </div>
                <div class="welcome-panel-column" style="width:250px !important;">
                    <h4 class="welcome-screen-margin">Go Premium</h4>
                    <ul>
                        <li>
                            <a href="http://tech-banker.com/products/wp-clean-up-optimizer/" target="_blank"
                               class="welcome-icon">
                                Features </a>
                        </li>
                        <li>
                            <a href="http://tech-banker.com/products/wp-clean-up-optimizer/demo/" target="_blank"
                               class="welcome-icon">
                                Online Demos </a>
                        </li>
                        <li>
                            <a href="http://tech-banker.com/products/wp-clean-up-optimizer/pricing/" target="_blank"
                               class="welcome-icon">
                                Premium Pricing Plan </a>
                        </li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:240px !important;">
                    <h4 class="welcome-screen-margin">
                        Knowledge Base </h4>
                    <ul>
                        <li>
                            <a href="http://tech-banker.com/forums/forum/wp-clean-up-optimizer-support/" target="_blank"
                               class="welcome-icon">
                                Support Forum </a>
                        </li>
                        <li>
                            <a href="http://tech-banker.com/products/wp-clean-up-optimizer/knowledge-base/"
                               target="_blank" class="welcome-icon">
                                FAQ's </a>
                        </li>
                        <li>
                            <a href="http://tech-banker.com/products/wp-clean-up-optimizer/" target="_blank"
                               class="welcome-icon">
                                Detailed Features </a>
                        </li>
                    </ul>
                </div>
                <div class="welcome-panel-column welcome-panel-last" style="width:250px !important;">
                    <h4 class="welcome-screen-margin">More Actions</h4>
                    <ul>
                        <li>
                            <a href="http://tech-banker.com/shop/plugin-customization/order-customization-wp-clean-optimizer/"
                               target="_blank" class="welcome-icon">
                                Plugin Customization </a>
                        </li>
                        <li>
                            <a href="admin.php?page=cpo_recommendations" class="welcome-icon">
                                Recommendations </a>
                        </li>
                        <li>
                            <a href="admin.php?page=cpo_other_services" class="welcome-icon">
                                Our Other Services </a>
                        </li>
                    </ul>
                </div>
            </div> */ ?>
        </div>
    </div>

    <h2 class="nav-tab-wrapper">
        <?php
        foreach ($arr_tabs as $tab => $title):
            ?>
            <a href="?page=wp-db-booster-<?php echo $tab ?>&amp;tab=<?php echo $tab ?>"
               class="nav-tab <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>"><?php echo $title ?></a>
            <?php
        endforeach
        ?>
    </h2>
    <div id="ohsnap"></div>
    <div id="poststuff" class="">
        <div class="postbox">
            <div class="inside" style="padding-top: 7px">
                <?php include(plugin_dir_path(__FILE__) . 'tabs/' . $active_tab . '.php'); ?>
            </div>
        </div>
    </div>
</div>
<?php
/*echo plugin_dir_path(dirname(__FILE__));
if (!class_exists('ReduxFramework') && file_exists(plugin_dir_path(dirname(__FILE__)) . '/ReduxFramework/ReduxCore/framework.php')) {
    require_once(plugin_dir_path(dirname(__FILE__)) . '/ReduxFramework/ReduxCore/framework.php');
}
if (!isset($redux_demo) && file_exists(plugin_dir_path(dirname(__FILE__)) . '/ReduxFramework/sample/sample-config.php')) {
    require_once(plugin_dir_path(dirname(__FILE__)) . '/ReduxFramework/sample/barebones-config.php');
}*/

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
