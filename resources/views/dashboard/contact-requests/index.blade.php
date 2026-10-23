@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Contact Requests">
            <li class="breadcrumb-item active">Contact Requests</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert/>
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="contact-request"/>
                        <div class="card-body order-datatable overflow-x-auto">
                            <div class="">
                                {!! $dataTable->table(['class'=>'display']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showMessageModal" tabindex="-1" aria-labelledby="showMessageModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showMessageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-message-body">
                    Welcome!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="markAsSpamModal" tabindex="-1" aria-labelledby="markAsSpamModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAsSpamModalLabel">Mark as spam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-message-body">
                    <p class="m-2 text-dark" style="font-size: 18px">
                        Are you sure marking <strong style="text-decoration: underline"
                                                     class="spam-email text-danger"></strong> as spam
                    </p>
                    <div class="alert alert-warning">That means you will never receive contact emails from him</div>
                </div>
                <div class="modal-footer">
                    <form id="mark-as-spam-form" action="{{ route('dashboard.contact-requests.mark-as-spam') }}"
                          method="post">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        @csrf
                        <input type="hidden" name="email" id="spam-email-input">
                        <button type="submit" class="btn btn-success">Mark as spam</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#data-table').on('draw.dt', function () {
            $('.show-message').on('click', function () {
                $('#show-message-body').html($(this).attr('data-message'))
                $('#showMessageModalLabel').html($(this).attr('data-name'))
            })

            $('.mark-as-spam').on('click', function () {
                $('.spam-email').text($(this).data('email'))
                $('#spam-email-input').val($(this).data('email'))
            });


        });

        $('#mark-as-spam-form').on('submit', function (e) {
            console.log('Mark as spam')
            e.preventDefault()
            $(this).find('button').attr('disabled', true)
            axios.post($(this).attr('action'), {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email: $('#spam-email-input').val()
            }).then(res => {
                toastr.success('Email marked as spam')
            }).catch(error => {
                toastr.error(error.response.data.message || "Something went wrong, try again later")
            }).finally(() => {
                $(this).find('button').attr('disabled', false)
                $("#markAsSpamModal").modal('hide')
                $('#data-table').DataTable().draw()
            })
        })
    </script>
@endpush
