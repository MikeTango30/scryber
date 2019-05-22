(function() {
    let audioPlayer = $('#player');
    let editor = $('#editor');

    audioPlayer.bind("timeupdate", function (e) {
        editor.find('span').each(function () {
            if (audioPlayer[0].currentTime >= $(this).attr('data-word-start')
                && audioPlayer[0].currentTime <= $(this).attr('data-word-end'))
                $(this).css('background','#DBCF96');
            else $(this).css('background','none');
        });
    });

    editor.children().bind('click', function (e) {
        audioPlayer[0].currentTime = $(this).attr('data-word-start');
    })
}());