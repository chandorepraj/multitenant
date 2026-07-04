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
    $('#deleteTaskForm'+id).submit();
    //window.location.reload();
}