<?php
/* @var $lex_articule_reviews backend\models\LexArticleReview[] */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-eraser"></i> Revisión sin derecho de edición</h2>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <?php
                    foreach ($lex_articule_reviews as $lex_articule_review){
                        echo
                            '<li class="list-group-item">
                                <p class="list-group-item-text"><strong>Criterio de revisión:</strong> '.$lex_articule_review->reviewCriteria->criteria.' </p>
                            </li>
                            <li class="list-group-item">
                                <p class="list-group-item-text"><strong>Observación:</strong> '.$lex_articule_review->comments.' </p>
                            </li>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>
