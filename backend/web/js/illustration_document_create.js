
function cambiar() {

    var button = $("button#select_all");
    var checks = $("input[type=checkbox]");
    var container = $("div.icheckbox_square-blue");

    if(button.attr("class")=="btn btn-primary btn-sm pull-right"){
        for (var i = 0; i <  checks.length; i++){
            checks.get(i).checked = true;
        }
        checks.checked = true;
        container.attr('class', 'icheckbox_square-blue checked');
        container.attr('aria-checked', true);
        button.attr("class", "btn btn-default btn-sm pull-right");
        button.html('<i class="fa fa-square-o"></i>');
    } else {
        for (var i = 0; i <  checks.length; i++){
            checks.get(i).checked = false;
        }
        container.attr('class', 'icheckbox_square-blue');
        container.attr('aria-checked', false);
        button.attr("class", "btn btn-primary btn-sm pull-right");
        button.html('<i class="fa fa-check-square-o"></i>');

    }
}

function selectedLemma() {

    var checks = $("input[type=checkbox]");
    var i = 0;
    var isSelected = false;
    while (i < checks.length && !isSelected){
        if (checks.get(i).checked == true){
            isSelected = true;
        }
        i++;
    }

    if (!isSelected){
        krajeeDialogError.alert("Error. Seleccione al menos un documento.");
        $("#illustrationdocument-continue").get(0).value = "";
    } else {
        $("#illustrationdocument-continue").get(0).value = "ok";
    }

}