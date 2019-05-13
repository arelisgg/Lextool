function tipo() {
    var type = $("#illustrationplan-type").get(0).value;
    var letter = $("#illustrationplan-letter");
    var document = $("#illustrationplan-document");
    if (type == "Lema"){
        letter.get(0).disabled = false;
        document.get(0).disabled = true;
    }else if(type == "Documento Complementario"){
        letter.get(0).disabled = true;
        document.get(0).disabled = false;
    }else {
        letter.get(0).disabled = true;
        document.get(0).disabled = true;
    }
}