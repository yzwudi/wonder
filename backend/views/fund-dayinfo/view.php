<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\FundDayInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Day Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-day-info-view">

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
            'name_en',
            'date',
            'unit_net_value',
            'total_net_value',
            'day_gr',
            'week_gr',
            'month_gr',
            'three_month_gr',
            'six_month_gr',
            'year_gr',
            'two_year_gr',
            'three_year_gr',
            'this_year_gr',
            'establish_gr',
            'self_define',
            'poundage',
            'create_time',
        ],
    ]) ?>

</div>
