<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FundBestInfo */

$this->title = 'Create Fund Best Info';
$this->params['breadcrumbs'][] = ['label' => 'Fund Best Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-best-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
