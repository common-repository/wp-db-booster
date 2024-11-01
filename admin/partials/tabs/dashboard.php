<?php
$sum_issues = 0;
$sum_issues2 = 0;
$cnt = 0;
foreach ($this->types as $key => $arr) {
    $num_issues = $this->helper->get_cleanup_info($arr['db']);
    if ($arr['type'] == 1) $sum_issues += $num_issues;
    if ($arr['type'] == 2) $sum_issues2 += $num_issues;
    $this->types[$key]['issues'] = $num_issues;
    $cnt++;
}

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
<div style="width:32%; display: inline-block; margin-right:10px;">
    <div class="issues-stat dashboard-stat <?php echo ($sum_issues) ? "red" : "green" ?>">
        <div class="visual">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="details">
            <div class="number">
                <span id="sum_issues"><?php echo $sum_issues ?></span>
            </div>
            <div class="desc"><?php _e('Issues Found', 'wp-db-booster') ?></div>
        </div>
        <a class="more" href="javascript:;" style="cursor: default"> <?php _e('Minnor issues: ', 'wp-db-booster') ?>
            <span id="sum_minnor" style="font-weight: bold"><?php echo $sum_issues2 ?></span>
            <i class="m-icon-swapright m-icon-white"></i>
        </a>
    </div>
</div>
<div style="width:32%; display: inline-block; margin-right: 10px">
    <div class="dashboard-stat blue">
        <div class="visual">
            <i class="fa fa-database"></i>
        </div>
        <div class="details">
            <div class="number">
                <?php
                $tables = $wpdb->get_results("SHOW TABLE STATUS");
                $num_tables = count($tables);
                unset($tables);
                ?>
                <span><?php echo $num_tables; ?></span>
            </div>
            <div class="desc"><?php _e('Tables in Database', 'wp-db-booster') ?></div>
        </div>
        <a class="more"
           href="<?php admin_url() ?>?page=wp-db-booster-dbtables"> <?php _e('View more', 'wp-db-booster') ?>
            <i class="m-icon-swapright m-icon-white"></i>
        </a>
    </div>
</div>

<div style="width:32%; display: inline-block">
    <div class="dashboard-stat purple">
        <div class="visual">
            <i class="fa fa-archive"></i>
        </div>
        <div class="details">
            <div class="number">
                <span><?php echo $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "wpdbboost_log"); ?></span>
            </div>
            <div class="desc"><?php _e('Items in Log', 'wp-db-booster') ?></div>
        </div>
        <a class="more" href="<?php admin_url() ?>?page=wp-db-booster-log"> <?php _e('View more', 'wp-db-booster') ?>
            <i class="m-icon-swapright m-icon-white"></i>
        </a>
    </div>
</div>
<hr>

