<!-- Add Dynamic Pricing Modal -->
<div class="modal fade" id="addDynamicModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.dynamic.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Dynamic Pricing Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Trigger Type <span class="text-danger">*</span></label>
                        <select name="trigger_type" class="form-control" required>
                            <option value="occupancy">Occupancy Rate (% booked)</option>
                            <option value="bookings_count">Total Bookings Count</option>
                            <option value="days_to_departure">Days Until Departure</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Threshold Min <span class="text-danger">*</span></label>
                            <input type="number" name="threshold_min" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Threshold Max</label>
                            <input type="number" name="threshold_max" class="form-control" min="0">
                            <small class="text-muted">Leave empty for "min or more"</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price Action <span class="text-danger">*</span></label>
                            <select name="price_action" class="form-control" required>
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                            <select name="adjustment_type" class="form-control" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="number" name="adjustment_value" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" name="priority" class="form-control" value="0">
                            <small class="text-muted">Higher = applied first</small>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="enabled" value="1" class="form-check-input" id="dynamicEnabled" checked>
                                <label class="form-check-label" for="dynamicEnabled">Enabled</label>
                            </div>
                        </div>
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


