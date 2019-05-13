$(document).ready(function () {
    $('.list-clicked:first-child').addClass('active');

    $('.tab-clicked').on('click', function () {

        var id_letter = $(this).attr('href').substring(1);

        var id_project = $("#id_project").html();

        $('#illustrationlemma-id_letter').get(0).value = id_letter;

        $.ajax({
            url: 'lemmas',
            type: 'Get',
            data: {id_project: id_project, id_letter:id_letter},
            success:function(data){
                $('#lemmas').html(data);
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            },
            fail: function(){
                alert("error")
            }
        });

    });

});
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
        krajeeDialogError.alert("Error. Seleccione al menos un lema.");
        $("#illustrationlemma-continue").get(0).value = "";
    } else {
        $("#illustrationlemma-continue").get(0).value = "ok";
    }

}