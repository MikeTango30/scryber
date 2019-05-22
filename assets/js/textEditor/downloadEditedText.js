$(document).ready(function() {

    var text;
    $('#downloadButton').click(function() {
        $('#editor').find('span').each(function(){
            text = $('#editor').text().replace(/ /g,'');
            text = text.replace(/\n/g, ' ').trim();
        });
        if ($('#editor').text() !== '')
            saveFile(text);
        else
            alert('Redaktoriuje nėra teksto');
    });
});

function saveFile(Value) {

    // convert text to a BLOB.

    var textToBLOB = new Blob([Value], { type: 'text/xml' });
    var sFileName = 'manoTranskripcija.txt';

    var newLink = document.createElement("a");
    newLink.download = sFileName;

    if (window.webkitURL != null) {
        newLink.href = window.webkitURL.createObjectURL(textToBLOB);
    }
    else {
        newLink.href = window.URL.createObjectURL(textToBLOB);
        newLink.style.display = "none";
        document.body.appendChild(newLink);
    }

    newLink.click();
}
