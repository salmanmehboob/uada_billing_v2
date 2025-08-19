<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
     aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Warehouse</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="warehouse-form" id="addWarehouseForm" method="POST" action="{{ route($route.'.store') }}">
            @csrf

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="warehouse-name">Warehouse Name</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="warehouse-name"
                    placeholder="Enter warehouse name"
                    name="name"
                    value="{{ old('name') }}"
                    required/>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="warehouse-location">Location</label>
                <input
                    type="text"
                    class="form-control @error('location') is-invalid @enderror"
                    id="warehouse-location"
                    placeholder="Enter warehouse location"
                    name="location"
                    value="{{ old('location') }}"
                    required/>
                @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="warehouse-description">Description</label>
                <textarea
                    id="warehouse-description"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Enter warehouse description"
                    name="description"
                    rows="3">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3">Submit</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
