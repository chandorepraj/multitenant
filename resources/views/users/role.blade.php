@csrf
<!-- The Modal -->
<div class="modal" id="roleModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="{{ route('users.update_role') }}">
        @csrf
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Change Role</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <input type="hidden" name="user_id" id="user_id" >
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" class="form-control" name="role" required>
              <option value=""> select Role</option>
              <option value="Admin">Admin</option>
              <option value="Manager">Manager</option>
              <option value="Sales">Sales</option>
              <option value="Support">Support</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>  
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Update</button>
      </div>
    </form>
    </div>
  </div>
</div>