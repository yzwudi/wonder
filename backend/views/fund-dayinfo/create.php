<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FundDayInfo */

$this->title = 'Create Fund Day Info';
$this->params['breadcrumbs'][] = ['label' => 'Fund Day Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-day-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
