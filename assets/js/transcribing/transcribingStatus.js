var userfileId = window.location.href.split('/').pop();

$(document).ready(function f(){
    setTimeout(function () {
        $.ajax({
            url: '/refresh_status/'+ userfileId,
            success: function() {
                f();
            }
        })
    }, 5000);
});

