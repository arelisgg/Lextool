jQuery(document).ready(function() {
    $('#demo1').Jcrop({
        onSelect: updateCoords
    });

    function updateCoords(c)
    {
        $('#crop_x').val(c.x);
        $('#crop_y').val(c.y);
        $('#crop_w').val(c.w);
        $('#crop_h').val(c.h);
    }

    $('#demo8_form').submit(function(){
        if (parseInt($('#crop_w').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    });

    handleResponsive();

    function handleResponsive() {
        if ($(window).width() <= 1024 && $(window).width() >= 678) {
            $('.responsive-1024').each(function(){
                $(this).attr("data-class", $(this).attr("class"));
                $(this).attr("class", 'responsive-1024 col-md-12');
            });
        } else {
            $('.responsive-1024').each(function(){
                if ($(this).attr("data-class")) {
                    $(this).attr("class", $(this).attr("data-class"));
                    $(this).removeAttr("data-class");
                }
            });
        }
    }
});
