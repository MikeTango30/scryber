var userfileId = window.location.href.split('/').pop();

$(document).ready(function f(){
    setTimeout(function () {
        $.ajax({
            url: '/check_status/'+ userfileId,
            data: job_status,
            success: function(data) {
                if (data === '1') {
                    $.ajax({url: '/refresh_status/' + userfileId});
                } else {
                    f();
                }
            }
        })
    }, 5000);
});

