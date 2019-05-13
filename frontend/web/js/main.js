

function buscar() {
    $('button').click();
}

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

function about_developers(){

    $('#about_developers').modal('show');

}