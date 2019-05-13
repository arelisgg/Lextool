$(document).ready(function () {
    $(".review_wrapper").on("afterInsert", function(e, item) {

        var selector = $(item).find("select");
        $.each(selector,function(){
            $(this).val('').trigger("change");
        });

    });
});
