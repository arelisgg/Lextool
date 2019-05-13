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
                        let alerta = "<p>No se ha podido eliminar el proyecto, tiene asociado:</p><ul>" +
                            "<li>1 - Fuentes</li>" +
                            "<li>2 - Planes de rextracción de lemas</li>" +
                            "<li>3 - Planes de revisión de lemas extraidos</li>" +
                            "<li>4 - Planes de extracción de documentos complementarios</li>" +
                            "<li>5 - Planes de revisión de documentos complementarios extraidos</li>" +
                            "<li>6 - Planes de confección de documentos</li>" +
                            "<li>7 - Planes de redaccion de lemas</li>" +
                            "<li>8 - Planes de revisión de lemas redactados</li>" +
                            "<li>9 - Planes de asociación de ilustración</li>" +
                            "<li>10 - Planes de revisión de ilustraciones asociadas</li>" +
                            "<li>11 - Lemas</li>" +
                            "<li>12 - Documentos complementarios</li>" +
                            "</ul>";
                        krajeeDialogError.alert(alerta);
                    } else {
                        $.pjax.reload({container: '#project-pjax'});
                        krajeeDialogSuccess.alert('El proyecto ha sido eliminado.');
                    }
                },
                fail: function(){
                    krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                }
            });
        }
    });
}