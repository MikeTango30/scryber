$('#highlights').change(function () {
    if (this.checked) {
        $('#editor').find('span').each(function() {
            if ($(this).prev('span').attr('data-word-conf') <= 0.65) {

            }
        })
    }
});

