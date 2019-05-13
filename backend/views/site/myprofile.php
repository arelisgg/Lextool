<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Mi perfil: ' . $model->username;
$this->params['breadcrumbs'][] = 'Mi perfil';

?>

<div class="grid-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-user"></i> <?= $this->title?></h2>
        </div>


        <div class="box-body" style=" margin-left:  10px; margin-right: 10px">


            <?php
            $form = ActiveForm::begin([
                'id' => 'myprofile-form',
                'enableAjaxValidation' => true,
            ]); ?>

            <?= $form->field($model, 'full_name')->textInput(["class"=>"form-control"]) ?>

            <?= $form->field($model, 'email')->input("email",["class"=>"form-control"]) ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'oldPassword')->input("password",["class"=>"form-control"])?>

            <?= $form->field($model, 'password')->input("password",["class"=>"form-control"])->label("Nueva contraseña")?>

            <?= $form->field($model, 'confirmPassword')->input("password",["class"=>"form-control"]) ?>

            <div class="form-group" style="text-align: right;">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btn-update-user']) ?>
            </div>

            <?php
            ActiveForm::end();
            ?>

        </div>


    </div>
</div>

