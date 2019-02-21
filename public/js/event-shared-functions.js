//
// Delete Event
//
$("#but_del_event").click(function (e) {
    var name = $(this).data('name');
    swal({
        title: "Are you sure?",
        html: "All information and check-ins will be deleted for<br><b>" + name + "</b><br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this event!</span> ",
        cancelButtonText: "Cancel!",
        confirmButtonText: "Yes, delete it!",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        reverseButtons: true,
        allowOutsideClick: true
    }).then(function (result) {
        if (result.value) {
            window.location.href = "/event/" + "{{ $event->id }}" + '/del';
        }
    });
});