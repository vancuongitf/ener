var isLoadingComment = false;
var isCommenting = false;
var maxId = -1;
var minId = -1;
var user = null;
var nextPageFlag = false;
var commentIds = [];
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
            }
        });
    }, 5000);
    $('#loading-view').hide();
    $('#btn-commenting').hide();
});
function renderCommentView(comment) {
    var html = '';
    html = html.concat('<div class="d-flex" style="width: 100%; padding: 10px;">', '<img id="user-avatar" src="', comment.user.image, '" style="width: 50px; height: 50px; margin: 0px !important; margin-right: 20px !important;">', '<div style="width: 100%; border-bottom: 1px solid #EEEEEE;">');
    html = html.concat('<div class="d-flex justify-content-between">');
    html = html.concat('<b>', comment.user.name ,'</b>');
    if (comment.like_count > 0) {
        html = html.concat('<b id="like-count-' , comment.id, '" style="margin: 0px; color: blue;">', comment.like_count, 'Like</b>');
    } else {
        html = html.concat('<b id="like-count-' , comment.id, '" style="margin: 0px; color: blue;"></b>');        
    }
    html = html.concat('</div>')
    html = html.concat('<p class="secondary-text" style="margin: 5px 0px 0px 0px;">', comment.created_at,'</p>');
    html = html.concat('<p class="main-text" style="margin: 5px 0px 0px 0px;">', comment.content, '</p>');
    html = html.concat('<div class="d-flex">');
    if (comment.like_flag) {
        html = html.concat('<b id="like-{{$comment->id}}" class="button blue-text-hover" onclick="likeClicked()">Bỏ Thích</b>')
    } else {
        html = html.concat('<b id="like-{{$comment->id}}" class="button main-text-hover" onclick="likeClicked()">Thích</b>')
    }
    html = html.concat('<p id="reply-{{$comment->id}}" class="button main-text-hover" onclick="">Trả lời</p>')    
    html = html.concat('</div>');
    html = html.concat('</div></div>');
    return html;   
}

function addPostToComment(content) {
    if (isCommenting) {
        return;
    }
    isCommenting = true;
    $('#btn-comment').hide();
    $('#btn-commenting').show();
    var dataJson = '{ '.concat('"post_id": "', postId, '", "user_google_id": "', user.id, '", "content": "', content, '", "max_id": "', maxId, '"}');
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
    var commentUrl = '';
    commentUrl = '/api/post/'.concat(postId, '/comments/', minId, '/user');        
    if (user != null) {
        commentUrl = commentUrl.concat('/', user.id);
    } 
    jQuery.ajax({
        type: 'GET',
        url: commentUrl,
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

function likeClicked(commentId) {
    if (user != null) {
        $.getJSON('/api/comment/'.concat(commentId, '/like/', user.id), function(data) {
                var btnLike = $('#like-'.concat(data.comment_id));
            if (data.like_flag) {
                btnLike.removeClass('main-text-hover');
                btnLike.addClass('blue-text-hover');
                btnLike.text('Bỏ Thích');
            } else {
                btnLike.removeClass('blue-text-hover');
                btnLike.add('main-text-hover');
                btnLike.text('Thích');
            }
            if (data.like_count > 0) {
                $('#like-count-'.concat(data.comment_id)).text(data.like_count.toString().concat(' Like'));
            } else {
                $('#like-count-'.concat(data.comment_id)).text('');
            }
        });
    } else {
        loginConfirm();
    }
}

function loginConfirm() {
    $.confirm({
        title: 'Thông Báo!',
        content: 'Vui lòng đăng nhập để sử dụng chức năng này!',
        buttons: {
            ok: function () {
                $('.abcRioButtonContentWrapper').first().click();
            },
            cancel: function () {
            }
        }
    });
}
