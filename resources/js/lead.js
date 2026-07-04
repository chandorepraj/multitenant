$('#deleteModal').on('hide.bs.modal', function (event) {
    /*var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('url'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find('form').attr('action', url); // Update the form's action*/
    $("#delete_id").val("");
});
window.delete_confirmation = function (id) {
    $("#delete_id").val(id);
};
window.delete_record = function()
{
    var id = $("#delete_id").val();
    $.ajax({
            url: '/leads/'+id,
            type: 'DELETE',
             headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                   "method":'DELETE' 
            },
            success: function(response){
                alert("Lead deleted successfully.");
                window.location.href = "/leads";

            }
        });
}
window.alert_message = function()
{
    alert('This record is already converted to Customer');
}
window.conversion_modal = function (lead_id) 
{
    $.ajax({
            url: '/leads/convert/'+lead_id,
            type: 'GET',
            dataType: 'json',
            success: function(response){
                $('#lead_id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#lead_name').val(response.data.name);
                $('#email').val(response.data.email);
                $('#phone').val(response.data.phone);
            }
        });
}