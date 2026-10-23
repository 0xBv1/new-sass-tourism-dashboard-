<div class="d-flex gap-2">
    @can('service-approvals.show')
        <a href="{{ route('dashboard.service-approvals.show', $approval->id) }}" 
           class="btn btn-sm btn-info" 
           title="View Details">
            <i class="fa fa-eye"></i>
        </a>
    @endcan

    @if($approval->isPending())
        @can('service-approvals.approve')
            <button type="button" 
                    class="btn btn-sm btn-success" 
                    title="Approve"
                    onclick="approveService({{ $approval->id }})">
                <i class="fa fa-check"></i>
            </button>
        @endcan

        @can('service-approvals.reject')
            <button type="button" 
                    class="btn btn-sm btn-danger" 
                    title="Reject"
                    data-bs-toggle="modal" 
                    data-bs-target="#rejectModal" 
                    data-approval-id="{{ $approval->id }}">
                <i class="fa fa-times"></i>
            </button>
        @endcan
    @endif
</div>

<script>
function approveService(approvalId) {
    if (confirm('Are you sure you want to approve this service?')) {
        fetch(`{{ url('dashboard/service-approvals') }}/${approvalId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to approve service. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}
</script>


