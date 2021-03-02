<div id="image">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cerrar</button>
        </div>
        <div class="col-md-6">
            <button type="submit" id="extractLemma" class="btn btn-primary btn-block">Continuar</button>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="id_source" value="<?= $source->id_source?>"
        </div>
    </div>

    <div class="row padding-tb-20 text-center">
        <div class="col-md-12">
            <img height="100%" width="100%" src="../../web/uploads/project/source/<?= $source->url?>">
        </div>
    </div>
</div>

