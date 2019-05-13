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
        //'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'enabled')->widget(Select2::classname(), [
        'data' => [1=>'Sí', 0=>'No'],
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <div class="form-group" style="text-align: right">
        <?= Html::submitButton('Editar', ['class' => 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
