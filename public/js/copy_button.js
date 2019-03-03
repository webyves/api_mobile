/****************************************
file: copy_button.js
JQuery Code for copy to clipboard
****************************************/

$(document).ready(function() {
    
    $("#button-copy-token").on( "click", function(e){
        e.preventDefault();
        $("#inputFbToken").select();
        document.execCommand("copy");
    });

});
