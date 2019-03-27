//
// Delete Event
//
$("#but_del_event").click(function (e) {
    var eid = $(this).data('eid');
    var name = $(this).data('name');
    Swal.fire({
        title: "Are you sure?",
        html: "All information and check-ins will be deleted for<br><b>" + name + "</b><br><br><span class='kt-font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this event!</span> ",
        cancelButtonText: "Cancel!",
        confirmButtonText: "Yes, delete it!",
        showCancelButton: true,
        reverseButtons: true,
        allowOutsideClick: true,
        animation: false,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
            popup: 'animated tada'
        }
    }).then(function (result) {
        if (result.value) {
            window.location.href = "/event/" + eid + '/del';
        }
    });
});