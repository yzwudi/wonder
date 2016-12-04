<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FundForecastInfo */

$this->title = 'Update Fund Forecast Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Forecast Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fund-forecast-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
