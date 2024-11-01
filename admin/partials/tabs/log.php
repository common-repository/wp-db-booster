<?php

/*$arr_data = array(
    'autodraft' => array(
        'descr' => __('Auto Draft', 'wp-db-booster'),
        'hint' => __('Auto Draft are the Page / Post saved as draft automatically in WordPress Database.', 'wp-db-booster'),
    ),
    'transient_feed' => array(
        'descr' => __('Dashboard Transient Feed', 'wp-db-booster'),
        'hint' => __('Transient Feed in WordPress are use Database entries to cache a certain entries.', 'wp-db-booster'),
    ),
    'draft' => array(
        'descr' => __('Draft', 'wp-db-booster'),
        'hint' => __('New Post / Page created as Draft in WordPress.', 'wp-db-booster'),
    ),
    'comment_meta' => array(
        'descr' => __('Orphan Comments Meta', 'wp-db-booster'),
        'hint' => __('Orphan Comments Meta holds the miscellaneous bits of extra information of comment.', 'wp-db-booster'),
    ),
    'post_meta' => array(
        'descr' => __('Orphans Posts Meta', 'wp-db-booster'),
        'hint' => __('Orphan Posts Meta holds the junk or obsolete data.', 'wp-db-booster'),
    ),
    'relationships' => array(
        'descr' => __('Orphan Relationships', 'wp-db-booster'),
        'hint' => __('Orphan Relationships holds the junk or obsolete Category and Tag.', 'wp-db-booster'),
    ),
    'revisions' => array(
        'descr' => __('Revisions', 'wp-db-booster'),
        'hint' => __('The WordPress revisions system stores a record of each saved draft or published update.Revisions are stored in the posts table.', 'wp-db-booster'),

    ),
    'pingbacks' => array(
        'descr' => __('Pingbacks', 'wp-db-booster'),
        'hint' => __('A Pingback is a type of comment that\'s created when you link to another blog post where pingbacks are enabled.', 'wp-db-booster'),
    ),
    'transient_options' => array(
        'descr' => __('Transient Options', 'wp-db-booster'),
        'hint' => __('Transient Options are like a basic cache system used by wordpress. Clearing these options before a backup will help to save space in your backup files.', 'wp-db-booster'),
    ),
    'trackbacks' => array(
        'descr' => __('Trackbacks', 'wp-db-booster'),
        'hint' => __('Trackbacks are a way to notify legacy blog systems that you have linked to them. If you link to a WordPress blog they will be notified automatically using pingbacks, no other action necessary.', 'wp-db-booster'),
    ),
    'spam_comment' => array(
        'descr' => __('Spam Comments', 'wp-db-booster'),
        'hint' => __('Spam Comments are the unwanted comments in the WordPress database.', 'wp-db-booster'),

    ),
    'trash_comment' => array(
        'descr' => __('Trash Comments', 'wp-db-booster'),
        'hint' => __('Trash Comments are the comments which are stored in the WordPress Trash.', 'wp-db-booster'),
    ),
);*/

$arr_data = array();
foreach($this->types as $key => $data) {
    $arr_data[$data['db']] = array('descr' => $key, 'hint' => $data['hint'], 'type' => $data['type']);
}

$per_page = 30;

$items = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'wpdbboost_log');
if ($items > 0) {
    $p = new pagination;
    $p->items($items);
    $p->limit($per_page); // Limit entries per page
    $p->target("admin.php?page=wp-db-booster-log");
    $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
    $p->calculate(); // Calculates what to show
    $p->parameterName('paging');
    $p->adjacents(1); //No. of page away from the current page

    if (!isset($_GET['paging'])) {
        $p->page = 1;
    } else {
        $p->page = $_GET['paging'];
    }

    //Query for limit paging
    $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;
} else {
    echo "No Record Found";
}

$log = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpdbboost_log ORDER BY updated_at DESC ' . $limit);

