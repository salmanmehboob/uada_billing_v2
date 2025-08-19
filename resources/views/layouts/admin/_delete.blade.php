<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal-{{ $row->id }}"  tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route($route.'.destroy', [$row->id]) }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                    Confirm
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
