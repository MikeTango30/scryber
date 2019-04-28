$(document).ready(function() {

    var text = [];
    $('#downloadButton').click(function() {
        $('#editor').find('div').each(function(){
            text.push($(this).text().trim())
        });
        text = text.join(' ');
        if ($('#editor').text() !== '')
            saveFile(text);
        else
            alert('You know nothing!');
    });
});


// USING BLOB (BINARY LARGE OBJECT) TO SAVE THE TEXT.

function saveFile(Value) {

    // CONVERT THE TEXT TO A BLOB.

    var textToBLOB = new Blob([Value], { type: 'text/xml' });
    var sFileName = 'myTranscription.txt';       // THE FILE IN WHICH THE CONTENTS WILL BE SAVED.

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
