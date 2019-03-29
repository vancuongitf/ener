var postImage = '';

function showImageTo(input, $target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $($target).attr('src', e.target.result);
            $('#img_create_post_image').show();
            $('#btn_remove_image').show();
            $('#btn_dont_change_image').show();
            $('#image_control').val('CHANGE');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeImage() {
    $('#img_create_post_image').hide();
    var imgChoose = $('#create_post_image');
    imgChoose.replaceWith(imgChoose.val('').clone(true));
    $('#btn_remove_image').hide();
    $('#btn_dont_change_image').show();
    $('#image_control').val('REMOVE');
}
function dontChangeImage() {
    $('#img_create_post_image').show();
    $('#btn_remove_image').show();
    $('#image_control').val('NOTHING');
    $('#img_create_post_image').attr('src', postImage);
    $('#btn_dont_change_image').hide();
    var imgChoose = $('#edit_post_image');
    imgChoose.replaceWith(imgChoose.val('').clone(true));

}
function genSearchValue(alias) {
    var str = alias;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
    str = str.replace(/ + /g, " ");
    str = str.trim();
    while (str.includes("  ") > 0) {
        str = str / replace("  ", " ");
    }
    return str;
}
function submitClick() {
    $.alert({
        title: 'Publish confirm!',
        content: 'Do you want publish this post now?',
        buttons: {
            heyThere: {
                text: 'OK', // text for button
                btnClass: 'btn-blue', // class for the button
                keys: ['enter'], // keyboard event for button
                isHidden: false, // initially not hidden
                isDisabled: false, // initially not disabled
                action: function (heyThereButton) {
                    var nameSearch = genSearchValue($('#title').val());
                    var descriptionSearch = genSearchValue($('#description').val());
                    $('#name_search').val(nameSearch);
                    $('#description_search').val(descriptionSearch);
                    $('#publish_now').val('1');
                    $('#post_form').submit();
                }
            },
            somethingElse: {
                text: 'Later',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function () {
                    var nameSearch = genSearchValue($('#title').val());
                    var descriptionSearch = genSearchValue($('#description').val());
                    $('#name_search').val(nameSearch);
                    $('#description_search').val(descriptionSearch);
                    $('#publish_now').val('0');
                    $('#post_form').submit();
                }
            },
            cancel: function () {
            },
        }
    });
}
function insertVideoOnClicked() {
    $.confirm({
        title: 'Insert video!',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Enter video url here!</label>' +
            '<input type="text" placeholder="Video url" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    var url = this.$content.find('.name').val();
                    if (!url) {
                        $.alert('Please insert video url!');
                        return false;
                    }
                    var videoContent = getVideoEmbedTag(url);
                    var markupStr = $('#summernote').summernote('code');
                    $("#summernote").summernote('code', markupStr.concat(videoContent));
                }
            },
            cancel: function () {
                //close
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}

function insertImageOnClicked() {
    $.confirm({
        title: 'Insert Image!',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Insert image url here!</label>' +
            '<input type="text" placeholder="Image url" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    var url = this.$content.find('.name').val();
                    if (!url) {
                        $.alert('Please insert video url!');
                        return false;
                    }
                    var imgTag = '<img src="'.concat(url, '"><br>');
                    var markupStr = $('#summernote').summernote('code');
                    $("#summernote").summernote('code', markupStr.concat(imgTag));
                }
            },
            cancel: function () {
                //close
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}

function getVideoEmbedTag($url) {
    if ($url.match('https://www.youtube.com')) {
        var vars = [], hash;
        var hashes = $url.slice($url.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        var videoUrl = 'https://www.youtube.com/embed/'.concat(vars['v']);
        return '<div class="videoWrapper"><iframe frameborder="0" src="'.concat(videoUrl, '"></iframe></div><br>');
    } else {
        return '<div class="videoWrapper"><video controls=""><source src="'.concat($url, '" type="video/mp4"></video></div><br>');
    }
}

$(document).ready(function () {
    $('#btn_dont_change_image').hide();
    $("#viewHTML").click(function () {
        var markupStr = $('#summernote').summernote('code');
        $('#view').html(markupStr);
    }
    );
    $("#btn-insert-iamge").click(function () {
        var imageTagStart = "<div style=\"text-align:center;\"><img style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto;\" src=\"";
        var htmlContent = imageTagStart.concat($("#image-address").val()).concat("\"><i style=\"color: blue; font-size: 15px;\">").concat($("#image-description").val()).concat("</i></div><br>")
        var markupStr = $('#summernote').summernote('code');
        $("#summernote").summernote('code', markupStr.concat(htmlContent));
    });
    $("#summernote").summernote({
        placeholder: 'Input post\' content',
        tabsize: 2,
        height: 500
    });
    $('#title').keyup(function () {
        $('#route').val(genUrl($('#title').val()));
    });
    function genUrl(alias) {
        var str = alias;
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
        str = str.replace(/ + /g, " ");
        str = str.trim();
        while (str.includes("  ") > 0) {
            str = str / replace("  ", " ");
        }
        while (str.includes(" ") > 0) {
            str = str.replace(" ", "-");
        }
        return str.concat(".html");
    }
    $('.note-icon-video').parent().hide();
    // $('.note-icon-picture').parent().hide();
    // $btnImage = '<button type="button" onclick="insertImageOnClicked()" class="note-btn btn btn-light btn-sm" role="button" tabindex="-1" title="" aria-label="Picture" data-original-title="Picture"><i class="note-icon-picture"></i></button>';
    $btnVideo = '<button type="button" onclick="insertVideoOnClicked()" class="note-btn btn btn-light btn-sm" role="button" tabindex="-1" title="" aria-label="Video" data-original-title="Video"><i class="note-icon-video"></i></button>';
    // $('.note-icon-video').parent().parent().append($btnImage);
    $('.note-icon-video').parent().parent().append($btnVideo);
});