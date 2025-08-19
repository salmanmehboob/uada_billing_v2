<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" id="edit_user_id">

            <div class="mb-3 form-control-validation">
                <label for="edit_name" class="form-label">Name</label>
                <input type="text" id="edit_name" name="name" class="form-control" />
            </div>

            <div class="mb-3 form-control-validation">
                <label for="edit_email" class="form-label">Email</label>
                <input type="email" id="edit_email" name="email" class="form-control" />
            </div>

            <div class="mb-3 form-control-validation">
                <label for="edit_role_id" class="form-label">Role</label>
                <select name="role_id" id="edit_role_id" class="form-select">
                    <option value="">Select</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 form-control-validation">
                <label for="edit_password" class="form-label">Password</label>
                <input type="password" id="edit_password" name="password" class="form-control" />
            </div>

            <div class="mb-3 form-control-validation">
                <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="edit_password_confirmation" name="password_confirmation" class="form-control" />
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
