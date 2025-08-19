<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="role-title">Add New Role</h4>
                    <p class="text-body-secondary">Set role permissions</p>
                </div>
                <!-- Add role form -->
                <form id="addRoleForm" class="row g-3"  action="{{route($route .'.store')}}" method="post">
                    @csrf
                    <div class="col-12 form-control-validation mb-3">
                        <label class="form-label" for="name">Role Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{old('name')}}"
                            class="form-control"
                            placeholder="Enter a role name"
                            tabindex="-1" />
                    </div>
                    <div class="col-12">
                        <h5 class="mb-6">Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                <tr>
                                    <td class="text-nowrap fw-medium">
                                        Administrator Access
                                        <i
                                            class="icon-base ti tabler-info-circle icon-xs"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Allows a full access to the system"></i>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input"  type="checkbox" id="selectAll" />
                                                <label class="form-check-label" for="selectAll"> Select All </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($permissions as $row)
                                 <tr>
                                    <td class="text-nowrap fw-medium text-heading">{{$row['name']}}</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            @foreach($row['permissions'] as $permissionID => $permission)
                                            <div class="form-check mb-0 me-4 me-lg-12">
                                                <input class="form-check-input" type="checkbox" name="permission[]"      value="{{$permissionID}}" id="check{{$permissionID}}" />
                                                <label class="form-check-label" for="check{{$permissionID}}">{{ Str::before($permission, ' ') }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-4 me-1">Submit</button>
                        <button
                            type="reset"
                            class="btn btn-label-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
