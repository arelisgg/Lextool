$(document).ready(function () {
    var url = $("#url").html();
    var id = $("#id_project").html();
    var name = $("#name_project").html();
    if (id != undefined){

        $.ajax({
            url: url+'/site/control',
            type: 'Get',
            data: {id_project:id},
            success:function(data){
                var access = data.split(',');
                if (access[0] == 1){
                    $(".admin").removeClass("hidden");
                    $(".lemma-menu").removeClass("hidden");
                    $(".document-menu").removeClass("hidden");
                    $(".redaction-menu").removeClass("hidden");
                    $(".illustration-menu").removeClass("hidden");
                }
                if (access[1] == 1){
                    $(".ext-lemma").removeClass("hidden");
                    $(".lemma-menu").removeClass("hidden");
                }
                if (access[2] == 1){
                    $(".rev-lemma").removeClass("hidden");
                    $(".lemma-menu").removeClass("hidden");
                }
                if (access[3] == 1){
                    $(".ext-doc").removeClass("hidden");
                    $(".document-menu").removeClass("hidden");
                }
                if (access[4] == 1){
                    $(".rev-doc").removeClass("hidden");
                    $(".document-menu").removeClass("hidden");
                }
                if (access[5] == 1){
                    $(".doc-make").removeClass("hidden");
                    $(".document-menu").removeClass("hidden");
                }
                if (access[6] == 1){
                    $(".redaction").removeClass("hidden");
                    $(".redaction-menu").removeClass("hidden");
                }
                if (access[7] == 1){
                    $(".revition").removeClass("hidden");
                    $(".redaction-menu").removeClass("hidden");
                }
                if (access[8] == 1){
                    $(".illustration").removeClass("hidden");
                    $(".illustration-menu").removeClass("hidden");
                }
                if (access[9] == 1){
                    $(".illustration-rev").removeClass("hidden");
                    $(".illustration-menu").removeClass("hidden");
                }
            },
            fail: function(){alert("error")}
        });

        $(".property").removeClass("hidden");

        $("#detail").attr('href', url+"/project/detail?id="+id);
        $("#team").attr('href', url+"/user_project/index?id_project="+id);
        $("#source").attr('href', url+"/source/index?id_project="+id);

        //Modelo Lexicográfico
        $("#separator").attr('href', url+"/separator/index?id_project="+id);
        $("#element").attr('href', url+"/element/index?id_project="+id);
        $("#sub_model").attr('href', url+"/sub_model/index?id_project="+id);
        $("#general_model").attr('href', url+"/templates/index?id_project="+id);

        //Lemas
        $("#lemma_ext_plan").attr('href', url+"/lemma_ext_plan/index?id_project="+id);
        $("#lemma_ext_task").attr('href', url+"/lemma_ext_task/plans?id_project="+id);
        $("#lemma_rev_plan").attr('href', url+"/lemma_rev_plan/index?id_project="+id);
        $("#lemma_rev_task").attr('href', url+"/lemma_rev_task/plans?id_project="+id);
        $("#lemario").attr('href', url+"/lemma/index?id_project="+id);


        //Documentos
        $("#doc_ext_plan").attr('href', url+"/doc_ext_plan/index?id_project="+id);
        $("#doc_rev_plan").attr('href', url+"/doc_rev_plan/index?id_project="+id);
        $("#document_ext_task").attr('href', url+"/document_ext_task/plans?id_project="+id);
        $("#document_rev_task").attr('href', url+"/document_rev_task/plans?id_project="+id);
        $("#doc_make_plan").attr('href', url+"/doc_make_plan/index?id_project="+id);
        $("#document_make").attr('href', url+"/document_make/plans?id_project="+id);
        $("#doc_accept").attr('href', url+"/complementary_doc/index?id_project="+id);

        //Redacción
        $("#art_red_plan").attr('href', url+"/redaction_plan/index?id_project="+id);
        $("#art_red_task").attr('href', url+"/art_red_task/plans?id_project="+id);
        $("#art_rev_plan").attr('href', url+"/revision_plan/index?id_project="+id);
        $("#art_rev_task").attr('href', url+"/art_rev_task/plans?id_project="+id);
        $("#lemma_finish").attr('href', url+"/lemma_finish/index?id_project="+id);

        //Ilustración
        $("#illustration_plan").attr('href', url+"/illustration_plan/index?id_project="+id);
        $("#illustration_task").attr('href', url+"/illustration/plans?id_project="+id);
        $("#illustration_rev_plan").attr('href', url+"/illustration_rev_plan/index?id_project="+id);
        $("#illustration_rev").attr('href', url+"/illustration/revplans?id_project="+id);

    }
    if (name != undefined){
        $("#name").html(name);
    }
    $('a.property').click(function () {
        if ($(this).attr('href') == '#'){
            krajeeDialogError.alert("Seleccione un proyecto lexicográfico.");
        }
    });
});

