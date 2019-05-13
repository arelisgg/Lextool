$(document).ready(function () {
    $('.sub_model').on('click', function () {

        let id = $(this).get(0).id

        var url = '/lextool/backend/web/general_model/details?id=' + id;

        $.ajax({
            url: url,
            type: 'get',
            success: function (data) {
                $("#details-section").fadeIn(500);
                $("#details").html(data);
            }
        });
    })
})