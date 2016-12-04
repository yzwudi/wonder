<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundForecastSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Forecast Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-forecast-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Fund Forecast Info', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=$mes?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fund_id',
            'name',
            'max_forecast',
            'min_forecast',
            'avg_forecast',
            // 'month',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
