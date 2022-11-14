$(document).ready(function(){
    var base_url = window.location.origin;
    $('.success').css('display','none');
    $('.danger').css('display','none');

    $('#hide_show_button').click(function(){
        $('.success').toggle('display');
        $('.danger').toggle('display');
    });



    $('.time_input').blur(function(){
        var user_id = $(this).attr('data-id');
        var key = $(this).attr('data-key');
        var action = $(this).attr('data-action');
        var date = $(this).attr('data-date');
        var input_value = $(this).val();
        console.log(user_id);
        console.log(key);
        console.log(action);
        console.log(date);
        console.log(input_value);
        $.ajax({
            method: "POST",
            url: base_url + "/stopTime",
            data: {id: user_id,action: action,key:key,date:date,input_value: input_value},
            dataType: "JSON",
        }).done(function (message) {
            console.log(message);
        });
        return false;
    });
    $('.checkbox_change_user_params').change(function(){
        var date = $(this).attr('data-date');
        var user_id = $(this).attr('data-id');
        var action = $(this).attr('data-action');
        if(this.checked) {
            var check_info = 1;
        } else {
            var check_info = 0;
        }
        $.ajax({
            method: "POST",
            url: base_url + "/dinnerAndLate",
            data: {id: user_id,check_info: check_info,date:date,action:action},
            dataType: "JSON",
        }).done(function (message) {
            console.log(message);
        });
    });
    $('.change_time_button').click(function(){
        var user_id = $(this).attr('data-id');
        var action = $(this).attr('data-action');
        console.log(action);
        $.ajax({
            method: "POST",
            url: base_url + "/startTime",
            data: {id: user_id,action: action},
            dataType: "JSON",
        }).done(function (message) {
            console.log(message);
            if(action == 'start'){
                $('.user_time').append("<span>"+message['current_time']+"-</span>")
            }
            else{
                $('.user_time').append("<span>"+message['current_time']+"</span><br>")
            }
        });
        return false;
    });

});