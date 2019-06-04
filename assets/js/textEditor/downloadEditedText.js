$(document).ready(function () {

    var text;
    $('#downloadButton').click(function () {
        if ($('#editor').html() !== '') {
            // saveFile(text);
            saveEditedText($('#editor').html());
        } else
            alert('Redaktoriuje nėra teksto');
    });
});

function saveEditedText(text) {
    var userfileId = $('.js-userfile').data('id');

    $.ajax({
        url : "/save_scribed_text/"+userfileId,
        type: "POST",
        data : 'text='+text,
        success: function (data, textStatus, jqXHR) {
            //data - response from server
            console.log("data", data, "textStatus", textStatus);
        },
        error: function (jqXHR, textStatus, errorThrown) {

        }
    });

}

function saveFile(Value) {

    // convert text to a BLOB.

    var textToBLOB = new Blob([Value], {type: 'text/xml'});
    var sFileName = 'manoTranskripcija.txt';

    var newLink = document.createElement("a");
    newLink.download = sFileName;

    if (window.webkitURL != null) {
        newLink.href = window.webkitURL.createObjectURL(textToBLOB);
    } else {
        newLink.href = window.URL.createObjectURL(textToBLOB);
        newLink.style.display = "none";
        document.body.appendChild(newLink);
    }

    newLink.click();
}
