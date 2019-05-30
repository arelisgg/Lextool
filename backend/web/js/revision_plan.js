


function tipo(){
    var type = $('#revisionplan-type');
    var edition = $('#revisionplan-edition');
    var criteria = $('#revisionplan-criterias');
    var submodel = $('#revisionplan-submodel');

    if (type.val() === 'Normal'){
        edition.get(0).disabled = false;
        // criteria.get(0).disabled = true;
        // submodel.get(0).disabled = true;
        openEdition();
        // clearCriteria();
        // clearSubmodel();
        edicion();
    }
    else if (type.val() === 'Léxica'){
        edition.get(0).disabled = true;
        criteria.get(0).disabled = true;
        submodel.get(0).disabled = false;
        clearEdition();
        clearCriteria();
        openSubmodel();
    } else {
        edition.get(0).disabled = true;
        criteria.get(0).disabled = true;
        submodel.get(0).disabled = true;
        clearEdition();
        clearCriteria();
        clearSubmodel();

    }
}



function edicion(){
    var edition = $('#revisionplan-edition');
    var criteria = $('#revisionplan-criterias');
    var submodel = $('#revisionplan-submodel');

    if(edition.val() === "0"){
        criteria.get(0).disabled = false;
        submodel.get(0).disabled = false;
        openCriteria();
        openSubmodel();
    } else {
        criteria.get(0).disabled = true;
        submodel.get(0).disabled = true;
        clearCriteria();
        clearSubmodel();
    }
}

function clearEdition() {
    var edition = $('#revisionplan-edition');

    edition.parent().find('.help-block').html('');
    edition.parent().find('#select2-revisionplan-edition-container').html('<span class="select2-selection__placeholder">Seleccione ...</span>');
    edition.parent().removeClass('has-error');
    edition.parent().removeClass('has-success');
}
function openEdition(){
    jQuery.when(jQuery('#revisionplan-edition').select2({"allowClear":true,"theme":"krajee","width":"100%","placeholder":"Seleccione...","language":"es"}));
}

function clearCriteria() {
    var criteria = $('#revisionplan-criterias');

    criteria.parent().find('.select2-selection__clear').click();
    criteria.parent().find('.select2-selection__rendered').html('<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="Seleccione..." style="width: 568px;"></li>');
    criteria.parent().removeClass('has-error');
    criteria.parent().removeClass('has-success');
    criteria.parent().find('.help-block').html('');
}
function openCriteria(){
    jQuery.when(jQuery('#revisionplan-criterias').select2({"allowClear":true,"theme":"krajee","width":"100%","placeholder":"Seleccione...","language":"es"}));
}

function clearSubmodel() {
    var submodel = $('#revisionplan-submodel');

    submodel.parent().find('.select2-selection__clear').click();
    submodel.parent().find('.select2-selection__rendered').html('<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="Seleccione..." disabled="" style="width: 100px;"></li>');
    submodel.parent().removeClass('has-error');
    submodel.parent().removeClass('has-success');
    submodel.parent().find('.help-block').html('');
}
function openSubmodel(){
     jQuery.when(jQuery('#revisionplan-submodel').select2({"allowClear":true,"theme":"krajee","width":"100%","placeholder":"Seleccione...","language":"es"}));
}