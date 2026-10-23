<!-- Add Tiered Price Modal -->
<div class="modal fade" id="addTieredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.tiered.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Tiered Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tier Name <span class="text-danger">*</span></label>
                        <input type="text" name="tier_name" class="form-control" required placeholder="e.g., Solo Traveler, Small Group">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Min Persons <span class="text-danger">*</span></label>
                            <input type="number" name="min_persons" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max Persons <span class="text-danger">*</span></label>
                            <input type="number" name="max_persons" class="form-control" min="1" required>
                        </div>
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
                    <div class="mb-3">
                        <label class="form-label">Benefits (Optional)</label>
                        <textarea name="benefits" class="form-control" rows="2" placeholder="Special benefits for this tier..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="enabled" value="1" class="form-check-input" id="tieredEnabled" checked>
                                <label class="form-check-label" for="tieredEnabled">Enabled</label>
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


