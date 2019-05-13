

<select multiple class="form-control" style="height: 465px">
    <?php
    foreach ($lemmas as $lemma){
        echo '<option id="'.$lemma->id_lemma.'" onclick="options('.$lemma->id_lemma.')">'.$lemma->extracted_lemma.'</option>';
    }
    ?>
</select>
