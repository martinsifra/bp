$(function () {
    $.nette.init();
    
    
    $('input.button.cancel').click(function(e){
        window.history.go(-1);
    });

});