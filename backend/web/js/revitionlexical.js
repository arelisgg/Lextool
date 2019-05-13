$(document).ready(function () {
    $(".review_wrapper").on("afterInsert", function(e, item) {

        var selector = $(item).find("select");
        $.each(selector,function(){
            $(this).val('').trigger("change");
        });

    });

    var div = document.getElementById('lex-article');
    div.addEventListener('mouseup', function(){
        var thetext = getSelectionText();
        if (thetext.length > 0){ // check there's some text selected
            actionSearch($('#id_project').html(), thetext);
        }
    }, false);



});

function addComment() {
    var word = $('#search-lemma').val();
    $('.add-item').click();
    var words = $('.word');
    words.get(words.length-1).value = word;
    $('#modal').modal('hide');
}

function getSelectionText(){
    var selectedText = "";
    if (window.getSelection){ // all modern browsers and IE9+
        selectedText = window.getSelection().toString();
    }
    return selectedText;
}

function actionSearch(id_project, word) {
    $.ajax({
        url: 'search',
        type: 'Get',
        data: {id_project:id_project, word:word},
        success:function(data){
            $('#modal').modal('show').find('#modalContent').html(data);
        },
        fail: function(){alert("error")}
    });
}

function actionReSearch() {
    var id_project = $('#search-id_project').val();
    var word = $('#search-lemma').val();
    $.ajax({
        url: 'search',
        type: 'Get',
        data: {id_project:id_project, word:word},
        success:function(data){
            $('#modalContent').html(data);
        },
        fail: function(){alert("error")}
    });
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