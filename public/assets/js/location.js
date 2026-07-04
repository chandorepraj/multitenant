$(document).ready(function() {
    $('#country').on('change', function() {
        var country = $(this).val();
        if(country) {
           // Clear existing options
            $('#state').empty(); 
            $('#state').append('<option value="">Select State</option>');

            $.ajax({
                url: '/get-states/' + country,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    // Populate state dropdown with options from the response
                    $.each(data, function(key, value) {
                        $('#state').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            // Disable and clear state dropdown if no category is selected
            $('#state').empty();
            $('#state').append('<option value="">Select state</option>');
        }
    });
});
$('#locationForm').on('submit', function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: "POST",   // keep POST
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {

            var modal = bootstrap.Modal.getInstance(
                document.getElementById('locationModal')
            );
            modal.hide();

            $('#olocationsTable').DataTable().ajax.reload();
        }
    });
});
$(document).on('click', '.editOrganisation', function (e) {
    e.preventDefault();

    let id = $(this).data('id');

    $.ajax({
        url: "/organisation_locations/" + id + "/edit",
        type: "GET",
        success: function (response) {
            $('#editLocationModal').html(response);
            $('#locationEditModal').modal('show');
            $('#locationEditForm').on('submit', function(e) {
            
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                type: "POST",   // keep POST
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    var modal = bootstrap.Modal.getInstance(
                        document.getElementById('locationEditModal')
                    );
                    modal.hide();
                    $('#editLocationModal').html("");
                    $('#olocationsTable').DataTable().ajax.reload();
                }
            });
        });
        }
    });
});
