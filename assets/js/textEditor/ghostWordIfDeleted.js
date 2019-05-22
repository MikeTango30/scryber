(function () {
    $('#hints').change(function () {
        if (!this.checked) {
            $('.word').removeClass('word').addClass('no-word');
        } else if (this.checked)
            $('.no-word').removeClass('no-word').addClass('word');
    })
}());