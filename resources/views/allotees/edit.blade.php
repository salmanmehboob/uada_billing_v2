<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasEditLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEditLabel" class="offcanvas-title">Edit {{$title}}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="allotee_id" id="edit_id">

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="edit_name">Name</label>
                <input type="text" id="edit_name" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Enter full name" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">S/D/W/O</label>
                <input type="text" id="edit_guardian_name" name="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror"
                       value="{{ old('guardian_name') }}" placeholder="Enter guardian name">
                @error('guardian_name')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Email</label>
                <input type="email" id="edit_email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Enter email">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Phone</label>
                <input type="text" id="edit_phone_no" name="phone_no" class="form-control @error('phone_no') is-invalid @enderror"
                       value="{{ old('phone_no') }}" placeholder="Enter phone number">
                @error('phone_no')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Plot No</label>
                <input type="text" id="edit_plot_no" name="plot_no" class="form-control @error('plot_no') is-invalid @enderror"
                       value="{{ old('plot_no') }}" placeholder="Enter plot number">
                @error('plot_no')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Select Sector</label>
                <select name="sector_id" id="edit_sector_id" class="form-select @error('sector_id') is-invalid @enderror">

                    @foreach($sectors as $key =>  $row)
                        <option  {{ old('sector_id') == '1' ? 'selected' : '' }}
                                 value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
                @error('sector_id')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Select  Plot Size</label>
                <select name="size_id" id="edit_size_id" class="form-select @error('size_id') is-invalid @enderror">

                    @foreach($sizes as $key =>  $row)
                        <option  {{ old('size_id') == '1' ? 'selected' : '' }}
                                 value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
                @error('size_id')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Select  Plot TYpe</label>
                <select name="type_id" id="edit_type_id" class="form-select @error('type_id') is-invalid @enderror">

                    @foreach($types as $key =>  $row)
                        <option  {{ old('type_id') == '1' ? 'selected' : '' }}
                                 value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
                @error('type_id')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


            <div class="mb-6 form-control-validation">
                <label class="form-label">Contact Person Name</label>
                <input type="text" id="edit_contact_person_name" name="contact_person_name" class="form-control @error('contact_person_name') is-invalid @enderror"
                       value="{{ old('contact_person_name') }}" placeholder="Contact Person Name">
                @error('contact_person_name')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Arrears</label>
                <input type="text" id="edit_arrears" name="arrears" class="form-control @error('arrears') is-invalid @enderror"
                       value="{{ old('arrears') }}" placeholder="Arrears">
                @error('arrears')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label">Address</label>
                <textarea name="address" id="edit_address" class="form-control @error('address') is-invalid @enderror"
                          placeholder="Enter address">{{ old('address') }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


            <button type="submit" class="btn btn-primary me-3">Update</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
