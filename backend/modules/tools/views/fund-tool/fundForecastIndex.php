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
            <div>
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'fund_id')
                    ->label(false)
                    ->textInput([
                        'placeholder' => $model->getAttributeLabel('fund_id'),
                        'style' => 'float:left;width:150px;margin-right:5px'
                    ]) ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '添加基金' : '更新', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <form role="form" class="form-group">
                <div class="form-group">
                    <input style="float: left;width: 150px;margin-right: 5px" type="text" value="<?=isset($searchId)?$searchId:''?>" name="searchId" class="form-control" id="searchId" placeholder="请输入基金ID" autocomplete="off">
                    <input style="float: left;width: 150px;margin-right: 5px" type="text" value="<?=isset($searchName)?$searchName:''?>" name="searchName" class="form-control" id="searchName" placeholder="请输入基金名称" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary">查询</button>
            </form>

        </div>

    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //['class' => \yii\grid\CheckboxColumn::className()],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'fund_id',
            'name',
            'max_forecast',
            'min_forecast',
            'avg_forecast',
            'current_value',
            'info',

            //['class' => 'yii\grid\ActionColumn', 'template' => '{view}  {delete}'],
            [
                'class'      => 'yii\grid\ActionColumn',
                'template' => '{view}  {delete}',
                'header'     => '<a>操作</a>',
                'buttons'    => [],

                'urlCreator' => function ($action, $model, $key, $index) {
                    switch($action)
                    {
                        case 'view':
                            return '/tools/fund-tool/forecast-detail?fund_id=' . $model->fund_id;
                            break;
                        case 'delete':
                            return '/tools/fund-tool/fund-forecast-delete?id=' . $model->id;
                            break;
                    }

                },
            ]
        ],
    ]); ?>
</div>
