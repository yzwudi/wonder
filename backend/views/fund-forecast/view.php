<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\FundForecastInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Forecast Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-forecast-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fund_id',
            'name',
            'max_forecast',
            'min_forecast',
            'avg_forecast',
            'month',
        ],
    ]) ?>

</div>
