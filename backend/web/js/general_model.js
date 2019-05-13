$(document).ready(function () {
    $('form#general_model_form').on('beforeSubmit', function(e) {

        let childs = $("#general").find('input');
        let i = 0;
        let result = false;

        if(childs.get(childs.length -1).name.split("-")[0] === 'separator' || childs.get(0).name.split("-")[0] === 'separator'){
            krajeeDialogError.alert("Error. El modelo de artículo no puede comenzar ni terminar con separador.");
            return false;
        }

        while (i < childs.length-1 && !result){
            let name1 = childs.get(i).name;
            let nameList1 = name1.split("-");

            let name2 = childs.get(i+1).name;
            let nameList2 = name2.split("-");

            if (nameList1[0] === 'separator' && nameList2[0] === 'separator')
                result = true;
            i++;
        }

        if (result){
            krajeeDialogError.alert("Error. Dos separadores no pueden ir juntos dentro del modelo de artículo.");
            return false;
        } else{
            return true;
        }
    });


    var list =  $("#general li");

    list.each(function () {
        this.className = 'addedItem';

        var span = document.createElement("span");
        var icon = document.createElement("i");
        icon.setAttribute('class','fa fa-trash js-remove');
        span.appendChild(icon);

        this.appendChild(span);
        this.style.cursor = "pointer";

        this.addEventListener('click', function () {

            var id = parseFloat(this.id);

            var url = '/lextool/backend/web/general_model/details?id='+id;

            $.ajax({
                url: url,
                type: 'get',
                success: function (data) {
                    $("#details-section").fadeIn(1000);
                    $("#details").html(data);
                }
            });
        });
    });

    //Actualizar separadores
    var i = 0;
    var inc = 1;
    var separators = $(".addedItem input[name^='separator']");
    while (i < separators.length) {
        separators[i].name = 'separator-'+inc;
        i++;
        inc++;
    }
});


