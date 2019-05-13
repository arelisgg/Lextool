$(document).ready(function () {
    $('#substructure_group').hide();

    $('#microestructura').on('click', function () {
        $('#substructure_group').show(500);
    });
    $('#macroestructura').on('click', function () {
        $('#substructure_group').hide(500);
    });
});