$(function(){
    $("form").submit(function(event){
        $("h1").after("<p>Aguarde, enviando o arquivo ...</p>");
        $("form input[type=submit]").prop("disabled", true);
    });    
});