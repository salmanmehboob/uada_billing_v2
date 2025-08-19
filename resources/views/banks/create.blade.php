<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
     aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Bank</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="bank-form" id="addBankForm" method="POST" action="{{ route($route.'.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="bank-name">Bank Name</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="bank-name"
                    placeholder="Enter bank name"
                    name="name"
                    value="{{ old('name') }}"
                    required/>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="branch-name">Branch</label>
                <input
                        type="text"
                        class="form-control @error('branch') is-invalid @enderror"
                        id="branch-name"
                        placeholder="Enter branch name"
                        name="branch"
                        value="{{ old('branch') }}"
                        required/>
                @error('branch')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="account_no">Account NO</label>
                <input
                        type="text"
                        class="form-control @error('branch') is-invalid @enderror"
                        id="account_no"
                        placeholder="Enter account no"
                        name="account_no"
                        value="{{ old('account_no') }}"
                        required/>
                @error('account_no')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3">Submit</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
