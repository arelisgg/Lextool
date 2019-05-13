$(document).ready(function () {
    $('form#submodel_form').on('beforeSubmit', function(e) {

        let childs = $("#submodel").find('input');
        let i = 0;
        let result = false;

        if(childs.get(i).name.split("-")[0] === 'separator'){
            krajeeDialogError.alert("Error. El componente no puede comenzar con separador.");
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
            krajeeDialogError.alert("Error. Dos separadores no pueden ir juntos dentro del componente.");
            return false;
        } else{
            return true;
        }
    });
});
