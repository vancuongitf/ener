var maxId = -1;
var minId = -1;
var user = null;
$(document).ready( function() {
    setTimeout(function() {
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
        jQuery.ajax({
            type: 'GET',
            url: '/api/post/'.concat(postId, '/comments/-1'),
            crossDomain: true,
            xhrFields: { 
                withCredentials: true
            },
            success: function( msg ) {
                var objJSON = JSON.parse(msg);
                console.log(msg);
            }
        });
    }, 5000);
});
function addPostToComment(content) {
    var dataJson = '{ '.concat('"post_id": "', postId, '", "user_google_id": "', user.id, '", "content": "', content, '", "max_id": "', maxId, '"}');
    jQuery.ajax({
        type: 'POST',
        url: '/api/post/'.concat(postId).concat('/comment'),
        crossDomain: true,
        xhrFields: { 
            withCredentials: true
        },
        data: dataJson,
        success: function(msg) {
            user = JSON.parse(msg);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            user = null;
        }
    });
}
