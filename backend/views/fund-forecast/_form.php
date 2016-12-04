<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FundForecastInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-forecast-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fund_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_forecast')->textInput() ?>

    <?= $form->field($model, 'min_forecast')->textInput() ?>

    <?= $form->field($model, 'avg_forecast')->textInput() ?>

    <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
