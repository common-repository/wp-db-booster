<?php
$dbtables = $wpdb->get_results("SHOW TABLE STATUS");
$num_tables = count($dbtables);
$sum_size = 0;
$sum_rows = 0;
foreach ($dbtables as $table) {
    $numrows = $wpdb->get_var('SELECT COUNT(*) FROM ' . $table->Name);
    $sum_size += $table->Data_length;
    $sum_rows += $numrows;
}
?>
<!-- MYSQL Information -->
<div class="postbox" style="float:left; width: 49%; height: 100%; display: block">
    <h3 class="hndle wi-title"><?php _e('MySQL Information', 'wp-db-booster') ?></h3>

    <div class="inside">
        <table class="widefat" style="border:0px;">
            <tbody>
            <tr>
                <td><?php _e("Database Host", 'wp-db-booster'); ?></td>
                <td><?php echo DB_HOST; ?></td>
            </tr>
            <tr>
                <td><?php _e("Database Name", 'wp-db-booster'); ?></td>
                <td><?php echo DB_NAME; ?></td>
            </tr>
            <tr>
                <td><?php _e("Database User", 'wp-db-booster'); ?></td>
                <td><?php echo DB_USER; ?></td>
            </tr>
            <tr>
                <td><?php _e("Database Size", 'wp-db-booster'); ?></td>
                <td><?php echo $this->helper->filesize_formatted($sum_size); ?></td>
            </tr>
            <tr>
                <td><?php _e("Number of DB Tables", 'wp-db-booster'); ?></td>
                <td><?php echo $num_tables; ?></td>
            </tr>
            <tr>
                <td><?php _e("Summary of Rows in all Tables", 'wp-db-booster'); ?></td>
                <td><?php echo $sum_rows; ?></td>
            </tr>
            <tr>
                <td><?php _e("Database Type", 'wp-db-booster'); ?></td>
                <td>MySQL</td>
            </tr>
            <tr>
                <td><?php _e("MySQL Version", 'wp-db-booster'); ?></td>
                <td><?php echo $wpdb->db_version(); ?> - <?php echo PHP_OS ?>&nbsp;(<?php echo(PHP_INT_SIZE * 8) ?>
                    &nbsp;Bit)
                </td>
            </tr>
            <tr>
                <td><?php _e("SQL Mode", 'wp-db-booster'); ?></td>
                <td>
                    <?php
                    $my_sql_info = $wpdb->get_results("SHOW VARIABLES LIKE \"sql_mode\"");
                    if (is_array($my_sql_info)) $sqlmode = $my_sql_info[0]->Value;
                    if (empty($sqlmode)) $sqlmode = __("Not set", 'wp-db-booster');
                    echo $sqlmode ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Wordpress Information -->
<div class="postbox" style="float:right; width: 49%; height: 100%; display: block">
    <h3 class="hndle wi-title"><?php _e('Wordpress Information', 'wp-db-booster') ?></h3>

    <div class="inside">
        <table class="widefat" style="border:0px;">
            <tbody>
            <tr>
                <td><?php _e("Home URL", 'wp-db-booster'); ?></td>
                <td><?php echo home_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e("Site URL", 'wp-db-booster'); ?></td>
                <td><?php echo site_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e("Web Server", 'wp-db-booster'); ?></td>
                <td><?php echo esc_html($_SERVER["SERVER_SOFTWARE"]); ?></td>
            </tr>
            <tr>
                <td><?php _e("WP Version", 'wp-db-booster'); ?></td>
                <td><?php echo bloginfo("version"); ?></td>
            </tr>
            <?php $theme = wp_get_theme($stylesheet, $theme_root); ?>
            <tr>
                <td><?php _e("Installed Theme", 'wp-db-booster'); ?></td>
                <td><?php echo $theme->Name; ?></td>
            </tr>
            <tr>
                <td><?php _e("Theme Version", 'wp-db-booster'); ?></td>
                <td><?php echo $theme->Version; ?></td>
            </tr>
            <tr>
                <td><?php _e("Theme Author", 'wp-db-booster'); ?></td>
                <td><?php echo $theme->Author; ?></td>
            </tr>
            <?php $count_posts = wp_count_posts(); ?>
            <tr>
                <td><?php _e("Published Posts", 'wp-db-booster'); ?></td>
                <td><?php echo $count_posts->publish; ?></td>
            </tr>
            <tr>
                <td><?php _e("Draft Posts", 'wp-db-booster'); ?></td>
                <td><?php echo $count_posts->draft; ?></td>
            </tr>
            <tr>
                <td><?php _e("Published Pages", 'wp-db-booster'); ?></td>
                <td><?php echo wp_count_posts('page')->publish; ?></td>
            </tr>
            <tr>
                <td><?php _e("Draft Pages", 'wp-db-booster'); ?></td>
                <td><?php echo wp_count_posts('page')->draft; ?></td>
            </tr>
            <?php $comments_count = wp_count_comments(); ?>
            <tr>
                <td><?php _e("Approved Comments", 'wp-db-booster'); ?></td>
                <td><?php echo $comments_count->approved; ?></td>
            </tr>
            <tr>
                <td><?php _e("Comments in Moderation", 'wp-db-booster'); ?></td>
                <td><?php echo $comments_count->moderated; ?></td>
            </tr>
            <tr>
                <td><?php _e("Comments in Spam", 'wp-db-booster'); ?></td>
                <td><?php echo $comments_count->spam; ?></td>
            </tr>
            <tr>
                <td><?php _e("Comments in Trash", 'wp-db-booster'); ?></td>
                <td><?php echo $comments_count->trash; ?></td>
            </tr>
            <tr>
                <td><?php _e("Images in Gallery", 'wp-db-booster'); ?></td>
                <td><?php echo $wpdb->get_var("SELECT COUNT(ID) FROM {$wpdb->prefix}posts WHERE post_type = 'attachment'"); ?></td>
            </tr>

            <tr>
                <td><?php _e("WP Multisite Enabled", 'wp-db-booster'); ?></td>
                <td><?php echo (is_multisite()) ? __("Yes", 'wp-db-booster') : __("No", 'wp-db-booster'); ?></td>
            </tr>
            <tr>
                <td><?php _e("WP Language", 'wp-db-booster'); ?></td>
                <td><?php echo (defined("WPLANG") && WPLANG) ? WPLANG : __("Default", 'wp-db-booster'); ?></td>
            </tr>
            <tr>
                <td><?php _e("WP Debug Mode", 'wp-db-booster'); ?></td>
                <td><?php echo (defined("WP_DEBUG") && WP_DEBUG) ? __("Enabled", 'wp-db-booster') : __("Disabled", 'wp-db-booster'); ?></td>
            </tr>
            <tr>
                <td><?php _e("WP Max Upload Size", 'wp-db-booster'); ?></td>
                <td><?php echo $this->helper->filesize_formatted(wp_max_upload_size()); ?></td>
            </tr>
            <tr>
                <?php
                $request["cmd"] = "_notify-validate";
                $params = array(
                    "sslverify" => false,
                    "timeout" => 60,
                    "user-agent" => "wp-Instagram-Bank",
                    "body" => $request
                );
                $response = wp_remote_post("https://www.paypal.com/cgi-bin/webscr", $params);
                ?>
                <td><?php _e("WP Remote Post", 'wp-db-booster'); ?></td>
                <td><?php echo (!is_wp_error($response)) ? __("Success", 'wp-db-booster') : __("Failed", 'wp-db-booster'); ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- PHP Information -->
