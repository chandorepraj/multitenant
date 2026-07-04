
    @csrf
<!-- The Modal -->
<div class="modal" id="convertModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Convert Lead to Customer</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
  <form action="{{ route('lead.conversion_save') }}" id="lead_conversion_form" method="POST">
            @csrf
      <!-- Modal body -->
      <div class="modal-body" id="convert_modal_body">
          
            <input type="hidden" name="lead_id" id="lead_id">
            <input type="hidden" name="lead_name" id="lead_name" >

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" name="company" id="company" class="form-control" required>
            </div>
       
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancle</button>
        <button type="submit" class="btn btn-success">Save</button>
      </div>
 </form>
    </div>
  </div>
</div>