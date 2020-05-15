$(document).ready(function() {

    //При клике ссылку на регистрацию, убрать форму входа и показать форму регистрации
    $("#signup").click(function() {
        $("#first").slideUp("slow", function(){
            $("#second").slideDown("slow"); 
        });
    });

    //При клике ссылку на входа, убрать форму регистрации и показать форму входа
    $("#signin").click(function() {
        $("#second").slideUp("slow", function(){
            $("#first").slideDown("slow"); 
        });
    });


});