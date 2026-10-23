@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Blogs">
            <li class="breadcrumb-item active">Blogs</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="blog">
                            <a data-model="Blog" href="#" type="button" class="auto-translate btn btn-primary add-row mt-md-0 mt-2">
                                <i class="fa icon fa-language"></i> Auto Translate
                            </a>
                        </x-dashboard.partials.table-card-header>
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
@endsection

@push('js')
    <script>
        let UPDATE_URL = "{{ route('dashboard.blogs.update', ['blog' => 122]) }}"

        $('#data-table').on('draw.dt', function () {
            $('.btn-blog').click(function () {

                $(this).attr('disabled', true)
                $(this).find('.fa').removeClass('fa-paper-plane')
                $(this).find('.fa').removeClass('fa-ban')
                $(this).find('.fa').addClass('fa-spin fa-spinner')

                let payload = {
                    status: $(this).data('status'),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    action: "UPDATE_PUBLISH"
                }

                axios.put(UPDATE_URL.replace('122', $(this).data('id')), payload)
                .then(res=>{
                    toastr.success(res.data.message)
                }).catch(e=>{
                    toastr.error("Unknown Error")
                }).finally(()=>{
                    $('#data-table').DataTable().draw()
                })
            })
        })
    </script>
@endpush
