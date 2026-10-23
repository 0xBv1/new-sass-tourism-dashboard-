<!-- Add Duration Price Modal -->
<div class="modal fade" id="addDurationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.duration.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Duration-Based Pricing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-2"></i>
                        Use multiplier to adjust price (0.9 = 10% discount, 1.1 = 10% increase)
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Min Days <span class="text-danger">*</span></label>
                            <input type="number" name="min_days" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max Days <span class="text-danger">*</span></label>
                            <input type="number" name="max_days" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price Multiplier <span class="text-danger">*</span></label>
                            <input type="number" name="price_multiplier" class="form-control" step="0.01" value="1.00" required>
                            <small class="text-muted">1.00 = no change</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fixed Discount</label>
                            <input type="number" name="fixed_discount" class="form-control" step="0.01" value="0">
                            <small class="text-muted">Applied after multiplier</small>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="enabled" value="1" class="form-check-input" id="durationEnabled" checked>
                        <label class="form-check-label" for="durationEnabled">Enabled</label>
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


