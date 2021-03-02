$(document).ready(function () {
    let url = $("#url").get(0).innerText
    let id = $("#id_template").get(0).innerText

    $("#delete_template").click(function () {
        krajeeDialogWarning.confirm("¿Está seguro de eliminar esta plantilla?", function (result) {
            if (result) {
                $.ajax({
                    url: url + '/templates/verify',
                    type: 'Get',
                    data: { id_template: id },
                    success: function (data) {
                        if (data.can_delete) {
                            $.ajax({
                                url: url + '/templates/delete',
                                data: { id_template: id },
                                type: 'Get',
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