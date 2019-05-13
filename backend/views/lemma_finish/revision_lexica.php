<?php
/* @var $review_lexicals backend\models\ReviewLexical[] */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-eraser"></i> Revisión léxica</h2>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <?php
                    foreach ($review_lexicals as $review_lexical){
                        echo
                            '<li class="list-group-item">
                                <p class="list-group-item-text"><strong>Palabra:</strong> '.$review_lexical->word.' </p>
                            </li>
                            <li class="list-group-item">
                                <p class="list-group-item-text"><strong>Observación:</strong> '.$review_lexical->comments.' </p>
                            </li>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>
