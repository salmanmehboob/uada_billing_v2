<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shared Bootstrap5 plugin config
        const bootstrap5Plugin = new FormValidation.plugins.Bootstrap5({
            rowSelector: '.form-control-validation',
            eleValidClass: ''
        });

        // --------- Edit Category ---------
        const editForm = document.getElementById('editForm');
        if (editForm) {
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', () => {
                    const { id, name, code, url , image } = button.dataset;

                    // Update form action and fields
                    editForm.action = url;
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_code').value = code;
                    document.getElementById('uploaded_image').src = image;
                });
            });

            FormValidation.formValidation(editForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: 'Please enter name' }
                        }
                    },
                    code: {
                        validators: {
                            notEmpty: { message: 'Please enter code' }
                        }
                    }
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

        // --------- Add Category Form ---------
        const addForm = document.getElementById('addCategoryForm');
        if (addForm) {
            FormValidation.formValidation(addForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: 'Please enter category name' }
                        }
                    },
                    code: {
                        validators: {
                            notEmpty: { message: 'Please enter code' }
                        }
                    }
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
