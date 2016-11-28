<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<style type="text/css">
    .txt {
        height: 34px;
        width: 60%;
        border: 1px solid #d4d4d4;
        margin: 0 5px 10px 0;
        padding: 0 0 0px 10px;
        font-size: 14px;
    }
</style>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Wonder</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <h4 class="login-box-msg">开启探索之旅</h4>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('用户名/邮箱')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('密码')]) ?>

        <?= $form->field($model,'verifyCode',[
            'wrapperOptions' => ['class' => 'verifyCodeDiv'],
            'labelOptions' => ['label' => '验证码', 'style' => "display:none"],
            "errorOptions" => ["class" => 'error']
        ])->widget(yii\captcha\Captcha::className(),
                [
                    'captchaAction'=>'site/captcha',
                    'template' => '{input}{image}<br>',
                    'options' => [
                        "placeholder" => "请输入验证码",
                        "class" => "txt",
                    ]
                ]);?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> facebook登录</a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Google+登录</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="#">忘记密码</a><br>
        <a href="/site/register" class="text-center">注册用户</a>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
