
$(document).ready(function() {

    /*$("#project-image").on("filepredelete", function(jqXHR) {
        var abort = true;
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                abort = false;
                return abort;
            }
        });
        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
    });*/

    $("#project-image").on("filepredelete", function(jqXHR) {
        var abort = true;
        if (confirm("¿Está seguro de eliminar este elemento?")) {
            abort = false;
        }
        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
    });

    /*$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("¿Está seguro de eliminar este elemento?")) {
            return false;
        }
        return true;
    });*/



    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit reached");
    });

    $(".field-source-0-letter").on('click', function () {
        $(".select2-dropdown select2-dropdown--below").html('<span id="s2-togall-source-0-letter" class="s2-togall-button s2-togall-select"><span class="s2-select-label"><i class="glyphicon glyphicon-unchecked"></i>Seleccionar todo</span><span class="s2-unselect-label"><i class="glyphicon glyphicon-check"></i>Deseleccionar todo</span></span>')
    });




});

