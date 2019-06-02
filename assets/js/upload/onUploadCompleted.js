(function () {
    $('.dropzone').bind('queuecomplete', function (){
        console.log('here');//window.location.href = '127.0.0.1:8000/user_dashboard'
    })
}());