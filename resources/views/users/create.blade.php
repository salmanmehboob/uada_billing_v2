<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
     aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form id="addUserForm" method="POST" action="{{ route($route.'.store') }}">
            @csrf

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="name">Name</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    placeholder="Enter name"
                    name="name"
                    value="{{ old('name') }}"
                    required/>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 form-control-validation">
                <label class="form-label" for="email">Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    placeholder="Enter email"
                    name="email"
                    value="{{ old('email') }}"
                    required/>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6 form-control-validation">
                <label for="role_id" class="form-label">Basic</label>
                <select id="role_id" name="role_id" class="select2 form-select" data-allow-clear="true">
                    <option></option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
                @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password"/>
                    <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                    @error('password')
                    <span class="invalid-feedback">
                        {{ $message }}
                     </span>
                    @enderror
                </div>
            </div>

            <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="confirm-password">Confirm Password</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="confirm-password"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password"/>
                    <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                    @error('password_confirmation')
                    <span class="invalid-feedback">
                                         {{ $message }}
                     </span>
                    @enderror
                </div>
            </div>


            <button type="submit" class="btn btn-primary me-3">Submit</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>
