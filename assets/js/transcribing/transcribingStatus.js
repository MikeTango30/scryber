var userfileId = window.location.href.split('/').pop();

$(document).ready(function () {
    (function worker()
    {
        $.ajax({
            url: '/check_status/'+ userfileId,
            success: function (data) {
                $("#statusMessage").text(data.message);
                if (data.redirecting === true) {
                    $(location).attr('href', '/show_results/'+userfileId);
                }
            },
            complete: function () {
                // Schedule the next request when the current one's complete
                setTimeout(worker, 5000);
            }
        });
    })();
});

