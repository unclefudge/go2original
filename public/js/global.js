function uSet(name, val) {
    $("#show_archived").val(val);
    $.ajax({
        url: '/uset',
        method: "POST",
        data: {name: name, val: val},
        success: function (result) {
            show_inactive_events = val;
            display_fields();
        },
        error: function (result) {
            alert("Something went wrong. Please try refresh screen");
            display_fields();
        }
    });
}