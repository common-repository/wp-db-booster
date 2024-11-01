<?php
$nonce = $_REQUEST['_wpnonce'];
$arr_settings = array();
if ($_POST['action'] == 'update' && wp_verify_nonce($nonce, 'wpdbboo-settings')) {
    $arr_settings['scheduler'] = $_POST['wpdbboo_chk_scheduler'];
    $arr_settings['scheduler_time'] = $_POST['wpdbboo_select_scheduler'];
    $arr_settings['scheduler_types'] = $_POST['wpdbboo_scheduler_types'];
    $arr_settings['email_address'] = $_POST['wpdbboo_email_address'];
    if(isset($_POST['wpdbboo_chk_email_notify']))
            $arr_settings['email_notify'] = $_POST['wpdbboo_chk_email_notify'];
       else $arr_settings['email_notify'] = 0;
    if(trim($arr_settings['email_address'] == '')) $arr_settings['email_address'] = get_option('admin_email');
    update_option('wpdbboo-settings', serialize($arr_settings));
}
$arr_settings = unserialize(get_option('wpdbboo-settings'));
if(trim($arr_settings['email_notify']) == '') { $arr_settings['email_notify'] = 1; }
if(trim($arr_settings['email_address'] == '')) $arr_settings['email_address'] = get_option('admin_email');

?>
<form method="post">
    <input type="hidden" name="option_page" value="wp-db-booster-settings">
    <input type="hidden" name="action" value="update">
    <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('wpdbboo-settings'); ?>">
    <input type="hidden" name="_wp_http_referer" value="<?php the_permalink() ?>">

    <div class="postbox" style="float:left; width: 49%; height: 100%; display: block">
        <h3 class="hndle wi-title"><?php _e('Automatic Clean-Up and Optimization', 'wp-db-booster'); ?></h3>

        <div class="inside">
            <div style="border:1px solid #e0dadf; margin-bottom:20px; padding: 8px 10px; border-left:5px solid #f0601e">
                <i class="fa fa-info-circle fa-2x" style="color:#f0601e;display: block; float: left; margin-right:10px; margin-top:5px; min-width:16px; width: 6%"></i>
                <div style="float:right; display: block; width: 90%"><?php _e('You can enable scheduled clean-up and optimization of your Database here (daily/weekly/monthly).','wp-db-booster')?></div>
                <div class="clear"></div>
            </div>
            <!-- SCHEDULER TABLE -->
            <table class="form-table">
                <tr>
                    <td><?php _e('Enable Scheduled Clean-Up and Optimization', 'wp-db-booster'); ?></td>
                    <td><input type="checkbox" id="wpdbboo_chk_scheduler"
                               name="wpdbboo_chk_scheduler" <?php echo ($arr_settings['scheduler']) ? "checked=\"checked\"" : "" ?>
                               style="margin:0px"
                               value="1"></td>
                </tr>
                <tr>
                    <td><?php _e('Frequency', 'wp-db-booster'); ?></td>
                    <td>
                        <div style="margin: 5px 0">
                            <select id="wpdbboo_select_scheduler" name="wpdbboo_select_scheduler"
                                    style="vertical-align:top">
                                <option
                                    value="1" <?php echo ($arr_settings['scheduler_time'] == 1) ? "selected" : "" ?>><?php _e('Everyday', 'wp-db-booster') ?></option>
                                <option
                                    value="2" <?php echo ($arr_settings['scheduler_time'] == 2) ? "selected" : "" ?>><?php _e('Every Week', 'wp-db-booster') ?></option>
                                <option
                                    value="3" <?php echo ($arr_settings['scheduler_time'] == 3) ? "selected" : "" ?>><?php _e('Every Month', 'wp-db-booster') ?></option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Enable Email Notification', 'wp-db-booster'); ?></td>
                    <td><input type="checkbox" id="wpdbboo_chk_email_notify"
                               name="wpdbboo_chk_email_notify" <?php echo (($arr_settings['email_notify'] || $arr_settings['email_notify'] == '') && $arr_settings['email_notify'] != 0) ? "checked=\"checked\"" : "" ?>
                               style="margin:0px"
                               value="1"></td>
                </tr>
                <tr>
                    <td><?php _e('Email Address', 'wp-db-booster'); ?></td>
                    <td><input name="wpdbboo_email_address" value="<?php echo $arr_settings['email_address'] ?>"></td>
                </tr>
            </table>

            <table class="widefat" style="background-color:#fff !important">
                <thead>
                <tr>
                    <th></th>
                    <th scope="col"><?php _e('Type of Data', 'wp-db-booster') ?></th>
                </tr>
                </thead>
                <tbody id="the-list" class="all_wp_chks">
                <?php
                foreach ($this->types as $key => $arr) {
                   $num_issues = 0;
                   $cnt = 1;
                   if($arr['type'] != 1) continue;
                ?>
                <td><input type="checkbox" id="wpdbboo_chk_<?php echo $arr['db'] ?>" name="wpdbboo_scheduler_types[]" style="margin:0px" value="<?php echo $arr['db'] ?>" <?php echo (@in_array($arr['db'],$arr_settings['scheduler_types']))?"checked=\"checked\"":"" ?>></td>
                <td><span class="hovertip underline" data-original-title = "<?php _e($arr['hint'],'wp-db-booster'); ?>"> <?php _e($key,'wp-db-booster') ?> </span></td>
                </tr>
                <?php
                  $cnt++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear"></div>
    <p class="submit"><input type="submit" class="button-primary" value="Uložiť zmeny"></p>
</form>
