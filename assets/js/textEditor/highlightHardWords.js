(function () {
    $('#highlights').change(function () {
        if (this.checked) {
            $('#editor').find('span').each(function() {
                if ($(this).attr('data-word-conf') <= 0.65) {
                    $(this).addClass('highlight');
                }
            })
        } else if (!this.checked) {
            $('.highlight').removeClass('highlight')
        }
    });
} ());