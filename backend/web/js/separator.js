function openPaleta() {
    //$('#paleta').modal('show').find('#paletaContent').html('<h2>asd</h2>');
    $('#paleta').modal('show');
}

function closePaleta() {
    $('#paleta').modal('hide');
}

function selectSymbol(symbol) {
    $(".select").removeClass("select");
    symbol.addClass("select");
}

function insertSymbol() {
    var symbol = $(".select").get(0).textContent;
    $('#separator-representation').get(0).value = symbol;
    $('#paleta').modal('hide');

}