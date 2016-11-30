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

<?= $str ?>