function agregar(){
    $.ajax({
        url: 'create',
        type: 'Get',
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarCrear($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function ver(id){
    $.ajax({
        url: 'view',
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarVer($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function editar(id){
    $.ajax({
        url: 'update',
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarEditar($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function habilitar(id){
    $.ajax({
        url: 'habilitar',
        type: 'Get',
        dataType: 'text',
        data: {id:id},
        success:function(data){
            let result = data.split(',');
            if(result[0] === "Activo"){
                $('#state_'+id).html('<span class="glyphicon glyphicon-ok"></span>');

                if (result[1] == 0)
                    krajeeDialogWarning.alert("El usuario ha sido habilitado pero ha ocurrido un error. No se pudo enviar el correo de confirmación.");
                else
                    krajeeDialogSuccess.alert("El usuario ha sido habilitado.");
                $.pjax.reload({container: '#user-pjax'});

            } else if (result[0] === "Guest"){
                krajeeDialogWarning.alert("No se ha podido deshabilitar, el usuario está autenticado.");
            } else if (result[0] === "Inactivo"){
                $('#state_'+id).html('<span class="glyphicon glyphicon-remove"></span>');
                if (result[1] == 0)
                    krajeeDialogWarning.alert("El usuario ha sido deshabilitado pero ha ocurrido un error. No se pudo enviar el correo de confirmación.");
                else
                    krajeeDialogWarning.alert("El usuario ha sido deshabilitado.");
                $.pjax.reload({container: '#user-pjax'});
            }
        },
        fail: function(){
            krajeeDialogError.alert("Ha ocurrido un error.");
        }
    });
}

function verUsuarioRol(item_name, username){
    $.ajax({
        url: 'view',
        type: 'Get',
        data: {item_name:item_name,user_id:username},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarVer($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function editarUsuarioRol(item_name, username){
    $.ajax({
        url: 'update',
        type: 'Get',
        data: {item_name:item_name,user_id:username},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarEditar($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function eliminar(id, type, pjax){
    krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
        if (result) {
            $.ajax({
                url: 'delete?id='+id,
                type: 'POST',
                success:function(data){
                    if (data == "Error"){
                        krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                    } else {
                        $.pjax.reload({container: '#'+pjax});
                        $(document).find('#modal').modal('hide');
                        krajeeDialogSuccess.alert('El '+type+' "'+data+'" ha sido eliminado.');
                    }
                },
                fail: function(){
                    krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                }
            });
        }
    });
}

function actionIndex(id,url) {
    $.ajax({
        url: url,
        type: 'Get',
        data: {id_project:id},
        success:function(data){
            $('#content').html(data);
        },
        fail: function(){alert("error")}
    });
}

function actionCreate(id,url) {
    $.ajax({
        url: url,
        type: 'Get',
        data: {id_project:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarCrear($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function actionView(id, url) {
    $.ajax({
        url: url,
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarVer($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function actionUpdate(id, url) {
    $.ajax({
        url: url,
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#illustration').modal('hide');
            $('#modal').modal('show').find('#modalContent').html(data);
            nombrarEditar($('.modelo').attr('id'));
        },
        fail: function(){alert("error")}
    });
}

function nombrarCrear(i){
    switch (i)
    {
        case '1':
            $('.modelo').text('Crear usuario');
            break;
        case '2':
            $('.modelo').text('Agregar usuario-rol');
            break;
        case '3':
            $('.modelo').text('Crear nomenclador de diccionario');
            break;
        case '4':
            $('.modelo').text('Crear nomenclador de documento complementario');
            break;
        case '5':
            $('.modelo').text('Agregar fuente');
            break;
        case '6':
            $('.modelo').text('Agregar tarea al plan de extracción de lemas');
            break;
        case '7':
            $('.modelo').text('Agregar tarea al plan de revisión de lemas');
            break;
        case '8':
            $('.modelo').text('Agregar tarea al plan de extracción de documentos');
            break;
        case '9':
            $('.modelo').text('Agregar tarea al plan de revisión de documentos');
            break;
        case '10':
            $('.modelo').text('Crear campo semántico');
            break;
        case '11':
            $('.modelo').text('Crear nomenclador de elemento lexicográfico');
            break;
        case '12':
            $('.modelo').text('Crear nomenclador de subelemento lexicográfico');
            break;
        case '13':
            $('.modelo').text('Crear nomenclador de componente lexicográfico');
            break;
        case '14':
            $('.modelo').text('Crear separador');
            break;
        case '15':
            $('.modelo').text('Crear elemento lexicográfico');
            break;
        case '16':
            $('.modelo').text('Agregar tarea al plan de redacción');
            break;
        case '17':
            $('.modelo').text('Agregar tarea al plan de revisión');
            break;
        case '18':
            $('.modelo').text('Agregar enlace de diccionario');
            break;
        case '19':
            $('.modelo').text('Agregar tarea al plan de ilustración');
            break;
        case '20':
            $('.modelo').text('Agregar tarea al plan de revisión de ilustración');
            break;
        case '21':
            $('.modelo').text('Crear criterio de revisión');
            break;
        case '22':
            $('.modelo').text('Agregar tarea al plan de confección de documentos');
            break;
        case '23':
            $('.modelo').text('Crear Tipo de plantilla');

    }
}

function nombrarVer(i){
    switch (i)
    {
        case '1':
            $('.modelo').text('Ver usuario');
            break;
        case '2':
            $('.modelo').text('Ver usuario-rol');
            break;
        case '3':
            $('.modelo').text('Ver nomenclador de diccionario');
            break;
        case '4':
            $('.modelo').text('Ver nomenclador de documento complementario');
            break;
        case '5':
            $('.modelo').text('Ver fuente');
            break;
        case '6':
            $('.modelo').text('Ver tarea del plan de extracción de lemas');
            break;
        case '7':
            $('.modelo').text('Ver tarea del plan de revisión de lemas');
            break;
        case '8':
            $('.modelo').text('Ver tarea del plan de extracción de documentos');
            break;
        case '9':
            $('.modelo').text('Ver tarea del plan de revisión de documentos');
            break;
        case '10':
            $('.modelo').text('Ver campo semántico');
            break;
        case '11':
            $('.modelo').text('Ver nomenclador de elemento lexicográfico');
            break;
        case '12':
            $('.modelo').text('Ver nomenclador de subelemento lexicográfico');
            break;
        case '13':
            $('.modelo').text('Ver nomenclador de componente lexicográfico');
            break;
        case '14':
            $('.modelo').text('Ver separador');
            break;
        case '15':
            $('.modelo').text('Ver elemento lexicográfico');
            break;
        case '16':
            $('.modelo').text('Ver tarea del plan de redacción');
            break;
        case '17':
            $('.modelo').text('Ver tarea del plan de revisión');
            break;
        case '18':
            $('.modelo').text('Ver enlace de diccionario');
            break;
        case '19':
            $('.modelo').text('Ver tarea del plan de ilustración');
            break;
        case '20':
            $('.modelo').text('Ver tarea al plan de revisión de ilustración');
            break;
        case '21':
            $('.modelo').text('Ver criterio de revisión');
            break;
        case '22':
            $('.modelo').text('Ver tarea del plan de confección de documentos');
            break;
        case '23':
            $('.modelo').text('Ver Tipo de plantilla');
    }
}

function nombrarEditar(i){
    switch (i)
    {
        case '1':
            $('.modelo').text('Editar usuario');
            break;
        case '2':
            $('.modelo').text('Editar usuario-rol');
            break
        case '3':
            $('.modelo').text('Editar nomenclador de diccionario');
            break;
        case '4':
            $('.modelo').text('Editar nomenclador de documento complementario');
            break;
        case '5':
            $('.modelo').text('Editar fuente');
            break;
        case '6':
            $('.modelo').text('Editar tarea del plan de extracción de lemas');
            break;
        case '7':
            $('.modelo').text('Editar tarea del plan de revisión de lemas');
            break;
        case '8':
            $('.modelo').text('Editar tarea del plan de extracción de documentos');
            break;
        case '9':
            $('.modelo').text('Editar tarea del plan de revisión de documentos');
            break;
        case '10':
            $('.modelo').text('Editar campo semántico');
            break;
        case '11':
            $('.modelo').text('Editar nomenclador de elemento lexicográfico');
            break;
        case '12':
            $('.modelo').text('Editar nomenclador de subelemento lexicográfico');
            break;
        case '13':
            $('.modelo').text('Editar nomenclador de componente lexicográfico');
            break;
        case '14':
            $('.modelo').text('Editar separador');
            break;
        case '15':
            $('.modelo').text('Editar elemento lexicográfico');
            break;
        case '16':
            $('.modelo').text('Editar tarea del plan de redacción');
            break;
        case '17':
            $('.modelo').text('Editar tarea del plan de revisión');
            break;
        case '18':
            $('.modelo').text('Editar enlace de diccionario');
            break;
        case '19':
            $('.modelo').text('Editar tarea del plan de ilustración');
            break;
        case '20':
            $('.modelo').text('Editar tarea al plan de revisión de ilustración');
            break;
        case '21':
            $('.modelo').text('Editar criterio de revisión');
            break;
        case '22':
            $('.modelo').text('Editar tarea del plan de confección de documentos');
            break;
        case '23':
            $('.modelo').text('Editar Tipo de plantilla');
    }
}

function deleteForm(node){

    krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {

        if (result) {
            $(node[0].previousSibling.previousSibling).click()
        }
    });

}

function about_developers(){

    $('#about_developers').modal('show');

}
