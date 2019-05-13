
<div class="box box-primary">
    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-language"></i> Lemas</h2>
        <button id="select_all" type="button" class="btn btn-primary btn-sm pull-right" onclick="cambiar()">
            <i class="fa fa-check-square-o"></i>
        </button>
    </div>
    <div class="box-body" style="height: 465px; padding-left: 10px; overflow-y: auto; padding-bottom: 20px;">

        <?php
        for ($i = 0; $i < count($lemmas); $i++){
            echo '<div class="checkbox icheck">
                                                  <label class="margin-right-10"><input name="lemmas['.$i.']" type="checkbox">'.$lemmas[$i]->extracted_lemma.'</label>
                                              </div>';
        }

        ?>


    </div>
</div>