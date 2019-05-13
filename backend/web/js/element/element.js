$(document).ready(function () {

    $(".sub_element_wrapper").on('afterInsert',function () {
        var inputs = $(".pick-a-color");

        for (var i = 0; i < inputs.length; i++){
            if(inputs.get(i).nextElementSibling.className =="help-block"){
                if (inputs.get(i).className == "pick-a-color form-control color")
                    $("#"+inputs.get(i).id).get(0).value = "000000";
                else
                    $("#"+inputs.get(i).id).get(0).value = "ffffff";

                $("#"+inputs.get(i).id).pickAColor({
                    showSpectrum            : true,
                    showSavedColors         : true,
                    saveColorsPerElement    : true,
                    fadeMenuToggle          : true,
                    showAdvanced			: true,
                    showBasicColors         : true,
                    showHexInput            : true,
                    allowBlank				: true,
                    inlineDropdown			: true
                });

            }
        }
    });

    var font_weight = $('.font_weight');

    for (var i = 0; i < font_weight.length; i++){
        if(font_weight.get(i).value == "bold"){
            font_weight.get(i).nextElementSibling.style = 'background-color: #f4f4f4; border-color: #ddd;';
            font_weight.get(i).nextElementSibling.className = 'btn bold';
        }
    }

    var font_style = $('.font_style');

    for (var i = 0; i < font_style.length; i++){
        if(font_style.get(i).value == "italic"){
            font_style.get(i).nextElementSibling.style = 'background-color: #f4f4f4; border-color: #ddd;';
            font_style.get(i).nextElementSibling.className = 'btn italic';
        }
    }

    var text_decoration = $('.text_decoration');

    for (var i = 0; i < text_decoration.length; i++){
        if(text_decoration.get(i).value == "underline"){
            text_decoration.get(i).nextElementSibling.style = 'background-color: #f4f4f4; border-color: #ddd;';
            text_decoration.get(i).nextElementSibling.className = 'btn underline';
        }
    }

    var text_transform = $('.text_transform');

    for (var i = 0; i < text_transform.length; i++){
        if(text_transform.get(i).value == "uppercase"){
            text_transform.get(i).nextElementSibling.style = 'background-color: #f4f4f4; border-color: #ddd;';
            text_transform.get(i).nextElementSibling.className = 'btn uppercase';
        }
    }

    $(".sub_element_wrapper").on("afterInsert", function(e, item) {

        var selector = $(item).find("select");
        $.each(selector,function(){
            $(this).val('').trigger("change");
        });

    });


});



function vaciar() {
    $("select.sub-element").html("");
}

function negrita(element) {

    if(element.attr('class') == "btn"){
        element.addClass("bold");
        element.attr('style', 'background-color: #f4f4f4; border-color: #ddd;');
        element.get(0).previousElementSibling.value="bold";
    } else {
        element.removeClass("bold");
        element.attr('style', 'background-color: #ffffff; border-color: #fff;');
        element.get(0).previousElementSibling.value="normal";
    }
}

function italic(element) {

    if(element.attr('class') == "btn"){
        element.addClass("italic");
        element.attr('style', 'background-color: #f4f4f4; border-color: #ddd;');
        element.get(0).previousElementSibling.value="italic";
    } else {
        element.removeClass("italic");
        element.attr('style', 'background-color: #ffffff; border-color: #fff;');
        element.get(0).previousElementSibling.value="normal";
    }
}

function text_decoration(element) {

    if(element.attr('class') == "btn"){
        element.addClass("underline");
        element.attr('style', 'background-color: #f4f4f4; border-color: #ddd;');
        element.get(0).previousElementSibling.value="underline";
    } else {
        element.removeClass("underline");
        element.attr('style', 'background-color: #ffffff; border-color: #fff;');
        element.get(0).previousElementSibling.value="none";
    }
}

function text_transform(element) {

    if(element.attr('class') == "btn"){
        element.addClass("uppercase");
        element.attr('style', 'background-color: #f4f4f4; border-color: #ddd;');
        element.get(0).previousElementSibling.value="uppercase";
    } else {
        element.removeClass("uppercase");
        element.attr('style', 'background-color: #ffffff; border-color: #fff;');
        element.get(0).previousElementSibling.value="none";
    }
}