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
            var removedNode = $(mutation.removedNodes);
            removedNode.text('');
            removedNode.addClass('empty');
            var htmlSpan = null;

            if (removedNode.is('span.word'))
                htmlSpan = removedNode[0];
            var previousSibling = $(mutation.previousSibling);
                if (htmlSpan)
                    if (!previousSibling[0])
                        $(mutation.nextSibling.nextSibling).before(htmlSpan);
                    else
                        previousSibling.after(htmlSpan);
        }
    } );
}
//
// <div class="tooltip">Hover over me
// <span class="tooltiptext">Tooltip text</span>
// </div>

(function() {
    $(document).on('click', '.empty', function (e) {
        $(this).text($(this).attr('data-word-content')).focus().removeClass('empty');
    })
}());

(function () {
    $('#hints').change(function () {
        if (!this.checked) {
            $('.no-word').removeClass('no-word').addClass('word');
        } else if (this.checked)
            $('.word').removeClass('word').addClass('no-word');
    })
}());