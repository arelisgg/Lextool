<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Separator */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="separator-form">
    <div id="paleta" class="fade modal" role="dialog" style="display: none; overflow-y: hidden;">
        <div class="modal-dialog" style="margin: 0px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" onclick="closePaleta()" class="close" aria-hidden="true">×</button>
                    <strong>Paleta de separadores</strong>
                </div>
                <div class="modal-body">
                    <div style="display: block; overflow-y: auto; height: 265px">
                        <table class="table table-bordered dataTable" style="margin-bottom: 0px">
                            <tbody>
                                <tr role="row">
                                    <td class="select" style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">º</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¹</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">²</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">³</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁴</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁵</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁶</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁷</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁸</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁹</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ⁱ</td>
                                </tr>

                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁺</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁻</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁼</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁽</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁾</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ⁿ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʰ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʱ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʲ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʳ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ª</td>
                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʶ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʷ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʸ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">°</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">´</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʺ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʻ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʼ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʽ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ˀ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ˁ</td>
                                </tr>

                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ˆ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ˇ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ˈ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¨</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">“</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">‴</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʴ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ʵ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">«</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">»</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¬</td>
                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">&</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">#</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">§</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">µ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¶</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">@</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">$</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">£</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">₤</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¢</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">₡</td>


                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¥</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">€</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">₿</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">©</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">℗</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">±</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">¿</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">?</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ф</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">℠</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">™</td>



                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">†</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">‡</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">•</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">…</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⁞</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">‼</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">‽</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∂</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∆</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∏</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∑</td>
                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">−</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">+</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∕</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∙</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">*</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">√</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∞</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∟</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∩</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">∫</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">≈</td>
                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">≠</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">≡</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">≤</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))"><</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">≥</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">></td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">□</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">◊</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">○</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">●</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⌂</td>
                                </tr>
                                <tr role="row">
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">⃝ </td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">˥</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">˦</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">˧</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">˨</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">˩</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">׀</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">‖</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">ǂ</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">[</td>
                                    <td style="text-align: center; vertical-align: middle; " onclick="selectSymbol($(this))">]</td>

                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div style="text-align: right; margin-top: 15px">
                        <button type="button" class="btn btn-info" onclick="insertSymbol()">Insertar</button>
                        <button type="button" class="btn btn-default" onclick="closePaleta()">Cancelar</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'separator-form',
    ]); ?>

    <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model, 'type')->widget(Select2::classname(), [
        'data' => ['Signo'=>'Signo', 'Símbolo'=>'Símbolo'],
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <label class="control-label" for="separator-type">Representación</label>
    <?= $form->field($model, 'representation',['options'=>['tag'=>'div','class'=>'form-group', 'style'=>'display: flex'],
        'template'=>'{input}<button type="button" class="btn btn-default" onclick="openPaleta()"><span class="fa fa-paragraph"></span></button>'
    ])->textInput()->label("Representación") ?>

    <?= $form->field($model, 'scope')->widget(Select2::classname(), [
        'data' => ['Elemento'=>'Elemento', 'Componente'=>'Componente'],
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <div class="form-group" style="text-align: right">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $('form#separator-form').on('beforeSubmit', function(e)
    {
        var form = $(this);
        $.post(
            form.attr("action"),
            form.serialize()
        ).done(function(result) {
            if (result == "Error"){
                krajeeDialogError.alert("No se ha podido crear el separador, ha ocurrido un error.")
            } else {
                $(form).trigger("reset");
                $.pjax.reload({container: '#separator-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('El separador ha sido '+result+'.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>