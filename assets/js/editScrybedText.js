const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

require('../css/app.scss');
require('./textEditor/audioSynchronize');
require('./textEditor/saveEditedText');
require('./textEditor/highlightHardWords');
require('./textEditor/ghostWordIfDeleted');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
