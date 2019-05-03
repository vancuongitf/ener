var isLoadingComment = false;
var isCommenting = false;
var maxId = -1;
var minId = -1;
var user = null;
var nextPageFlag = false;
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
    }, 5000);
    $('#loading-view').hide();
    $('#btn-commenting').hide();
});
function renderCommentView(comment) {
    var html = '';
    html = html.concat('<div class="d-flex" style="width: 100%; padding: 10px;">', '<img id="user-avatar" src="', comment.user.image, '" style="width: 50px; height: 50px; margin: 0px !important; margin-right: 20px !important;">', '<div style="width: 100%; border-bottom: 1px solid #EEEEEE;">');
    html = html.concat('<b>', comment.user.name ,'</b>');
    html = html.concat('<p class="secondary-text" style="margin: 5px 0px 0px 0px;">', comment.created_at,'</p>');
    html = html.concat('<p class="main-text" style="margin: 5px 0px 0px 0px;">', comment.content, '</p></div></div>');
    return html;   
}

function addPostToComment(content) {
    if (isCommenting) {
        return;
    }
    isCommenting = true;
    $('#btn-comment').hide();
    $('#btn-commenting').show();
    var dataJson = '{ '.concat('"post_id": "', postId, '", "user_google_id": "', 2, '", "content": "', content, '", "max_id": "', maxId, '"}');
    jQuery.ajax({
        type: 'POST',
        url: '/api/post/'.concat(postId).concat('/comments'),
        crossDomain: true,
        xhrFields: { 
            withCredentials: true
        },
        data: dataJson,
        success: function(msg) {
            $('#comment').val('');
            var response = JSON.parse(msg);
            maxId = response.max_id;
            var addedCommentView = '';
            response.comments.forEach(comment => {
                addedCommentView = addedCommentView.concat(renderCommentView(comment));
            });
            addedCommentView = addedCommentView.concat($('#comment-zone').html());
            $('#comment-zone').html(addedCommentView);
            $('#btn-comment').show();
            $('#btn-commenting').hide();
            isCommenting = false;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.alert({
                title: 'Thông báo!',
                content: 'Xãy ra lỗi! Vui lòng thử lại sau!',
            });
            $('#btn-comment').show();
            $('#btn-commenting').hide();
            isCommenting = false;
        }
    });
}

function viewMoreComments() {
    if (isLoadingComment) {
        return;
    }
    isLoadingComment = true;
    $('#loading-view').show();
    $('#view-more-commtent').hide();
    jQuery.ajax({
        type: 'GET',
        url: '/api/post/'.concat(postId, '/comments/', minId),
        crossDomain: true,
        xhrFields: { 
            withCredentials: true
        },
        success: function( msg ) {
            var response = JSON.parse(msg);
            nextPageFlag = response.next_page_flag;
            minId = response.min_id;
            isLoadingComment = false;
            var addedCommentView = '';
            response.comments.forEach(comment => {
                addedCommentView = addedCommentView.concat(renderCommentView(comment));
            });
            $('#comment-zone').append(addedCommentView);
            $('#loading-view').hide();
            if (nextPageFlag) {
                $('#view-more-commtent').show();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            isLoadingComment = false;
            $('#loading-view').hide();
            if (nextPageFlag) {
                $('#view-more-commtent').show();
            }
        }
    });
}
