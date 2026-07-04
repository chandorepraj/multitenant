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
            url: '/invitations/'+id,
            type: 'DELETE',
             headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                   "method":'DELETE' 
            },
            success: function(response){
                alert("Invitation deleted successfully.");
                window.location.href = "/users";

            }
        });
}
window.update_role = function (id) {
    $("#user_id").val(id);
};
$('#roleModal').on('hide.bs.modal', function (event) {
    /*var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('url'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find('form').attr('action', url); // Update the form's action*/
    $("#user_id").val("");
});

$('#deleteTeamModal').on('hide.bs.modal', function (event) {
    /*var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('url'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find('form').attr('action', url); // Update the form's action*/
    $("#delete_team_id").val("");
});
window.delete_team_confirmation = function (id) {
    $("#delete_team_id").val(id);
};
window.delete_team_record = function()
{
    var id = $("#delete_team_id").val();
    $.ajax({
            url: '/users/team_delete/'+id,
            type: 'POST',
             headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                   "method":'DELETE' 
            },
            success: function(response){
                alert("Member deleted successfully.");
                window.location.href = "/users";

            }
        });
}