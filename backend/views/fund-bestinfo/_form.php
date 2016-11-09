<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FundBestInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-best-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fund_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_net_value')->textInput() ?>

    <?= $form->field($model, 'total_net_value')->textInput() ?>

    <?= $form->field($model, 'day_gr')->textInput() ?>

    <?= $form->field($model, 'week_gr')->textInput() ?>

    <?= $form->field($model, 'month_gr')->textInput() ?>

    <?= $form->field($model, 'three_month_gr')->textInput() ?>

    <?= $form->field($model, 'six_month_gr')->textInput() ?>

    <?= $form->field($model, 'year_gr')->textInput() ?>

    <?= $form->field($model, 'two_year_gr')->textInput() ?>

    <?= $form->field($model, 'three_year_gr')->textInput() ?>

    <?= $form->field($model, 'this_year_gr')->textInput() ?>

    <?= $form->field($model, 'establish_gr')->textInput() ?>

    <?= $form->field($model, 'self_define')->textInput() ?>

    <?= $form->field($model, 'poundage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
