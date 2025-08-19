<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reusable Bootstrap5 plugin config
        const bootstrap5PluginConfig = {
            rowSelector: '.form-control-validation',
            eleValidClass: ''
        };

        // ---------- Edit User Form ----------
        const editForm = document.getElementById('editUserForm');
        if (editForm) {
            // Attach click handler to edit buttons
            document.querySelectorAll('.btn-edit-user').forEach(button => {
                button.addEventListener('click', () => {
                    const { id, name, email, role, url } = button.dataset;

                    editForm.action = url;

                    document.getElementById('edit_user_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role_id').value = role;
                });
            });

            // Edit form validation
            FormValidation.formValidation(editForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: 'Please enter user name' }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: { message: 'Please enter email' },
                            emailAddress: { message: 'Invalid email' }
                        }
                    },
                    password: {
                        validators: {
                            callback: {
                                message: 'Password must be at least 8 characters',
                                callback: input => input.value === '' || input.value.length >= 8
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            identical: {
                                compare: () => editForm.querySelector('[name="password"]').value,
                                message: 'Passwords do not match'
                            }
                        }
                    },
                    role_id: {
                        validators: {
                            notEmpty: { message: 'Please select a role' }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5(bootstrap5PluginConfig),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        }

        // ---------- Add User Form ----------
        const addForm = document.getElementById('addUserForm');
        if (addForm) {
            FormValidation.formValidation(addForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: 'Please enter user name' }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: { message: 'Please enter user email' },
                            emailAddress: { message: 'Please enter a valid email address' }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: { message: 'Please enter a password' },
                            stringLength: {
                                min: 8,
                                message: 'Password must be at least 8 characters'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: { message: 'Please confirm your password' },
                            identical: {
                                compare: () => addForm.querySelector('[name="password"]').value,
                                message: 'Password and confirmation must match'
                            }
                        }
                    },
                    role_id: {
                        validators: {
                            notEmpty: { message: 'Please select a role' }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5(bootstrap5PluginConfig),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        }
    });
</script>
