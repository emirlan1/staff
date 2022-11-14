$(document).ready(function() {
    var base_url = window.location.origin;

    $('#new_holiday').submit(function(){
        var form = $(this)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: base_url + "/newHoliday",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            console.log(message);
            $('#holiday_table').append("<tr><td>"+message['date']+"</td><td>"+message['name']+"</td><td><input type='checkbox' class='checkbox_holiday'><td></tr>")
        });
    });
    $('.checkbox_holiday').change(function(){
        var date = $(this).attr('data-date');
        if(this.checked) {
            var check_info = 1;
        } else {
            var check_info = 0;
        }
        $.ajax({
            method: "POST",
            url: base_url + "/holidayRepeat",
            data: {check_info: check_info,date:date},
            dataType: "JSON",
        }).done(function (message) {
            console.log(message);
        });
    });
})