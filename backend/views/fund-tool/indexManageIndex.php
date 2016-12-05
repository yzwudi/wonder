<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/4
 * Time: 21:32
 */
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\ActiveForm;
$this->title = '基金估值';

?>
<?php

$form = ActiveForm::begin(['id' => 'login-form']);
 ?>
<div class="form-group" style="padding-right: 80%">
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'date',
        'template' => '{addon}{input}',
        'language' => 'zh-CN',
        //'placeholder'=>'2015-12-05',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'date'=>'2015-12-05',
        ]
    ]);?>
</div>
<div class="form-group">
    <?= \yii\bootstrap\Html::submitButton( '按日期查询' , ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="index-manage-info" style="width:98%">
    <table class="table table-hover" style="font-size:15px">
        <caption><?=date('Y-m-d', time()).' 各指数详情'?></caption>
        <thead>
        <tr class="success">
            <th><?=$model->getAttributeLabel('index_name')?></th>
            <th><?=$model->getAttributeLabel('index')?></th>
            <th><?=$model->getAttributeLabel('yield_rate')?></th>
            <th><?=$model->getAttributeLabel('pe')?></th>
            <th><?=$model->getAttributeLabel('pb')?></th>
            <th><?=$model->getAttributeLabel('dividend_rate')?></th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($indexList as $val){
                if($val['pe']<10){
                    $color = 'success';
                }elseif($val['pe']>30){
                    $color = 'danger';
                }else{
                    $color = 'warning';
                }
                echo "<tr class=\"".$color."\">";
                echo "<td>".$val['index_name']."</td>";
                echo "<td>".$val['index']."</td>";
                echo "<td>".$val['yield_rate']."</td>";
                echo "<td>".$val['pe']."</td>";
                echo "<td>".$val['pb']."</td>";
                echo "<td>".$val['dividend_rate']."</td>";
                echo "<tr>";
            }

        ?>
        </tbody>
    </table>
</div>
