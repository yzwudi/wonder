<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/25
 * Time: 16:26
 */
$this->title = '基金估值详情';
$this->registerJs(
    "var columns = ".json_encode([
        ['上月走势', 30, 200, 100, 400, 150, 250, 333, 30, 200, 100, 400, 150, 250, 333,  30, 200, 100, 400, 150, 250, 333, 150, 250, 333],
        ['当前值', 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30],
        ['最低值', 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30],
    ]),$this::POS_HEAD
);

$this->registerJsFile('/c3/c3.min.js');
$this->registerJsFile('/c3/d3.v3.min.js');
$this->registerCssFile('/c3/c3.min.css');
$this->registerJsFile('/static/script/modules/tools/forecastDetail.js');


?>

<div id='chart' style="width:90%"></div>



