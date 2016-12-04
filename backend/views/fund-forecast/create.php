<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FundForecastInfo */

$this->title = 'Create Fund Forecast Info';
$this->params['breadcrumbs'][] = ['label' => 'Fund Forecast Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-forecast-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
