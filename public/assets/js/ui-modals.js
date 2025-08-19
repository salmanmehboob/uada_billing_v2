/**
 * UI Modals
 */

'use strict';
(function () {
    const animationModal = document.querySelector('#deleteModal');
    let deleteUrl = ''; // Store the delete URL dynamically

    // Open delete confirmation modal when .delete-record is clicked
    document.addEventListener('click', function (event) {
        if (event.target.closest('.delete-record')) {
            event.preventDefault();

            const button = event.target.closest('.delete-record');
            const deleteModal = document.getElementById('deleteModal');

            // Show modal with Bootstrap
            const modalInstance = new bootstrap.Modal(deleteModal);
            modalInstance.show();
        }
    });


    // Confirm Delete Button Click - Perform AJAX request
    document.querySelector('#confirmDeleteBtn').addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');

        // AJAX request to delete record
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                // if (data.success) {
                    // Close modal and refresh the table/page
                    const deleteModal = bootstrap.Modal.getInstance(animationModal);
                    deleteModal.hide();

                    // Optionally refresh DataTable or reload page
                    // location.reload();
                    $('.datatable').DataTable().ajax.reload();
                // } else {
                //     alert('Error deleting record.');
                // }
            })
            .catch(error => console.error('Error:', error));
    });

})();

