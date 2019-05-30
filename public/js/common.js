function myAlert(title, message, callBack) { 
    $.alert({
        title: title,
        content: message,
        buttons: {
            heyThere: {
                text: 'OK', // text for button
                btnClass: 'btn-blue', // class for the button
                keys: ['enter'], // keyboard event for button
                isHidden: false, // initially not hidden
                isDisabled: false, // initially not disabled
                action: function (heyThereButton) {
                    callBack.call();
                }
            }
        }
    });
}