<!-- MAIN TABLE -->
<div style="width:50%; display: inline-block">
    <div style="margin: 5px 0">
        <select id="wpdbboo_action1" name="wpdbboo_action1" style="vertical-align:top">
            <option value="0"><?php _e('Bulk Action', 'wp-db-booster') ?></option>
            <option value="1"><?php _e('Empty', 'wp-db-booster') ?></option>
        </select>
        <input type="button" id="wpdbboo_btn_action1" name="wpdbboo_btn_action1"
               class="button-primary" value="<?php _e('Apply', 'wp-db-booster'); ?>">
    </div>
    <table class="widefat" style="background-color:#fff !important">
        <thead>
        <tr>
            <th style="width:10%;"><input type="checkbox" id="wpdbboo_select_all1" name="wpdbboo_select_all1"
                                          style="margin:0px">
            </th>
            <th scope="col"><?php _e('Type of Data', 'wp-db-booster') ?></th>
            <th scope="col"><?php _e('Count', 'wp-db-booster') ?></th>
            <th scope="col"><?php _e('Action', 'wp-db-booster') ?></th>
        </tr>
        </thead>
        <tbody id="the-list" class="all_wp_chks">
        <?php
        foreach ($this->types as $key => $arr) {
           $num_issues = 0;
           $cnt = 1;
           if($arr['type'] != 1) continue;
        ?>
        <td><input type="checkbox" id="wpdbboo_chk_<?php echo $arr['db'] ?>" name="wpdbboo_chk1[]" style="margin:0px" value="1"></td>
        <td><span class="hovertip underline" data-original-title = "<?php _e($arr['hint'],'wp-db-booster') ?>"> <?php _e($key,'wp-db-booster'); ?> </span></td>
        <td>
            <span id="issues_<?php echo $arr['db'] ?>"><?php echo $arr['issues']; ?></span>
            <img id="loader_<?php echo $arr['db'] ?>" style="display:none" src="<?php echo plugin_dir_url(dirname(dirname(dirname(__FILE__)))) . 'admin/img/loader.gif' ?>">
        </td>
        <td>
            <?php if($arr['issues'] > 0): ?>
                <input type="button" id="btnclean_<?php echo $arr['db'] ?>" data-type="<?php echo $arr['db'] ?>" class="button-primary wbdbboo-btnclean" value="<?php _e('Empty','wp-db-booster')?>">
            <?php else:  ?>
                <input type="button" id="btnclean_<?php echo $arr['db'] ?>" enabled="false" class="button disabled" value="<?php _e('Empty','wp-db-booster')?>">
            <?php endif ?>
        </td>
        </tr>
        <?php
          $cnt++;
        }
        ?>
        </tbody>
    </table>

    <div style="border:1px solid #e0dadf; margin:40px 0 30px 0; padding: 2px 10px; border-left:5px solid #f0601e"><p><i
                class="fa fa-exclamation-circle fa-2x"
                style="color:#f0601e;display: block; float: left; margin-right:10px; margin-top:-5px"></i> <?php _e('Data in the table below may change frequently. Most of them are temporary data created either by 3rd party plugins or Wordpress itself.', 'wp-db-booster') ?>
        </p></div>
    <hr>
    <!-- SECOND TABLE -->
    <div style="margin: 5px 0">
        <select id="wpdbboo_action2" name="wpdbboo_action2" style="vertical-align:top">
            <option value="0"><?php _e('Bulk Action', 'wp-db-booster') ?></option>
            <option value="1"><?php _e('Empty', 'wp-db-booster') ?></option>
        </select>
        <input type="button" id="wpdbboo_btn_action2" name="wpdbboo_btn_action2"
               class="button-primary" value="<?php _e('Apply', 'wp-db-booster'); ?>">
    </div>
    <table class="widefat" style="background-color:#fff !important">
        <thead>
        <tr>
            <th style="width:10%;"><input type="checkbox" id="wpdbboo_select_all2" name="wpdbboo_select_all2"
                                          style="margin:0px">
            </th>
            <th scope="col"><?php _e('Type of Data', 'wp-db-booster') ?></th>
            <th scope="col"><?php _e('Count', 'wp-db-booster') ?></th>
            <th scope="col"><?php _e('Action', 'wp-db-booster') ?></th>
        </tr>
        </thead>
        <tbody id="the-list" class="all_wp_chks">
        <?php
        foreach ($this->types as $key => $arr) {
           $num_issues = 0;
           $cnt = 1;
           if($arr['type'] != 2) continue;
        ?>
        <td><input type="checkbox" id="wpdbboo_chk_<?php echo $arr['db'] ?>" name="wpdbboo_chk2[]" style="margin:0px" value="1"></td>
        <td><span class="hovertip underline" data-original-title = "<?php _e($arr['hint'],'wp-db-booster') ?>"> <?php _e($key,'wp-db-booster') ?> </span></td>
        <td>
            <span id="issues_<?php echo $arr['db'] ?>"><?php echo $arr['issues']; ?></span>
            <img id="loader_<?php echo $arr['db'] ?>" style="display:none" src="<?php echo plugin_dir_url(dirname(dirname(dirname(__FILE__)))) . 'admin/img/loader.gif' ?>">
        </td>
        <td>
            <?php if($arr['issues'] > 0): ?>
                <input type="button" id="btnclean_<?php echo $arr['db'] ?>" data-type="<?php echo $arr['db'] ?>" class="button-primary wbdbboo-btnclean" value="<?php _e('Empty','wp-db-booster')?>">
            <?php else:  ?>
                <input type="button" id="btnclean_<?php echo $arr['db'] ?>" enabled="false" class="button disabled" value="<?php _e('Empty','wp-db-booster')?>">
            <?php endif ?>
        </td>
        </tr>
        <?php
          $cnt++;
        }
        ?>
        </tbody>
    </table>
</div>

