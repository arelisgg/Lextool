<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ElementType;
use wbraganca\dynamicform\DynamicFormWidget;
use backend\models\ElementSubType;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Element */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->project->name?></div>
<div class="element-form">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title ?></h2>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'element-form',
            ]); ?>

            <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

            <?= $form->field($model,'id_element_type')->widget(Select2::className(),[
                'data' => ArrayHelper::map(ElementType::find()->where(['removed' => false])->all(),'id_element_type','name'),
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'onChange' => 'vaciar()',
                    ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>

            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'property')->widget(Select2::classname(), [
                        'data' => [
                            'Descripción'=>'Descripción',
                            "Redacción"=>'Redacción',
                            "Lema-entrada"=>'Lema-entrada',
                            "Lema-remisión"=>'Lema-remisión'
                        ],
                        'options' => ['placeholder' => 'Seleccione...',],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'visibility')->widget(Select2::classname(), [
                        'data' => [1=>'Sí', 0=>'No'],
                        'options' => ['placeholder' => 'Seleccione...',],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3" >
                    <?= $form->field($model, 'font')->widget(Select2::classname(), [
                        'data' => [
                            'Arial'=>'Arial',
                            'Open Sans'=>'Open Sans',
                            'Calibri'=>'Calibri',
                            'Centaur'=>'Centaur',
                            'Century'=>'Century',
                            'Consolas'=>'Consolas',
                            'Constantia'=>'Constantia',
                            'Courier New'=>'Courier New',
                            'Georgia'=>'Georgia',
                            'Malgun Gothic'=>'Malgun Gothic',
                            'Segoe UI'=>'Segoe UI',
                            'Symbol'=>'Symbol',
                            'Tahoma'=>'Tahoma',
                        ],
                        'options' => ['placeholder' => 'Seleccione...',],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'font_size')->input("number",["min"=>8, "max"=>200, "step"=>1, "value"=>12])?>
                </div>

                <div class="col-lg-2">
                    <div class="form-group field-element-color required">
                        <label class="control-label" for="element-color">Color</label>
                        <input type="text" id="element-color" value="<?=$model->isNewRecord? "000000": $model->color?>" class="pick-a-color form-control" name="Element[color]" aria-required="true" aria-invalid="true">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group field-element-background required">
                        <label class="control-label" for="element-background">Color de fondo</label>
                        <input type="text" id="element-background" value="<?=$model->isNewRecord? "ffffff": $model->background?>" class="pick-a-color form-control" name="Element[background]" aria-required="true" aria-invalid="true">
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-lg-2" style="padding-left: 0px; padding-top: 25px">
                    <div class="form-group">
                        <input type="text" id="element-font_weight" class="form-control hidden font_weight" name="Element[font_weight]" value="<?=$model->isNewRecord? "normal": $model->font_weight?>" aria-required="true" aria-invalid="true">
                        <button class="btn" type="button" onclick="negrita($(this))" style="background-color: #ffffff; border-color: #fff;">
                            <span class="fa fa-bold"></span>
                        </button>

                        <input type="text" id="element-font_style" class="form-control hidden font_style" name="Element[font_style]" value="<?=$model->isNewRecord? "normal": $model->font_style?>" aria-required="true" aria-invalid="true">
                        <button class="btn" type="button" onclick="italic($(this))" style="background-color: #ffffff; border-color: #fff;">
                            <span class="fa fa-italic"></span>
                        </button>

                        <input type="text" id="element-text_decoration" class="form-control hidden text_decoration" name="Element[text_decoration]" value="<?=$model->isNewRecord? "none": $model->text_decoration?>" aria-required="true" aria-invalid="true">
                        <button class="btn" type="button" onclick="text_decoration($(this))" style="background-color: #ffffff; border-color: #fff;">
                            <span class="fa fa-underline"></span>
                        </button>

                        <input type="text" id="element-text_transform" class="form-control hidden text_transform" name="Element[text_transform]" value="<?=$model->isNewRecord? "none": $model->text_transform?>" aria-required="true" aria-invalid="true">
                        <button class="btn" type="button" onclick="text_transform($(this))" style="background-color: #ffffff; border-color: #fff;">
                            <span class="fa fa-text-height"></span>
                        </button>
                    </div>
                </div>

            </div>

            <br>


            <div>
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'sub_element_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.sub_element_items', // required: css class selector
                    'widgetItem' => '.sub_element', // required: css class
                    'limit' => 200, // the maximum times, an element can be cloned (default 999)
                    'min' => 0, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelSubElements[0],
                    'formId' => 'element-form',
                    'formFields' => [
                        'id_element_sub_type',
                        'visibility',
                        'font',
                        'font_size',
                        'font_weight',
                        'font_style',
                        'text_decoration',
                        'color',
                        'background',
                        'text_transform',
                    ],
                ]); ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title"><i class="fa fa-square-o"></i> Subelementos lexicográficos</h2>
                        <button type="button" class="add-item btn btn-success btn-sm pull-right">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>
                    <div class="box-body">

                        <div class="sub_element_items"><!-- widgetContainer -->
                            <?php foreach ($modelSubElements as $i => $modelSubElement): ?>
                                <div class="sub_element"><!-- widgetBody -->
                                    <?php
                                    // necessary for update action.
                                    if (!$modelSubElement->isNewRecord) {
                                        echo Html::activeHiddenInput($modelSubElement, "[{$i}]id_sub_element");
                                        if ($modelSubElement->visibility == false)
                                            $modelSubElement->visibility = 0;
                                    }
                                    ?>

                                    <div class="col-lg-11" style="padding-left: 0px; padding-right: 0px; ">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?= $form->field($modelSubElement, "[{$i}]id_element_sub_type")->widget(Select2::classname(), [
                                                    //'initValueText' => $modelSubElement->id_element_sub_type, // set the initial display text
                                                    'data' => ArrayHelper::map(ElementSubType::find()->where(['removed' => false, 'id_element_type'=>$model->id_element_type])->orderBy('name')->all(),'id_element_sub_type','name'),
                                                    'language' => 'es',
                                                    'options' => [
                                                            'placeholder' => 'Seleccione...',
                                                            'class' => 'sub-element',
                                                        ],
                                                    //'hideSearch' => true,
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                        //'minimumInputLength' => 1,
                                                        'language' => [
                                                            'errorLoading' => new JsExpression("function () { return 'Esperando por resultados...'; }"),
                                                        ],
                                                        'ajax' => [
                                                            'url' => Url::to(['/element/ajax_sub_element_list']),
                                                            'dataType' => 'json',
                                                            'data' => new JsExpression('function(params) { return {q:params.term,id:$("#element-id_element_type").get(0).value}; }'),
                                                            'cache' =>true,
                                                        ],
                                                    ],
                                                ]) ?>

                                            </div>
                                            <div class="col-lg-6">
                                                <?= $form->field($modelSubElement, "[{$i}]visibility")->widget(Select2::classname(), [
                                                    'data' => [1=>'Sí', 0=>'No'],
                                                    'options' => ['placeholder' => 'Seleccione...',],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3" >
                                                <?= $form->field($modelSubElement, "[{$i}]font")->widget(Select2::classname(), [
                                                    'data' => [
                                                        'Arial'=>'Arial',
                                                        'Open Sans'=>'Open Sans',
                                                        'Calibri'=>'Calibri',
                                                        'Centaur'=>'Centaur',
                                                        'Century'=>'Century',
                                                        'Consolas'=>'Consolas',
                                                        'Constantia'=>'Constantia',
                                                        'Courier New'=>'Courier New',
                                                        'Georgia'=>'Georgia',
                                                        'Malgun Gothic'=>'Malgun Gothic',
                                                        'Segoe UI'=>'Segoe UI',
                                                        'Symbol'=>'Symbol',
                                                        'Tahoma'=>'Tahoma',
                                                    ],
                                                    'options' => ['placeholder' => 'Seleccione...',],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                ]) ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <?= $form->field($modelSubElement, "[{$i}]font_size")->input("number",["min"=>8, "max"=>200, "step"=>1, "value"=>12])?>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group field-subelement-<?=$i?>-color required">
                                                    <label class="control-label" for="subelement-<?=$i?>-color">Color</label>
                                                    <input type="text" id="subelement-<?=$i?>-color" value="<?=$modelSubElement->isNewRecord? "000000": $modelSubElement->color?>" class="pick-a-color form-control color" name="SubElement[<?=$i?>][color]" aria-required="true" aria-invalid="true">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group field-subelement-<?=$i?>-background required">
                                                    <label class="control-label" for="subelement-<?=$i?>-background">Color de fondo</label>
                                                    <input type="text" id="subelement-<?=$i?>-background" value="<?=$modelSubElement->isNewRecord? "ffffff": $modelSubElement->background?>" class="pick-a-color form-control" name="SubElement[<?=$i?>][background]" aria-required="true" aria-invalid="true">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2" style="padding-left: 0px; padding-top: 25px; padding-right: 0px;">
                                                <div class="form-group">
                                                    <input type="text" id="subelement-<?=$i?>-font_weight" class="form-control hidden font_weight" name="SubElement[<?=$i?>][font_weight]" value="<?=$modelSubElement->isNewRecord? "normal": $modelSubElement->font_weight?>" aria-required="true" aria-invalid="true">
                                                    <button class="btn" type="button" onclick="negrita($(this))" style="background-color: #ffffff; border-color: #fff;">
                                                        <span class="fa fa-bold"></span>
                                                    </button>

                                                    <input type="text" id="subelement-<?=$i?>-font_style" class="form-control hidden font_style" name="SubElement[<?=$i?>][font_style]" value="<?=$modelSubElement->isNewRecord? "normal": $modelSubElement->font_style?>" aria-required="true" aria-invalid="true">
                                                    <button class="btn" type="button" onclick="italic($(this))" style="background-color: #ffffff; border-color: #fff;">
                                                        <span class="fa fa-italic"></span>
                                                    </button>

                                                    <input type="text" id="subelement-<?=$i?>-text_decoration" class="form-control hidden text_decoration" name="SubElement[<?=$i?>][text_decoration]" value="<?=$modelSubElement->isNewRecord? "none": $modelSubElement->text_decoration?>" aria-required="true" aria-invalid="true">
                                                    <button class="btn" type="button" onclick="text_decoration($(this))" style="background-color: #ffffff; border-color: #fff;">
                                                        <span class="fa fa-underline"></span>
                                                    </button>

                                                    <input type="text" id="subelement-<?=$i?>-text_transform" class="form-control hidden text_transform" name="SubElement[<?=$i?>][text_transform]" value="<?=$modelSubElement->isNewRecord? "none": $modelSubElement->text_transform?>" aria-required="true" aria-invalid="true">
                                                    <button class="btn" type="button" onclick="text_transform($(this))" style="background-color: #ffffff; border-color: #fff;">
                                                        <span class="fa fa-text-height"></span>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>



                                        <hr>
                                    </div>
                                    <div class="col-lg-1"  style="padding-left: 0px; padding-right: 0px; margin-top: 30px; text-align: center">
                                        <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                        <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))">Eliminar</button>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php DynamicFormWidget::end(); ?>
            </div>
            <br><br>

            <div class="form-group" style="text-align: right">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <a href="<?= Url::toRoute(["element/index",'id_project' => $model->id_project]) ?>" type="button" class="btn btn-default" >Cancelar</a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {

        $(".pick-a-color").pickAColor({
            showSpectrum            : true,
            showSavedColors         : true,
            saveColorsPerElement    : true,
            fadeMenuToggle          : true,
            showAdvanced			: true,
            showBasicColors         : true,
            showHexInput            : true,
            allowBlank				: true,
            inlineDropdown			: true
        });

    });

</script>