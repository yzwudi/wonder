<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/27
 * Time: 20:37
 */
$this->title = '添加新用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = \yii\bootstrap\ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->label('登陆名')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email')->label('邮箱') ?>

            <?= $form->field($model, 'password')->label('密码')->passwordInput() ?>

            <?= $form->field($model, 'password_compare')->label('确认密码')->passwordInput() ?>

            <div class="form-group">
                <?= \yii\bootstrap\Html::submitButton('添加', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
    </div>
</div>