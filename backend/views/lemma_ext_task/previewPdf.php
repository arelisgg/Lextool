<div id="pdf">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cerrar</button>
        </div>
        <div class="col-md-6">
            <button type="submit" id="extractLemma" class="btn btn-primary btn-block">Continuar</button>
        </div>
    </div>

    <div class="col-md-12">
        <input type="hidden" name="id_source" value="<?= $source->id_source?>"
    </div>

    <div class="row padding-tb-20">
        <iframe id="preview" src="../../web/js/pdfjs/web/viewer.html?file=../../../uploads/project/source/<?= $source->url?>" width="100%" height="500" allowfullscreen></iframe>
    </div>
</div>
