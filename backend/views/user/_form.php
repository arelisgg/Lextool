<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\AuthItem;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->input("password",["class"=>"form-control"])?>

    <?= $form->field($model, 'confirmPassword')->input("password",["class"=>"form-control"]) ?>

    <?= $form->field($model, 'full_name')->textInput(["class"=>"form-control"]) ?>

    <?= $form->field($model, 'email')->input("email",["class"=>"form-control"]) ?>

    <?= $form->field($model,'rol')->widget(Select2::className(),[
        'data' => ArrayHelper::map(AuthItem::find()->all(),'name','name'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <div class="form-group" style="text-align: right">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
