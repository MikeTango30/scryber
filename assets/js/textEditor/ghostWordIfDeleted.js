(function() {
    let originalWord = '';
    $('#editor').bind('keypress', function (e) {
        originalWord = window.getSelection().anchorNode.textContent;
        if (window.getSelection().anchorNode.textContent === '')
            window.getSelection().anchorNode.textContent = originalWord;
        // if (this.length === 0)
        //     console.log('zero');

    })
}());