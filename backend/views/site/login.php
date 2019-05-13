<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Lextool';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="login-box">
    <div class="login-logo">
        <a href="<?=Yii::$app->homeUrl?>"><b>Lex</b>Tool</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Inicie sesión</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div>
            Si olvidó su contraseña puede <?= Html::a('restablecerla', ['site/request-password-reset']) ?>.
        </div>
        <br>

        <div class="form-group" style="text-align: right; padding-bottom: 10px">

            <?= Html::a('Registrarse', 'singup', [ 'class' => 'btn btn-success']) ?>
            <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>


        </div>

        <?php ActiveForm::end(); ?>

        <!--<a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>-->

    </div>

</div>

