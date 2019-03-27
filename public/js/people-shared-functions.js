//
// Hide / show avatar modal
//
$("#avatar").click(function () {
    $("#modal_avatar_edit").modal('show');
});

$("#avatar-edit").click(function (e) {
    e.stopPropagation();
    $("#modal_avatar_edit").modal('show');
});

//
// Delete Person
//
$("#but_del_person").click(function (e) {
    var uid = $(this).data('uid');
    var name = $(this).data('name');
    Swal.fire({
        title: "Are you sure?",
        html: "All information and check-ins will be deleted for<br><b>" + name + "</b><br><br><span class='kt-font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this person!</span> ",
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
            window.location.href = "/people/" + uid + '/del';
        }
    });
});