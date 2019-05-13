function actionDelete(id, url){
    krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
        if (result) {
            $.ajax({
                url: url+'?id='+id,
                type: 'POST',
                success:function(data){
                    if (data == "Error"){
                        krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                    } else if (data == "Used"){
                        krajeeDialogError.alert("No se ha podido eliminar el elemento lexicográfico, está siendo usado en algún componente lexicográfico.");
                    } else {
                        $.pjax.reload({container: '#element-pjax'});
                        krajeeDialogSuccess.alert('El elemento lexicográfico ha sido eliminado.');
                    }
                },
                fail: function(){
                    krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                }
            });
        }
    });
}