function loadSourceDocument(url) {

    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            console.log("hello");
            $('#myModal').modal('show').find('#modalContent').html(data);
        },
        fail: function () {
            alert("error")
        }
    });
}