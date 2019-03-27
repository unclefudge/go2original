function uSet(name, val) {
    $("#show_archived").val(val);
    $.ajax({
        url: '/uset',
        method: "POST",
        data: {name: name, val: val},
        success: function (result) {
            //alert('uset');
            uSetReturn(true, val);
        },
        error: function (result) {
            alert("Something went wrong. Please try refresh screen");
            uSetReturn(false, val)
        }
    });
}