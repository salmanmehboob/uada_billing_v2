<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shared Bootstrap5 plugin config
        const bootstrap5Plugin = new FormValidation.plugins.Bootstrap5({
            rowSelector: '.form-control-validation',
            eleValidClass: ''
        });

        const editForm = document.getElementById('editForm');
        if (editForm) {
            // Use event delegation to handle dynamically rendered DataTable rows
            document.addEventListener('click', (e) => {
                const button = e.target.closest('.btn-edit');
                if (!button) return;

                const {
                    id, name, plot_no, email, phone_no, address,
                    contact_person_name, arrears, guardian_name,
                    sector_id,  size_id , type_id, is_active,
                    url
                } = button.dataset;

                // Set form action and input values
                editForm.action = url;
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_plot_no').value = plot_no;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_phone_no').value = phone_no;
                document.getElementById('edit_contact_person_name').value = contact_person_name;
                document.getElementById('edit_arrears').value = arrears;
                document.getElementById('edit_guardian_name').value = guardian_name;
                document.getElementById('edit_address').value = address;
                const editStatus = document.getElementById('edit_is_active');
                const editSector = document.getElementById('edit_sector_id');
                const editSize = document.getElementById('edit_size_id');
                const editType = document.getElementById('edit_type_id');

                if (editStatus) {
                    for (let option of editStatus.options) {
                        if (option.value === is_active) {
                            option.selected = true;
                            break;
                        }
                    }
                }

                if (editSector) {
                    for (let option of editSector.options) {
                        if (option.value === sector_id) {
                            option.selected = true;
                            break;
                        }
                    }
                }

                if (editSize) {
                    for (let option of editSize.options) {
                        if (option.value === size_id) {
                            option.selected = true;
                            break;
                        }
                    }
                }

                if (editType) {
                    for (let option of editType.options) {
                        if (option.value === type_id) {
                            option.selected = true;
                            break;
                        }
                    }
                }


            });

            FormValidation.formValidation(editForm, {
                fields: {
                    name: { validators: { notEmpty: { message: 'Please enter name' } } },
                    guardian_name: { validators: { stringLength: { max: 255, message: 'Max 255 characters' } } },
                    email: { validators: { emailAddress: { message: 'Please enter a valid email address' } } },
                    phone_no: {
                        validators: {
                            regexp: {
                                regexp: /^[0-9+\-\s()]{6,20}$/,
                                message: 'Enter a valid phone number'
                            }
                        }
                    },
                    plot_no: { validators: { stringLength: { max: 100, message: 'Max 100 characters' } } },
                    sector_id: { validators: { notEmpty: { message: 'Please select sector' } } },
                    size_id: { validators: { notEmpty: { message: 'Please select plot size' } } },
                    type_id: { validators: { notEmpty: { message: 'Please select plot type' } } },
                    contact_person_name: { validators: { stringLength: { max: 255, message: 'Max 255 characters' } } },
                    arrears: {
                        validators: {
                            numeric: { message: 'Arrears must be a number' }
                        }
                    },
                    address: { validators: { stringLength: { max: 1000, message: 'Max 1000 characters' } } }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: bootstrap5Plugin,
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        }


        // --------- Add Allotee Form (create.blade) ---------
        const addForm = document.getElementById('addAlloteeForm');
        if (addForm) {
            FormValidation.formValidation(addForm, {
                fields: {
                    name: { validators: { notEmpty: { message: 'Please enter name' } } },
                    guardian_name: { validators: { stringLength: { max: 255, message: 'Max 255 characters' } } },
                    email: { validators: { emailAddress: { message: 'Please enter a valid email address' } } },
                    phone_no: {
                        validators: {
                            regexp: {
                                regexp: /^[0-9+\-\s()]{6,20}$/,
                                message: 'Enter a valid phone number'
                            }
                        }
                    },
                    plot_no: { validators: { stringLength: { max: 100, message: 'Max 100 characters' } } },
                    sector_id: { validators: { notEmpty: { message: 'Please select sector' } } },
                    size_id: { validators: { notEmpty: { message: 'Please select plot size' } } },
                    type_id: { validators: { notEmpty: { message: 'Please select plot type' } } },
                    contact_person_name: { validators: { stringLength: { max: 255, message: 'Max 255 characters' } } },
                    arrears: {
                        validators: {
                            numeric: { message: 'Arrears must be a number' }
                        }
                    },
                    address: { validators: { stringLength: { max: 1000, message: 'Max 1000 characters' } } }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: bootstrap5Plugin,
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        }

    });
</script>
