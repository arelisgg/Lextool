<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="document-form">

    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>
            <?= $form->field($model, 'id_doc_type')->hiddenInput(['value' => $ext_plan->id_doc_type ])->label(false) ?>
            <?= $form->field($model, 'id_doc_ext_plan')->hiddenInput(['value' => $ext_plan->id_doc_ext_plan ])->label(false) ?>
            <?= $form->field($model, 'id_user')->hiddenInput(['value' => Yii::$app->user->id ])->label(false) ?>
            <input id="id_lemma" name="id_document" type="hidden" class="form-control" value="<?= $model->id_document ?>">
            <input  name="id_rev_plan" type="hidden" class="form-control" value="<?= $rev_plan->id_rev_plan ?>">

            <input type="hidden" id="crop_x" name="x"/>
            <input type="hidden" id="crop_y" name="y"/>
            <input type="hidden" id="crop_w" name="w"/>
            <input type="hidden" id="crop_h" name="h"/>

            <input id="img_url" type="hidden" name="image_url" value="<?= $source->url ?>">

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <a href="<?= Url::to(['document_rev_task/index', 'id_rev_plan' => $rev_plan->id_rev_plan])?>" class="btn btn-primary">
                    Finalizar edición
                </a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


    <div class="row margin-top-10 margin-bottom-10">
        <div class="col-md-12">
            <div class="card-deck">
                <?php
                if (count($model->docImages) > 0) {
                    foreach ($model->docImages as $docImage){
                        echo  ' <div class="card text-center" style="width: 18rem;" >
                                <img class="card-img " src="../../web/'.$docImage->url.$docImage->name.'" alt="'.$docImage->name.'" >
                <div class="card-body">
                <form action="image-delete?id='.$docImage->id_doc_image.'&id_rev_plan='.$rev_plan->id_rev_plan.'" method="post">
                       <input type="hidden" name="_csrf-backend" value="XWU9KezSScgx7EZqYH0RkepQIVfrM7imr4fY0gifwawbIX9fvoE5kXaqdg8GTETrhQJLb7pmi8zMsIm9TtONwA==">
                       <button class="btn btn-danger margin-bottom-10" type="submit">Eliminar</button>
                    </form>                </div>
            </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-default margin-bottom-30" id="back" href="#">Volver Atrás</a>
            <div id="pdf-main-container">
                <div id="pdf-loader">Loading document ...</div>
                <div id="pdf-contents">
                    <div id="pdf-meta">
                        <div id="pdf-buttons">
                            <button class="btn btn-primary" id="pdf-prev"><i class="fa fa-arrow-left"></i> Página anterior</button>
                            <button class="btn btn-primary" id="pdf-next">Página siguiente <i class="fa fa-arrow-right"></i></button>
                            <button class="btn btn-default" id="preview" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i> Vista previa del documento</button>
                            <a class="btn btn-danger" id="download-image" href="#"><i class="fa fa-scissors"></i> Recortar</a>
                        </div>
                        <div id="page-count-container">Página <!--<div id="pdf-current-page"></div>--> <input id="pdf-current-page" type="number" value="1" title="Page" style="width: 40px; text-align: right;" size="4" min="1"> de <div id="pdf-total-pages"></div></div>
                    </div>

                    <canvas id="pdf-canvas" width="950" height="auto"></canvas>
                    <div id="page-loader">Loading page ...</div>
                </div>

                <img id="preview-pdf" class="img-thumbnail preview" src="#">
            </div>
        </div>
    </div>

    <div id="myModal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2>Vista Previa del Documento Original</h2>
                </div>
                <div class="modal-body">
                    <iframe id="preview" src="../../web/js/pdfjs/web/viewer.html?file=../../../uploads/project/source/<?= $source->url?>" width="100%" height="500" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var pdf_url = '../../web/uploads/project/source/<?= $source->url?>';

    var __PDF_DOC,
        __CURRENT_PAGE,
        __TOTAL_PAGES,
        __PAGE_RENDERING_IN_PROGRESS = 0,
        __CANVAS = $('#pdf-canvas').get(0),
        __CANVAS_CTX = __CANVAS.getContext('2d');

    // Download button


    $(document).ready(function () {
        $("#back").hide();
        $("#preview-pdf").hide();
        showPDF(pdf_url);
    });


    function showPDF(pdf_url) {
        $("#pdf-loader").show();

        PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
            __PDF_DOC = pdf_doc;
            __TOTAL_PAGES = __PDF_DOC.numPages;

            // Hide the pdf loader and show pdf container in HTML
            $("#pdf-loader").hide();
            $("#pdf-contents").show();
            $("#pdf-total-pages").text(__TOTAL_PAGES);

            // Show the first page
            showPage(1);
        }).catch(function(error) {
            // If error re-show the upload button
            $("#pdf-loader").hide();
            $("#upload-button").show();

            alert(error.message);
        });
    }

    function showPage(page_no) {
        __PAGE_RENDERING_IN_PROGRESS = 1;
        __CURRENT_PAGE = page_no;

        // Disable Prev & Next buttons while page is being loaded
        $("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

        // While page is being rendered hide the canvas and show a loading message
        $("#pdf-canvas").hide();
        $("#page-loader").show();

        $("#download-image").hide();

        // Update current page in HTML
        $("#pdf-current-page").get(0).value = page_no;

        // Fetch the page
        __PDF_DOC.getPage(page_no).then(function(page) {
            // As the canvas is of a fixed width we need to set the scale of the viewport accordingly
            var scale_required = __CANVAS.width / page.getViewport(1).width;

            // Get viewport of the page at required scale
            var viewport = page.getViewport(scale_required);

            // Set canvas height
            __CANVAS.height = viewport.height;

            var renderContext = {
                canvasContext: __CANVAS_CTX,
                viewport: viewport
            };

            // Render the page contents in the canvas
            page.render(renderContext).then(function() {
                __PAGE_RENDERING_IN_PROGRESS = 0;

                // Re-enable Prev & Next buttons
                $("#pdf-next, #pdf-prev").removeAttr('disabled');

                // Show the canvas and hide the page loader
                $("#pdf-canvas").show();
                $("#page-loader").hide();
                $("#download-image").show();
            });
        });
    }

    $("#pdf-current-page").on('input',function () {
        __PAGE_RENDERING_IN_PROGRESS = 1;
        var current_page = $(this).get(0).valueAsNumber;
        var total = $("#pdf-total-pages").get(0).innerText;
        var total_pages = parseFloat(total);

        $("#pdf-current-page").attr('max', total);

        if (current_page <= total_pages) {
            showPage(current_page);
        }else {
            krajeeDialogError.alert("La página que está tratando de buscar no es correcta, por favor introduzca un valor dentro del rango de páginas del documento");
            $(this).get(0).valueAsNumber = 1;
            showPage(1);
        }

    });


    // Previous page of the PDF
    $("#pdf-prev").on('click', function() {
        if(__CURRENT_PAGE != 1)
            showPage(--__CURRENT_PAGE);
    });

    // Next page of the PDF
    $("#pdf-next").on('click', function() {
        if(__CURRENT_PAGE != __TOTAL_PAGES)
            showPage(++__CURRENT_PAGE);
    });

    // Download button
    $("#download-image").on('click', function() {
        $("#pdf-loader").hide();
        $("#pdf-contents").hide();

        $("#back").show();

        var image =  $('.jcrop-holder img');
        var pdfcanvas = $("#pdf-canvas");

        $("#preview-pdf").hide();

        $('#preview-pdf').attr('src', __CANVAS.toDataURL()).attr('download', 'page.png')
            .attr('height', pdfcanvas.height)
            .attr('width', pdfcanvas.width);

        $("#img_url").attr('value',__CANVAS.toDataURL());

        image.attr('src', __CANVAS.toDataURL()).attr('download', 'page.png')
            .attr('height', pdfcanvas.height)
            .attr('width', pdfcanvas.width);

        $('.img-thumbnail.preview').attr('src', __CANVAS.toDataURL()).attr('download', 'page.png')
            .attr('height', pdfcanvas.height)
            .attr('width', pdfcanvas.width);

        $('.jcrop-holder').show();

        $("#preview-pdf").Jcrop({
            onSelect: updateCoords
        });
    });

    function updateCoords(c)
    {
        $('#crop_x').val(c.x);
        $('#crop_y').val(c.y);
        $('#crop_w').val(c.w);
        $('#crop_h').val(c.h);
    }


    $("#back").on('click', function () {
        $('.jcrop-holder').hide();
        $("#back").hide();
        showPDF(pdf_url);
    });

</script>
