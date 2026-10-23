<!-- Add Custom Rule Modal -->
<div class="modal fade" id="addRuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dashboard.tour-pricing.rule.store', $tour) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Custom Pricing Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rule Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g., Weekend Special">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rule Type <span class="text-danger">*</span></label>
                            <select name="rule_type" class="form-control" required>
                                <option value="date_range">Date Range</option>
                                <option value="day_of_week">Day of Week</option>
                                <option value="month">Month</option>
                                <option value="holiday">Holiday</option>
                                <option value="nationality">Nationality</option>
                                <option value="age_group">Age Group</option>
                                <option value="group_size">Group Size</option>
                                <option value="booking_channel">Booking Channel</option>
                                <option value="promo_code">Promo Code</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Describe when this rule applies..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Action Type <span class="text-danger">*</span></label>
                            <select name="action_type" class="form-control" required>
                                <option value="percentage">Percentage Discount</option>
                                <option value="fixed">Fixed Amount Discount</option>
                                <option value="set_price">Set Fixed Price</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="number" name="action_value" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" name="priority" class="form-control" value="0">
                        </div>
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="stackable" value="1" class="form-check-input" id="ruleStackable">
                                <label class="form-check-label" for="ruleStackable">
                                    Stackable (can combine with other rules)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="enabled" value="1" class="form-check-input" id="ruleEnabled" checked>
                                <label class="form-check-label" for="ruleEnabled">Enabled</label>
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


