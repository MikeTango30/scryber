let audioPlayer = $('#player');
let editor = $('#editor');

(function() {
    audioPlayer.bind("timeupdate", function (e) {
        editor.find('span').each(function () {
            if (audioPlayer[0].currentTime >= $(this).attr('data-word-start')
                && audioPlayer[0].currentTime <= $(this).attr('data-word-end'))
                $(this).addClass('sync');
            else
                $(this).removeClass('sync')
        });
    });
}());

(function() {
    editor.children().bind('click', function (e) {
    audioPlayer[0].currentTime = $(this).attr('data-word-start');
    });
}());
