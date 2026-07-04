$(document).on('click', '.deleteRole', function () {

    let id = $(this).data('id');

    if(confirm('Delete role?')) {

        $.ajax({
            url: "/roles/" + id,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                window.location.href = "/roles";
            }
        });

    }
});