$(document).ready(function () {
    $('.list-clicked:first-child').addClass('active');

    $('.tab-clicked').on('click', function () {

        var id_letter = $(this).attr('href').substring(1);

        var id_project = $("#id_project").html();

        $.ajax({
            url: 'lemas',
            type: 'Get',
            data: {id_project: id_project, id_letter:id_letter},
            success:function(data){
                $('#lemas').html(data);
            },
            fail: function(){
                alert("error")
            }
        });

    });
});

function options(id) {
    $.ajax({
        url: 'options',
        type: 'Get',
        data: {id: id},
        success:function(data){
            $('#options').html(data);
        },
        fail: function(){
            alert("error")
        }
    });
}