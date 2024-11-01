<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wpmaniax.com
 * @since      1.0.0
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/admin
 * @author     WPManiax <plugins@wpmaniax.com>
 */
class Wp_Db_Booster_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    private $types;

    public function __construct($plugin_name, $version)
    {
        $arr_data = array(
            __('Auto Draft', 'wp-db-booster') => array(
                'hint' => __('Auto Draft are the Page / Post saved as draft automatically in WordPress Database.', 'wp-db-booster'),
                'db' => 'autodraft',
                'type' => 1
            ),
            __('Posts in Trash', 'wp-db-booster') => array(
                'hint' => __('Posts in Trash are posts that have been deleted and moved to Trash.', 'wp-db-booster'),
                'db' => 'trash_post',
                'type' => 1
            ),
            __('Dashboard Transient Feed', 'wp-db-booster') => array(
                'hint' => __('Transient Feed in WordPress are use Database entries to cache a certain entries.', 'wp-db-booster'),
                'db' => 'transient_feed',
                'type' => 3
            ),
           /* __('Draft', 'wp-db-booster') => array(
                'hint' => __('New Post / Page created as Draft in WordPress.', 'wp-db-booster'),
                'db' => 'draft',
                'type' => 1
            ),*/
            __('Orphan Comment Meta', 'wp-db-booster') => array(
                'hint' => __('Orphan Comments Meta holds the miscellaneous bits of extra information of comment.', 'wp-db-booster'),
                'db' => 'orphan_comment_meta',
                'type' => 1
            ),
            __('Duplicated Comment Meta', 'wp-db-booster') => array(
                'hint' => __('Duplicated Comment Meta holds duplicated comment meta information', 'wp-db-booster'),
                'db' => 'duplicated_comment_meta',
                'type' => 1
            ),
            __('Orphan Post Meta', 'wp-db-booster') => array(
                'hint' => __('Orphan Post Meta holds the junk or obsolete data.', 'wp-db-booster'),
                'db' => 'orphan_post_meta',
                'type' => 1
            ),
            __('Duplicated Post Meta', 'wp-db-booster') => array(
                'hint' => __('Duplicated Post Meta holds duplicated post meta information', 'wp-db-booster'),
                'db' => 'duplicated_post_meta',
                'type' => 1
            ),
            __('Orphan User Meta', 'wp-db-booster') => array(
                'hint' => __('Orphan User Meta holds the junk or obsolete data.', 'wp-db-booster'),
                'db' => 'orphan_user_meta',
                'type' => 1
            ),
            __('Duplicated User Meta', 'wp-db-booster') => array(
                'hint' => __('Duplicated User Meta holds duplicated user meta information', 'wp-db-booster'),
                'db' => 'duplicated_user_meta',
                'type' => 1
            ),
            __('Orphan Term Meta', 'wp-db-booster') => array(
                'hint' => __('Orphan Term Meta holds the junk or obsolete data.', 'wp-db-booster'),
                'db' => 'orphan_term_meta',
                'type' => 1
            ),
            __('Duplicated Term Meta', 'wp-db-booster') => array(
                'hint' => __('Duplicated Term Meta holds duplicated term meta information', 'wp-db-booster'),
                'db' => 'duplicated_term_meta',
                'type' => 1
            ),
            __('Orphan Term Relationships', 'wp-db-booster') => array(
                'hint' => __('Orphan Relationships holds the junk or obsolete Category and Tag.', 'wp-db-booster'),
                'db' => 'orphan_term_relationships',
                'type' => 1
            ),
            __('Unused Tags', 'wp-db-booster') => array(
                'hint' => __('Unused Tags holds the junk or obsolete Category and Tag.', 'wp-db-booster'),
                'db' => 'unused_tags',
                'type' => 1
            ),
            __('Revisions', 'wp-db-booster') => array(
                'hint' => __('The WordPress revisions system stores a record of each saved draft or published update.Revisions are stored in the posts table.', 'wp-db-booster'),
                'db' => 'revisions',
                'type' => 1
            ),
            __('Pingbacks', 'wp-db-booster') => array(
                'hint' => __('A Pingback is a type of comment that\'s created when you link to another blog post where pingbacks are enabled.', 'wp-db-booster'),
                'db' => 'pingbacks',
                'type' => 1
            ),
            __('Transient Options', 'wp-db-booster') => array(
                'hint' => __('Transient Options are like a basic cache system used by wordpress. Clearing these options before a backup will help to save space in your backup files.', 'wp-db-booster'),
                'db' => 'transient_options',
                'type' => 2
            ),
            __('Trackbacks', 'wp-db-booster') => array(
                'hint' => __('Trackbacks are a way to notify legacy blog systems that you have linked to them. If you link to a WordPress blog they will be notified automatically using pingbacks, no other action necessary.', 'wp-db-booster'),
                'db' => 'trackbacks',
                'type' => 1
            ),
            __('Unaproved Comments', 'wp-db-booster') => array(
                'hint' => __('Unaproved Comments are the comments waiting for moderation.', 'wp-db-booster'),
                'db' => 'unaproved_comment',
                'type' => 1
            ),
            __('Spam Comments', 'wp-db-booster') => array(
                'hint' => __('Spam Comments are the unwanted comments in the WordPress database.', 'wp-db-booster'),
                'db' => 'spam_comment',
                'type' => 1
            ),
            __('Trash Comments', 'wp-db-booster') => array(
                'hint' => __('Trash Comments are the comments which are stored in the WordPress Trash.', 'wp-db-booster'),
                'db' => 'trash_comment',
                'type' => 1
            ),
            __('oEmbed Caches In Post Meta', 'wp-db-booster') => array(
                'hint' => __('oEmbed Is used by Wordpress to grab embed code for various embeddable content such as YouTube and Vimeo videos', 'wp-db-booster'),
                'db' => 'oembed_post_meta',
                'type' => 1
            ),

        );

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->types = $arr_data;
        $this->helper = new Wp_Db_Booster_Helper();

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/pagination.class.php';

        $this->pagination = new pagination();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Db_Booster_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Db_Booster_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-db-booster-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '_font_awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '_morris_css', 'http://cdn.oesmith.co.uk/morris-0.4.3.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Db_Booster_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Db_Booster_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-db-booster-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '_ohsnap', plugin_dir_url(__FILE__) . 'js/ohsnap.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '_tooltip', plugin_dir_url(__FILE__) . 'js/jquery.Tooltip.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '_raphael_js', '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '_morris_js', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js', array('jquery'), $this->version, false);

        wp_localize_script($this->plugin_name, 'ajax_call', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'loader_img' => plugin_dir_path(dirname(__FILE__)) . 'admin/img/loader.gif',
            'confirm_text' => __('Are you sure, you want to Clean Up these data?', 'wp-db-booster'),
            'confirm_alert' => __('Please select action first', 'wp-db-booster')
        ));

    }

    function add_action_links($links)
    {
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);
    }

    public function display_admin_page()
    {
        add_menu_page('WP DB Booster', 'WP DB Booster', 'nosuchcapability', 'wp-db-booster-main', null, 'dashicons-admin-tools');
        add_submenu_page('wp-db-booster-main', 'WP DB Booster Dashboard', __('Dashboard', 'wp-db-booster'), 'manage_options', 'wp-db-booster-dashboard', array($this, 'show_dashboard'));
        add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('DB Tables', 'wp-db-booster'), __('DB Tables', 'wp-db-booster'), 'manage_options', 'wp-db-booster-dbtables', array($this, 'show_db_tables'));
        add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('Log', 'wp-db-booster'), __('Log', 'wp-db-booster'), 'manage_options', 'wp-db-booster-log', array($this, 'show_log'));
        add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('Statistics', 'wp-db-booster'), __('Statistics', 'wp-db-booster'), 'manage_options', 'wp-db-booster-statistics', array($this, 'show_statistics'));
        add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('System Status', 'wp-db-booster'), __('System Status', 'wp-db-booster'), 'manage_options', 'wp-db-booster-systemstatus', array($this, 'show_systemstatus'));
        //add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('Settings', 'wp-db-booster'), __('Settings', 'wp-db-booster'), 'manage_options', 'wp-db-booster-settings', array($this, 'show_settings'));
        //add_submenu_page('wp-db-booster-main', 'WP DB Booster ' . __('Premium Features', 'wp-db-booster'), __('Premium Features', 'wp-db-booster'), 'manage_options', 'wp-db-booster-premium', array($this, 'show_premium'));
    }

    public function detect_plugin_activation($plugin, $network_activation)
    {
        global $wpdb;
        $old_tables = array();
        $tables = $this->helper->get_tables();
        $datetime = date('Y-m-d H:i:s');
        $plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/' . $plugin);
        $old_tables = get_option('wpdbbooster_tables');
        $diff = array_diff($tables, $old_tables);
        update_option('wpdbbooster_tables', $tables);
        $wpdb->insert($wpdb->prefix . "wpdbboost_log", array('updated_at' => $datetime, 'plugin_name' => $plugin_data['Name'], 'plugin' => $plugin, 'data' => serialize($diff), 'action' => 'activated'));
    }

    public function detect_plugin_deactivation($plugin, $network_activation)
    {
        global $wpdb;
        $datetime = date('Y-m-d H:i:s');
        $old_tables = get_option('wpdbbooster_tables');
        $tables = $this->helper->get_tables();
        $diff = array_diff($old_tables, $tables);
        update_option('wpdbbooster_tables', $tables);
        $plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/' . $plugin);
        $wpdb->insert($wpdb->prefix . "wpdbboost_log", array('updated_at' => $datetime, 'plugin_name' => $plugin_data['Name'], 'plugin' => $plugin, 'data' => serialize($diff), 'action' => 'deactivated'));
    }

    public function show_dashboard()
    {
        $tables = $this->helper->get_tables();
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_statistics()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_log()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_db_tables()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_systemstatus()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_settings()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function show_premium()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-db-booster-admin-display.php';
    }

    public function cleanup()
    {
        global $wpdb;
        $datetime = date('Y-m-d H:i:s');
        $type = $_POST['type'];
        $to_delete = $this->helper->get_cleanup_info($type);
        $this->helper->do_cleanup($type);
        $wpdb->insert($wpdb->prefix . "wpdbboost_log", array('updated_at' => $datetime, 'data' => $type, 'action' => 'cleanup'));
        $count = $this->helper->get_cleanup_info($type);
        $new = get_option('wpdbboost_cleanedup', 0);
        update_option('wpdbboost_cleanedup_' . $type, $to_delete . ' - ' . $new);
        update_option('wpdbboost_cleanedup', $to_delete + $new);
        echo $count;
        die();
    }

    public function cleanup_all()
    {
        global $wpdb;
        $types = $_POST['type'];
        $datetime = date('Y-m-d H:i:s');
        // count sum of issues
        $to_delete = 0;
        $arr_results = array();
        foreach ($types as $type) {
            $cleaned = 0;
            $info = $this->helper->get_cleanup_info($type);
            $to_delete += $info;
            $this->helper->do_cleanup($type);
            $wpdb->insert($wpdb->prefix . "wpdbboost_log", array('updated_at' => $datetime, 'data' => $type, 'action' => 'cleanup'));
            $arr_results[$type] = $this->helper->get_cleanup_info($type);
            $cleaned += $arr_results[$type];
        }
        update_option('wpdbboost_cleanedup', ($to_delete - $cleaned) + get_option('wpdbboost_cleanedup', 0));
        header('Content-Type: application/json');
        echo json_encode($arr_results);
        die();
    }

    public function info()
    {
        $type = $_POST['type'];
        $count = $this->helper->get_cleanup_info($type);
        echo $count;
        die();
    }

    public function all_info()
    {
        $arr_issues = array();
        $arr_types = array(
            "autodraft", /*"transient_feed",*/
            "trash_post",
//            "draft",
            "orphan_comment_meta",
            "duplicated_comment_meta",
            "orphan_user_meta",
            "duplicated_user_meta",
            "orphan_post_meta",
            "duplicated_post_meta",
            "orphan_term_meta",
            "duplicated_term_meta",
            "orphan_term_relationships",
            "unused_tags",
            "revisions",
            "pingbacks",
            /*"transient_options",*/
            "trackbacks",
            "unaproved_comment",
            "spam_comment",
            "trash_comment");
        $count = 0;
        foreach ($arr_types as $type) {
            $count += $this->helper->get_cleanup_info($type);
        }
        $arr_issues['issues'] = $count;
        $arr_types = array("comment_meta", "post_meta", "relationships", "transient_options");
        $count = 0;
        foreach ($arr_types as $type) {
            $arr_issues[$type] = $this->helper->get_cleanup_info($type);
            $count += $arr_issues[$type];
        }
        $arr_issues['minnor'] = $count;
        header('Content-Type: application/json');
        echo json_encode($arr_issues);
        die();
    }

    public function deletelog()
    {
        global $wpdb;
        $type = $_POST['type'];
        if ($type == 'all') {
            $wpdb->query("DELETE FROM " . $wpdb->prefix . "wpdbboost_log");
        }
        if ($type == 'plugins') {
            $wpdb->query("DELETE FROM " . $wpdb->prefix . "wpdbboost_log WHERE action = 'activated' OR action = 'deactivated'");
        }
        if ($type == 'cleanup') {
            $wpdb->query("DELETE FROM " . $wpdb->prefix . "wpdbboost_log WHERE action = 'cleanup'");
        }
    }

}
