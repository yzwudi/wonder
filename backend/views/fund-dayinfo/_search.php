<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FundDayInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-day-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fund_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_en') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'unit_net_value') ?>

    <?php // echo $form->field($model, 'total_net_value') ?>

    <?php // echo $form->field($model, 'day_gr') ?>

    <?php // echo $form->field($model, 'week_gr') ?>

    <?php // echo $form->field($model, 'month_gr') ?>

    <?php // echo $form->field($model, 'three_month_gr') ?>

    <?php // echo $form->field($model, 'six_month_gr') ?>

    <?php // echo $form->field($model, 'year_gr') ?>

    <?php // echo $form->field($model, 'two_year_gr') ?>

    <?php // echo $form->field($model, 'three_year_gr') ?>

    <?php // echo $form->field($model, 'this_year_gr') ?>

    <?php // echo $form->field($model, 'establish_gr') ?>

    <?php // echo $form->field($model, 'self_define') ?>

    <?php // echo $form->field($model, 'poundage') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
