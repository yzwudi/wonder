<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FundForecastSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-forecast-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fund_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'max_forecast') ?>

    <?= $form->field($model, 'min_forecast') ?>

    <?php // echo $form->field($model, 'avg_forecast') ?>

    <?php // echo $form->field($model, 'month') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