$cnt = 0;
if ($_GET['paging'] > 0) $cnt = ($_GET['paging'] - 1) * $per_page;
?>

<div style="border:1px solid #e0dadf; margin:0px 0 30px 0; padding: 2px 10px; border-left:5px solid #d02c21"><p><i class="fa fa-exclamation-circle fa-2x" style="color:#d02c21; display: block; float: left; margin-right:10px"></i> <?php _e('Please keep in mind that Log keeps information about installed plugins and DB tables created by these plugins.<br>If you decide to clear the log this information will be lost.','wp-db-booster')?></p></div>
<div style="margin: 5px 0">
    <select id="wpdbboo_action" name="wpdbboo_action" style="vertical-align:top">
        <option value="0"><?php _e('Bulk Action', 'wp-db-booster') ?></option>
        <option value="all"><?php _e('All Entries', 'wp-db-booster') ?></option>
        <option value="plugins"><?php _e('Plugin Activations/Deactivations', 'wp-db-booster') ?></option>
        <option value="cleanup"><?php _e('Database Cleanups', 'wp-db-booster') ?></option>
    </select>
    <input type="button" id="wpdbboo_btn_deletelog" name="wpdbboo_btn_deletelog"
           class="button-primary" value="<?php _e('Delete Log', 'wp-db-booster') ?>">
    <div class="pull-right"><strong><?php echo $items.' '; _e('Entries in Log','wp-db-booster'); ?></strong></div>
</div>
<table class="widefat fixed striped" style="background-color:#fff !important;">
    <thead>
    <tr>
        <th scope="col" style="width:15px">#</th>
        <th scope="col" style="width:44%"><?php _e('Detail', 'wp-db-booster') ?></th>
        <th scope="col" style="width:17%"><?php _e('Action', 'wp-db-booster') ?></th>
        <th scope="col" style="width:17%"><?php _e('Date', 'wp-db-booster') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($log as $row): $cnt++ ?>
        <tr>
            <td>
                <?php echo $cnt; ?>
            </td>
            <td>
                <?php
                if ($row->action == 'activated' || $row->action == 'deactivated') {
                    _e('Plugin', 'wp-db-booster'); echo ': ';
                    echo "<strong>".$row->plugin_name."</strong>";
                }
                if ($row->action == 'cleanup') { _e('Data type:','wp-db-booster'); echo ' <span class="underline hovertip" data-placement="right" data-original-title="'.$arr_data[$row->data]['hint'].'"><strong>'.$arr_data[$row->data]['descr'].'</strong></span>'; }
                ?>
            </td>
            <td>
                <?php
                if ($row->action == 'cleanup') {
                    ?>
                    <i class="fa fa-trash hovertip"
                       data-original-title="<?php _e('Database Cleanup', 'wp-db-booster') ?>" style="color:#202020"></i>
                    <?php
                    _e('Database Cleanup', 'wp-db-booster');
                }
                if ($row->action == 'activated') {
                    ?>
                    <i class="fa fa-play-circle hovertip"
                       data-original-title="<?php _e('Plugin Activated', 'wp-db-booster') ?>" style="color:#579e34"></i>
                    <?php
                    _e('Plugin Activated', 'wp-db-booster');
                }
                if ($row->action == 'deactivated') {
                    ?>
                    <i class="fa fa-pause hovertip"
                       data-original-title="<?php _e('Plugin Deactivated', 'wp-db-booster') ?>"
                       style="color:#d0534d"></i>
                    <?php
                    _e('Plugin Deactivated', 'wp-db-booster');
                }
                ?>
            </td>
            <td><?php echo Date('d M Y H:i:s', strtotime($row->updated_at)); ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<div class="tablenav">
    <div class='tablenav-pages'>
        <?php
              if(is_object($p)) echo $p->show();  // Echo out the list of paging.
        ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(".hovertip").tooltip_tip({placement: "left"});
</script>