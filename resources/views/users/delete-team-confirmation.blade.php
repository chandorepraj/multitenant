@csrf
<input type="hidden" name="delete_team_id" id="delete_team_id">
<!-- The Modal -->
<div class="modal" id="deleteTeamModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Delete confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        Do you want to delete selected record?
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancle</button>
        <button type="button" onclick="return delete_team_record()" class="btn btn-danger" data-dismiss="modal">Delete</button>
      </div>

    </div>
  </div>
</div>