<x-dashboard.form.input-select :options="$options ?? []"
                               error-key="related_tour"
                               multible
                               name="related_tours[]"
                               track-by="id"
                               :value="$value ?? []"
                               id="related_tour"
                               option-lable="title"
                               label-title="Related Tours"
/>

@push('js')
    <script>
        setTimeout(() => {
            let selector = '#related_tour'
            $(selector).select2('destroy');
            $(selector).select2({
                ajax: {
                    url: "{{ route('api.tours.index') }}",
                    data: function (params) {
                        return {
                            title: `*${params.term}*`,
                        }
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.data.map(function (tour) {
                                return {id: tour.id, text: tour.title}
                            })
                        };
                    }
                }
            })
        }, 1500)

    </script>
@endpush
