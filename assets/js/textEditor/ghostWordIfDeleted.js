var targetNodes         = $("#editor");
var MutationObserver    = window.MutationObserver || window.WebKitMutationObserver;
var observer          = new MutationObserver (mutationHandler);
var observerConfig           = { childList: true, characterData: true, attributes: true, subtree: true };

//--- Add a target node to the observer. Can only add one node at a time.
targetNodes.each ( function () {
    observer.observe (this, observerConfig);
} );

function mutationHandler (mutations) {
    mutations.forEach ( function (mutation) {
        if (typeof mutation.removedNodes === "object") {
            var jq = $(mutation.removedNodes);
            var htmlSpan = null;
            if (jq.is('span.word'))
                htmlSpan = jq[0];
            console.log(htmlSpan);

        }
    } );
}


(function () {
    $('.word').change(function () {
        if (this.length <= 1)
            console.log('here');
        });
    $('#hints').change(function () {
        if (!this.checked) {
            $('.word').removeClass('word').addClass('no-word');
        } else if (this.checked)
            $('.no-word').removeClass('no-word').addClass('word');
    })
}());