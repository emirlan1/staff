document.addEventListener("DOMContentLoaded", function() {
    var base_url = window.location.origin;
    $('#sign_in_form').submit(function () {
        var form = $(this)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: base_url + "/signIn",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            console.log(message);


            $('.list-error-group').empty();
            if (message === 1) {
                $('.list-error-group').append('<li class="list-group-item list-group-item-danger">Login or password incorrect</li>');
            } else {
                $('.list-error-group').append('<li class="list-group-item list-group-item-success">Successfully, now you will be redirected to the blog</li>');
               window.location.assign('Staff');
            }
        });
        return false;
    });


});