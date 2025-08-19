<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit"
     aria-labelledby="offcanvasEditLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEditLabel" class="offcanvas-title">Edit Bank</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form  id="editForm" method="POST" enctype="multipart/form-data">

        @csrf
            @method('PUT')
            <input type="hidden" name="bank_id" id="edit_id" >

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="edit_name">Bank Name</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="edit_name"
                    placeholder="Enter bank name"
                    name="name"
                    value="{{ old('name') }}"
                    required/>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="edit_branch">Branch</label>
                <input
                        type="text"
                        class="form-control @error('branch') is-invalid @enderror"
                        id="edit_branch"
                        placeholder="Enter branch name"
                        name="branch"
                        value="{{ old('branch') }}"
                        required/>
                @error('branch')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="edit_account">Account NO</label>
                <input
                        type="text"
                        class="form-control @error('branch') is-invalid @enderror"
                        id="edit_account"
                        placeholder="Enter account no"
                        name="account_no"
                        value="{{ old('account_no') }}"
                        required/>
                @error('account_no')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3">Update</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