<div class="postbox" style="float:left; width: 49%; height: 100%; display: block">
    <h3 class="hndle wi-title"><?php _e('PHP Information', 'wp-db-booster') ?></h3>

    <div class="inside">
        <table class="widefat" style="border:0px;">
            <tbody>
            <tr>
                <td><?php _e('PHP Version', 'wp-db-booster'); ?></td>
                <td><?php if (function_exists("phpversion")) echo esc_html(phpversion()); ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Max Execution Time', 'wp-db-booster'); ?></td>
                <td><?php echo ini_get("max_execution_time"); ?>s</td>
            </tr>
            <tr>
                <td><?php _e('PHP Max Input Vars', 'wp-db-booster'); ?></td>
                <td><?php echo ini_get("max_input_vars"); ?></td>
            </tr>
            <tr>
                <td><?php _e('SUHOSIN Installed', 'wp-db-booster'); ?></td>
                <td><?php echo extension_loaded("suhosin") ? __("Yes", 'wp-db-booster') : __("No", 'wp-db-booster') ?></td>
            </tr>
            <tr>
                <td><?php _e('Default Time Zone', 'wp-db-booster'); ?></td>
                <td>
                    <?php
                    $timezone = date_default_timezone_get();
                    if ("UTC" !== $timezone) {
                        echo sprintf("%s - it should be UTC", $timezone);
                    } else {
                        echo sprintf("%s", $timezone);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php _e('Memory Usage', 'wp-db-booster'); ?></td>
                <td><?php echo (function_exists("memory_get_usage")) ? round(memory_get_usage() / 1024 / 1024, 2) . " MB" : "N/A"; ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Safe Mode', 'wp-db-booster'); ?></td>
                <td><?php echo PHP_VERSION ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Allow URL Open', 'wp-db-booster'); ?></td>
                <td><?php echo (ini_get("allow-url-fopen")) ? __("On", 'wp-db-booster') : __("Off", 'wp-db-booster') ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Memory Limit', 'wp-db-booster'); ?></td>
                <td><?php echo (ini_get("memory_limit")) ? ini_get("memory_limit") : "N/A"; ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Max Post Size', 'wp-db-booster'); ?></td>
                <td><?php echo (ini_get("post_max_size")) ? ini_get("post_max_size") : "N/A" ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Max Upload Size', 'wp-db-booster'); ?></td>
                <td><?php echo (ini_get("upload_max_filesize")) ? ini_get("upload_max_filesize") : "N/A" ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Output Buffer Size', 'wp-db-booster'); ?></td>
                <td><?php echo (ini_get("pcre.backtrack_limit")) ? ini_get("pcre.backtrack_limit") : "N/A" ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Exif Support', 'wp-db-booster'); ?></td>
                <td><?php echo (is_callable("exif_read_data")) ? __("Yes", 'wp-db-booster') . " ( V" . substr(phpversion("exif"), 0, 4) . ")" : __("No", 'wp-db-booster') ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP IPTC Support', 'wp-db-booster'); ?></td>
                <td><?php echo (is_callable("iptcparse")) ? __("Yes", 'wp-db-booster') : __("No", 'wp-db-booster') ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP XML Support', 'wp-db-booster'); ?></td>
                <td><?php echo (is_callable("xml_parser_create")) ? __("Yes", 'wp-db-booster') : __("No", 'wp-db-booster') ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="clear"></div>