$(document).ready(function () {
    $('#olocationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: oLocationDatatableUrl,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'address1', name: 'address1'},
            {data: 'address2', name: 'address2'},
            {data: 'city', name: 'city'},
            {data: 'state', name: 'state'},
            {data: 'country', name: 'country'},
            {data: 'phone', name: 'phone'},
            {data: 'fax', name: 'fax'},
            {data: 'created_by', name: 'created_by'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

});
$(document).on('click', '.deleteOLocation', function () {

    let id = $(this).data('id');

    if(confirm('Delete location?')) {

        $.ajax({
            url: "/organisation_locations/" + id,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                $('#olocationsTable').DataTable().ajax.reload();
            }
        });

    }
});