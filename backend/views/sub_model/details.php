<li class="detail">
    <span class="detail-property">Tipo de Elemento: <span style="text-transform: capitalize;" class="detail-description"><?= $element->elementType->name ?></span></span>
</li>
<li> <span class="detail-property">Propiedad: <span style="text-transform: capitalize;" class="detail-description"><?= $element->property ?></span></span></li>
<?php
  if ($element->visibility){
      echo '<li> <span class="detail-property">Visibilidad: <span class="detail-description">Sí</span></span></li>';
  }else {
      echo '<li> <span class="detail-property">Visibilidad: <span class="detail-description">No</span></span></li>';
  }
?>

<li style="font-family: <?= $element->font ?>"> <span class="detail-property">Tipo de fuente: <span  class="detail-description"><?= $element->font ?></span></span></li>
<li style="font-size: <?= $element->font_size ?>"> <span class="detail-property">Tamaño de fuente: <span  class="detail-description"><?= $element->font_size?></span></span></li>
<li style="font-weight: <?= $element->font_weight?>"> <span class="detail-property">Ancho de fuente: <span  class="detail-description"><?= $element->font_weight?></span></span></li>
<li style="font-style: <?= $element->font_style?>"> <span class="detail-property">Estilo de fuente: <span  class="detail-description"><?= $element->font_style?></span></li>
<li style="text-transform: <?= $element->text_decoration ?>"> <span class="detail-property">Decoración de fuente: <span  class="detail-description"><?= $element->text_decoration ?></span></span></li>
<li style="color: <?= $element->color ?> !important;"> <span class="detail-property">Color de fuente: <span  class="detail-description"><?= $element->color ?></span></span></li>
<li style="background: <?= $element->background?>"> <span  class="detail-property">Color fondo de fuente: <span class="detail-description"><?= $element->background?></span></span></li>