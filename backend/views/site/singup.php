<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Registrarse';
$this->params['breadcrumbs'][] = ['label' => 'Autenticarse', 'url' => ['login']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="grid-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="glyphicon glyphicon-user"></i> <?= $this->title?></h2>
        </div>
        <br>

        <div class="box-body" style="margin: 10px">

            <?php $form = ActiveForm::begin([
                'id' => 'user-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => true,
            ]); ?>

            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'confirmPassword')->input("password",["class"=>"form-control"]) ?>

            <div class="form-group" style="text-align: right">
                <?= Html::submitButton('Guardar' , ['class' => 'btn btn-success', 'id' =>'btn-create-user']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>



</div>
