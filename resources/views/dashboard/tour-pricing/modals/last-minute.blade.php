<!-- Add Last Minute Deal Modal -->
<div class="modal fade" id="addLastMinuteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.last-minute.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Last Minute Deal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Within Days Before Travel <span class="text-danger">*</span></label>
                        <input type="number" name="days_before_travel" class="form-control" min="1" required placeholder="e.g., 7">
                        <small class="text-muted">Apply when travel date is within this many days</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" class="form-control" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" name="discount_value" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Available Slots</label>
                        <input type="number" name="min_available_slots" class="form-control" min="0">
                        <small class="text-muted">Only apply if tour has at least this many slots available (leave empty for any)</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" class="form-control">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="enabled" value="1" class="form-check-input" id="lastMinuteEnabled" checked>
                        <label class="form-check-label" for="lastMinuteEnabled">Enabled</label>
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


