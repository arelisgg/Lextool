function lemma_image(id){
    $.ajax({
        url: 'lemma_image',
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
            lightGallery(document.getElementById('lightgallery'));
        },
        fail: function(){alert("error")}
    });
}

function revision_lexica(id){
    $.ajax({
        url: 'revision_lexica',
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
        },
        fail: function(){alert("error")}
    });
}

function revision_no_edition(id){
    $.ajax({
        url: 'revision_no_edition',
        type: 'Get',
        data: {id:id},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
        },
        fail: function(){alert("error")}
    });
}

function finished(id){
    $.ajax({
        url: 'finished',
        type: 'Get',
        data: {id:id},
        success:function(data){

        },
        fail: function(){alert("error")}
    });
}

function closeModal() {
    $('#modal').modal('hide');
}
