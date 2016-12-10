<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/30
 * Time: 21:09
 */
use yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = '基金工具';
//$this->params['breadcrumbs'][] = false;
$display = $info ? "play" : "none";
?>
<?php $form = ActiveForm::begin(['id' => 'fund-tool', 'enableClientValidation' => false]); ?>
<div style="padding: 15px 80% 10px 0px;">
    <?= $form
        ->field($model, 'fund_id')
        ->label(false)
        ->textInput(['placeholder' => $model->getAttributeLabel('fund_id')]) ?>
    <div class="row">
        <div class="col-xs-4">
            <?= Html::submitButton('查询', ['class' => 'btn btn-primary btn-flat', 'name' => 'fund-tool-button']) ?>
        </div>
        <!-- /.col -->
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div style="padding: 15px 30% 10px 0px;">
    <table class="table table-hover"  bordercolor="#FF9933" style="display:<?=$display?>"/>
        <thead>
            <tr>
                <th><?=date('m')?>月预估最高价</th>
                <th><?=date('m')?>月预估最低价</th>
                <th><?=date('m')?>月预估买卖价</th>
                <th>提示</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=isset($info['max'])? $info['max'] : null?></td>
                <td><?=isset($info['min'])? $info['min'] : null?></td>
                <td><?=isset($info['avg'])? $info['avg'] : null?></td>
                <td><?=isset($info['avg'])? $info['info'] : null?></td>
            </tr>
        </tbody>
    </table>
</div>