<div style="width:48%; display: inline-block; vertical-align: top; padding:40px 0 0 10px">
    <div class="postbox">
        <h3 class="hndle wi-title"><?php _e('Database Related Security Tests', 'wp-db-booster') ?></h3>

        <div class="inside">
            <table class="widefat" style="border:0px;">
                <tbody>
                <tr>
                    <td><span class="hovertip underline"
                                               data-original-title="<?php _e("Knowing the names of your database tables can help an attacker dump the table's data and get to sensitive information like password hashes. Since WP table names are predefined the only way you can change table names is by using a unique prefix. One that's different from 'wp_' or any similar variation such as 'wordpress_'.", 'wp-db-booster'); ?>"><?php _e("Default Table Prefix", 'wp-db-booster'); ?></td>
                    <td><?php echo $this->helper->status($this->helper->database_prefix()); ?></td>
                </tr>
                <tr>
                    <td><span class="hovertip underline"
                           data-original-title="<?php _e("Having any kind of debug mode (general WP debug mode in this case) or error reporting mode enabled on a production server is extremely bad. Not only will it slow down your site, confuse your visitors with weird messages it will also give the potential attacker valuable information about your system.", 'wp-db-booster'); ?>"><?php _e("Debug Mode", 'wp-db-booster'); ?></td>
                    <td><?php echo $this->helper->status($this->helper->debug_mode()); ?></td>
                </tr>
                <tr>

                    <td><span class="hovertip underline"
                           data-original-title="<?php _e("Having any kind of debug mode (WP DB debug mode in this case) or error reporting mode enabled on a production server is extremely bad. Not only will it slow down your site, confuse your visitors with weird messages it will also give the potential attacker valuable information about your system.", 'wp-db-booster'); ?>"><?php _e("Database Debug Mode", 'wp-db-booster'); ?></td>
                    <td><?php echo $this->helper->status($this->helper->database_debug_mode()); ?></td>
                </tr>
                <tr>
                    <td>
                    <span class="hovertip underline"
                          data-original-title="<?php _e("We have tested your DB password against more than 1450 most commonly used passwords on the internet", 'wp-db-booster'); ?>"><?php _e("Common Passwords Test", 'wp-db-booster'); ?></span>
                    </td>
                    <td><?php echo $this->helper->status($this->helper->common_pass_test()); ?>
                    </td>
                </tr>
                <tr>
                    <?php
                    $pass_strength = $this->helper->check_password(DB_PASSWORD);
                    $pass_text = $this->helper->check_password_text($pass_strength);
                    $hint = sprintf(__("Your calculated password strength is %s which is %s", 'wp-db-booster'), $pass_strength, $pass_text);
                    ?>
                    <td><span class="hovertip underline"
                              data-original-title="<?php echo $hint ?>"><?php _e("Password Strength", 'wp-db-booster'); ?></span>
                    </td>
                    <td>
                        <div class="progress progress-small">
                            <div class="bar"
                                 style="width:<?php echo $pass_strength ?>%;background-position:0 <?php echo $pass_strength ?>%"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <?php /*
    <div class="postbox">
        <h3 class="hndle wi-title"><?php _e('Database Information', 'wp-db-booster') ?></h3>

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
                    <?php
                    $pass_strength = $this->helper->check_password(DB_PASSWORD);
                    $pass_text = $this->helper->check_password_text($pass_strength);
                    $hint = sprintf(__("Your calculated password strength is %s which is %s", 'wp-db-booster'), $pass_strength, $pass_text);
                    ?>
                    <td><span class="hovertip underline"
                              data-original-title="<?php echo $hint ?>"><?php _e("Password Strength", 'wp-db-booster'); ?></span>
                    </td>
                    <td>
                        <div class="progress progress-small">
                            <div class="bar"
                                 style="width:<?php echo $pass_strength ?>%;background-position:0 <?php echo $pass_strength ?>%"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="hovertip underline"
                              data-original-title="<?php _e("We have tested your DB password against more than 1450 most commonly used passwords on the internet", 'wp-db-booster'); ?>"><?php _e("Common Passwords Test", 'wp-db-booster'); ?></span>
                    </td>
                    <td>
                        <?php
                        echo $this->helper->common_pass_test();
                        ?>
                    </td>
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
    </div> */ ?>
</div>


<?php

$plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/' . 'wp-snow-effect/wp-snow-effect.php');
echo "<pre>";
//print_r($plugin_data);
echo "</pre>";

global $wpdb;
$mytables = $wpdb->get_results("SHOW TABLES");
foreach ($mytables as $mytable) {
    foreach ($mytable as $t) {
//        echo $t . "<br>";
    }
}

$tables = $this->helper->get_tables();
$datetime = date('Y-m-d H:i:s');
$plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/' . $plugin);
$old_tables = get_option('wpdbbooster_tables');

?>
<script type="text/javascript">


</script>