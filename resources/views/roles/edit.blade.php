<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser{{ $row->id }}"
     aria-labelledby="offcanvasEditUserLabel{{ $row->id }}">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEditUserLabel{{ $row->id }}" class="offcanvas-title">Edit Warehouse</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="warehouse-form" id="warehouseForm{{$row->id}}" method="POST" action="{{ route($route.'.update', $row->id) }}">

        @csrf
            @method('PUT')

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="warehouse-name">Warehouse Name</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="warehouse-name"
                    placeholder="Enter warehouse name"
                    name="name"
                    value="{{ old('name', $row->name) }}"
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
                    value="{{ old('location', $row->location) }}"
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
                    name="description" re
                    rows="3">{{ old('description', $row->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3">Update</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
