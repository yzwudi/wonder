<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/25
 * Time: 16:26
 */
$this->title = '基金估值详情';
//array_unshift($date, 'x');
array_unshift($values, '上月走势');
$this->registerJs(
    "var columns = ". json_encode([
        $values,
        $current,
        $max,
        $min
    ]).';'.
    'var categories = '.json_encode($date).';'
    ,$this::POS_HEAD
);

$this->registerJsFile('/c3/c3.min.js');
$this->registerJsFile('/c3/d3.v3.min.js');
$this->registerCssFile('/c3/c3.min.css');
$this->registerJsFile('/static/script/modules/tools/forecastDetail.js');

?>


<div id='chart' style="width:95%"></div>



