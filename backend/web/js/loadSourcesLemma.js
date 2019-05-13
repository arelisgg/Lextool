function loadSourcesLemma(url) {
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            $('#myModal').modal('show').find('#modalContent').html(data);
        },
        fail: function () {
            alert("error")
        }
    });
}

