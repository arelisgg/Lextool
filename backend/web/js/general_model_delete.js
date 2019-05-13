$(document).ready(function () {
    let url = $("#url").get(0).innerText
    let id_project = $("#id_project").get(0).innerText

    $("#delete_model").click(function () {
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este modelo de artículo?", function (result) {
            if (result) {
                $.ajax({
                    url: url + '/general_model/verify',
                    type: 'Get',
                    data: { id_project: id_project },
                    success: function (data) {
                        if (data.can_delete) {
                            $.ajax({
                                url: url + '/general_model/delete',
                                data: { id_project: id_project },
                                type: 'get',
                            })

                        }else {
                            let message = document.createElement('ul')

                            let count = 1;

                            for (let key in data.error_list) {
                                let li = document.createElement('li')
                                li.innerText = count + '-' + data.error_list[key]
                                message.appendChild(li)
                                count++
                            }

                            krajeeDialogError.alert(message);
                        }
                    }
                })
            }
        })
    })
})