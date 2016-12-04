<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundForecastSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '基金估值';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="fund-forecast-info-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="fund-forecast-info-create">

        <div class="fund-forecast-info-form" style="padding-right: 70%">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'fund_id')
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('fund_id')]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? '添加基金' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <form role="form">
                <div class="form-group">
                    <input type="text" name="searchId" class="form-control" id="searchId" placeholder="请输入基金ID" >
                    <input type="text" name="searchName" class="form-control" id="searchName" placeholder="请输入基金名称">
                </div>
                <button type="submit" class="btn btn-primary">查询</button>
            </form>

        </div>

    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
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
