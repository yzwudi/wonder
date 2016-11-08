<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundayInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '基金排名';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-day-info-index">

    <h2><?= Html::encode('截止'.date('Y-m-d',time()).'日 3年排名前300基金') ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a('Create Fund Day Info', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fund_id',
            'name',
            'name_en',
            'month_gr',
            'three_year_gr',
            'this_year_gr',

            // 'unit_net_value',
            // 'total_net_value',
            // 'day_gr',
            // 'week_gr',
            // 'month_gr',
            // 'three_month_gr',
            // 'six_month_gr',
            // 'year_gr',
            // 'two_year_gr',
            // 'three_year_gr',
            // 'this_year_gr',
            // 'establish_gr',
            // 'self_define',
            // 'poundage',
            // 'create_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
