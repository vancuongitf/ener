$(document).ready( function() {
    setTimeout(function() {
        console.log("call api");
        jQuery.ajax({
            type: 'POST',
            url:  '/api/view/'.concat(postId),
            crossDomain: true,
            xhrFields: { 
            withCredentials: true
            },
            success: function( msg ) {
                var objJSON = JSON.parse(msg);
                console.log(objJSON.status);
            }
        });
    }, 5000);
});
