@csrf
<input type="hidden" name="delete_id" id="delete_id">
<!-- The Modal -->
<div class="modal" id="inviteModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="{{ route('invitations.store') }}">
        @csrf
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Invite User</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> 
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
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Send Invitation</button>
      </div>
    </form>
    </div>
  </div>
</div>