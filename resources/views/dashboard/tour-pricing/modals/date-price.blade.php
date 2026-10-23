<!-- Add Date Price Modal -->
<div class="modal fade" id="addDatePriceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.date.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Date-Specific Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <input type="text" name="label" class="form-control" placeholder="e.g., Christmas Special">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Adult Price <span class="text-danger">*</span></label>
                            <input type="number" name="adult_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Child Price</label>
                            <input type="number" name="child_price" class="form-control" step="0.01" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Infant Price</label>
                            <input type="number" name="infant_price" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="enabled" value="1" class="form-check-input" id="datePriceEnabled" checked>
                        <label class="form-check-label" for="datePriceEnabled">Enabled</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


