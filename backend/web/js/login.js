jQuery(document).ready(function() {
    $(".main-header").remove();
    $(".main-sidebar").remove();
    $(".main-footer").html('');
    $(".main-footer").attr("style","background-color: #d2d6de");
    $("#page-container").removeClass("content-wrapper");
    /*$("html").attr("style","");
    $("body").attr("style","");
    $("div.wrapper").attr("style","");*/
    $(".wrapper").attr('style', "background: #d2d6de")
});