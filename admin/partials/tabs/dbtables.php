<?php
$core_tables = array(
    'commentmeta',
    'comments',
    'links',
    'options',
    'postmeta',
    'posts',
    'term_relationships',
    'term_taxonomy',
    'termmeta',
    'terms',
    'usermeta',
    'users'
);
$activated = $wpdb->get_results("SELECT plugin_name,plugin,data FROM " . $wpdb->prefix . "wpdbboost_log WHERE action='activated' GROUP BY plugin_name");
?>

<table class="widefat fixed striped" style="background-color:#fff !important;">
    <thead>
    <tr>
        <!--<th><input type="checkbox" id="ux_chk_select_all_db_optimizer" name="ux_chk_cleanup_db" style="margin:0px;">-->
        <!--</th>-->
        <th scope="col" style="width:35px"></th>
        <th scope="col"><?php _e('Table', 'wp-db-booster') ?></th>
        <th scope="col" style="width:8%"><?php _e('Rows', 'wp-db-booster') ?></th>
        <th scope="col" style="width:10%; text-align: center"><?php _e('Type', 'wp-db-booster') ?></th>
        <th scope="col" style="width:80px; text-align: center"><?php _e('Size', 'wp-db-booster') ?></th>
        <th scope="col" style="width:80px; text-align: center"><?php _e('Index Size', 'wp-db-booster') ?></th>
        <th scope="col" nowrap=""><?php _e('Created By', 'wp-db-booster') ?></th>
    </tr>
    </thead>
    <tbody id="the-list" class="all_chks_dp_optimzier">
    <?php
    $sum_size = 0;
    $sum_index_size = 0;
    $sum_rows = 0;
    $dbtables = $wpdb->get_results("SHOW TABLE STATUS");
    $num_tables = count($dbtables);
    foreach ($dbtables as &$table) {
        $numrows = $wpdb->get_var('SELECT COUNT(*) FROM ' . $table->Name);
        $table->numrows = $numrows;
        $sum_size += $table->Data_length;
        $sum_index_size += $table->Index_length;
        $sum_rows += $numrows;
    }

    foreach ($dbtables as &$table) {
    $added_table = false;
    ?>
    <tr>
        <td>
            <?php
            if (!in_array(str_replace($wpdb->prefix, '', $table->Name), $core_tables)) {
                $added_table = true;
                ?>
                <img class="hovertip" data-placement="right"
                     data-original-title="<?php _e('Table added by 3rd party plugin', 'wp-db-booster') ?>"
                     src="<?php echo bloginfo('url') ?>/wp-content/plugins/wp-db-booster/admin/img/table-add.png">
            <?php } else { ?>
                <!--                <img class="hovertip" data-placement="right" data-original-title="WP Core table"-->
                <!--                     src="--><?php //echo bloginfo('url') ?><!--/wp-content/plugins/wp-db-booster/admin/img/table.png">-->
                <i class="fa fa-wordpress hovertip" data-placement="right"
                   data-original-title="<?php _e('WordPress Core Table', 'wp-db-booster') ?>"
                   style="color:#0073A4; font-size:18px"></i>
            <?php } ?>
        </td>
        <td>
            <?php if (in_array(str_replace($wpdb->prefix, '', $table->Name), $core_tables)) echo ""; ?>
            <?php echo $table->Name; ?>
        </td>
        <td style="text-align: right">
            <?php echo $table->numrows ?>
            <?php /*<div class="progress progress-small">
                <?php $perc = ceil(($table->numrows / $sum_rows) * 100); ?>
                <div class="bar-rev"
                     style="width:<?php echo $perc ?>%;background-position:0 <?php echo $perc ?>%"></div>
            </div>*/ ?>
        </td>
        <td style="text-align: center">
            <?php echo $table->Engine ?>
        </td>
        <td style="text-align: right">
            <?php echo $this->helper->filesize_formatted($table->Data_length); ?>
            <?php /*<div class="progress progress-small">
                <?php $perc = ceil(($table->Data_length / $sum_size) * 100); ?>
                <div class="bar-rev"
                     style="width:<?php echo $perc ?>%;background-position:0 <?php echo $perc ?>%"></div>
            </div>*/ ?>
        </td>
        <td align="right">
            <?php echo $this->helper->filesize_formatted($table->Index_length); ?>
        </td>
        <td>
            <?php
            $belongs_to_plugin = false;
            if ($added_table) {
                foreach ($activated as $act_item) {
                    if (strpos($act_item->data, $table->Name) !== false) {
                        $belongs_to_plugin = true;
                        if (is_plugin_active($act_item->plugin)) { ?>
                            <i class="fa fa-play-circle hovertip"
                               data-original-title="<?php _e('Plugin Activate', 'wp-db-booster') ?>"
                               style="color:#006799"></i>
                        <?php } else {
                            ?>
                            <i class="fa fa-pause hovertip"
                               data-original-title="<?php _e('Plugin Inactive', 'wp-db-booster') ?>"
                               style="color:#d0534d"></i>
                            <?php
                        }
                        echo " " . $act_item->plugin_name;
                    }
                }
                if (!$belongs_to_plugin) { ?>
                    <i class="fa fa-question-circle hovertip"
                       data-original-title="<?php _e('Table created by Unknown plugin', 'wp-db-booster') ?>"
                       style="color:#F1C40F"></i>
                <?php }
            } else { ?>
                <i class="fa fa-wordpress hovertip"
                   data-original-title="<?php _e('WordPress Core Table', 'wp-db-booster') ?>"
                   style="color:#0073A4"></i>
                <?php
            }
            ?>
        </td>
        <?php
        }
        ?>
    </tr>
    </tbody>
    <tfoot>
    <tr style="border-top: 1px solid #efefef;">
        <td colspan="2" style="font-family:Tahoma;"><strong><?php _e('Total', 'wp-db-booster') ?>
                : <?php echo $num_tables ?> <?php _e('tables', 'wp-db-booster') ?></strong></td>
        <td style="text-align: right; font-family:Tahoma;"><strong><?php echo $sum_rows ?></strong></td>
        <td></td>
        <td scope="col" style="font-family:Tahoma; text-align: right">
            <strong><?php echo $this->helper->filesize_formatted($sum_size); ?></strong>
        </td>
        <td scope="col" style="font-family:Tahoma; text-align: right">
            <strong><?php echo $this->helper->filesize_formatted($sum_index_size); ?></strong>
        </td>
        <td></td>
    </tr>
    </tfoot>
</table>
<script type="text/javascript">
    jQuery(".hovertip").tooltip_tip({placement: "left"});
</script>