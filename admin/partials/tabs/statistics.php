<?php
$actions = Array(
    'activated' => __('Plugin Activated','wp-db-booster'),
    'deactivated' => __('Plugin Deactivated','wp-db-booster'),
    'cleanup' => __('CleanUp','wp-db-booster'),
);

$dbtables = $wpdb->get_results("SHOW TABLE STATUS");
$num_tables = count($dbtables);
$arr_by_rows = array();
$arr_by_size = array();
$sum_size = 0;
$sum_index_size = 0;
$sum_rows = 0;
foreach ($dbtables as $table) {
    $arr_by_rows[$table->Name] = $wpdb->get_var("SELECT COUNT(*) FROM $table->Name");
    $arr_by_size[$table->Name] = $table->Data_length;
    $sum_size += $table->Data_length;
    $sum_rows += $arr_by_rows[$table->Name];
}
uasort($arr_by_rows, array($this->helper, 'compare'));
uasort($arr_by_size, array($this->helper, 'compare'));

// Log analysis
$log = $wpdb->get_results("SELECT count(*) as cnt, action FROM {$wpdb->prefix}wpdbboost_log GROUP BY action ORDER BY cnt DESC");

?>
<h3><?php _e('DB Tables sorted by number of rows','wp-db-booster') ?></h3>
<div id="bar-chart-rows"></div>

<h3><?php _e('DB Tables sorted by size (bytes)','wp-db-booster') ?></h3>
<div id="bar-chart-size"></div>

<h3><?php _e('Log Analysis','wp-db-booster') ?></h3>
<div id="bar-chart-log"></div>

<script type="application/javascript">
    var data = [
            /*{y: '2014', a: 50, b: 90},
             {y: '2015', a: 65, b: 75},*/
            <?php
              foreach($arr_by_rows as $key => $val) {
              if($val > 0)
                echo '{y:\''.$key.'\',a: '.$val.'},';
              }
            ?>
        ],
        config = {
            data: data,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['<?php _e('Rows','wp-db-booster'); ?>'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            behaveLikeLine: true,
            resize: true,
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            lineColors: ['gray', 'red']
        };
    config.element = 'bar-chart-rows';
    Morris.Bar(config);

    var data = [
            <?php
              foreach($arr_by_size as $key => $val) {
              if($val > 0)
                echo '{y:\''.$key.'\',a: '.$val.'},';
              }
            ?>
        ],
        config = {
            data: data,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['<?php _e('Size in Bytes','wp-db-booster'); ?>'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            behaveLikeLine: true,
            resize: true,
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            lineColors: ['gray', 'red']
        };
    config.element = 'bar-chart-size';
    Morris.Bar(config);

    var data = [
                <?php
                  foreach($log as $item) {
                    echo '{y:\''.$actions[$item->action].'\',a: '.$item->cnt.'},';
                  }
                ?>
            ],
            config = {
                data: data,
                xkey: 'y',
                ykeys: ['a'],
                labels: ['<?php _e('Actions in Log','wp-db-booster'); ?>'],
                fillOpacity: 0.6,
                hideHover: 'auto',
                behaveLikeLine: true,
                resize: true,
                pointFillColors: ['#ffffff'],
                pointStrokeColors: ['black'],
                lineColors: ['gray', 'red']
            };
        config.element = 'bar-chart-log';
        Morris.Bar(config);
</script>
