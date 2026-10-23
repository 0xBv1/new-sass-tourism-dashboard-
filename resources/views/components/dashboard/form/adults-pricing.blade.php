@props([
    'adults' => []
])
<div id="adults_pricing">
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center" colspan="6">Adults Pricing</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 20%">Age Range</td>
                <td style="width: 15%">Max Count</td>
                <td style="width: 20%">Price</td>
                <td style="width: 25%">Description</td>
                <td style="width: 10%">Enabled</td>
                <td style="width: 10%">
                    <button @click.prevent="addAdultPricing()" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>

            <tr v-for="(adult, index) in adults_pricing">
                <td>
                    <input required class="form-control" type="text" v-model="adult.age_range"
                           :name="`adults[${index}][age_range]`" placeholder="e.g., 18-25" aria-label="Age Range">
                </td>
                <td>
                    <input required class="form-control" type="number" v-model="adult.max_count"
                           :name="`adults[${index}][max_count]`" placeholder="Max Count" aria-label="Max Count">
                </td>
                <td>
                    <input required class="form-control" type="number" step="0.01" v-model="adult.price"
                           :name="`adults[${index}][price]`" placeholder="Price" aria-label="Price">
                </td>
                <td>
                    <input class="form-control" type="text" v-model="adult.description"
                           :name="`adults[${index}][description]`" placeholder="Description" aria-label="Description">
                </td>
                <td>
                    <input type="checkbox" v-model="adult.enabled" :name="`adults[${index}][enabled]`" value="1">
                    <input type="hidden" :name="`adults[${index}][enabled]`" :value="adult.enabled ? 1 : 0">
                </td>
                <td>
                    <button @click.prevent="removeAdultPricing(index)"
                            class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

            <tr v-if="!adults_pricing.length">
                <td colspan="6">No adults pricing configured</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        const el = new Vue({
            el: '#adults_pricing',
            data() {
                return {
                    adults_pricing: {!! json_encode($adults) !!}
                }
            },
            methods: {
                removeAdultPricing(index) {
                    this.adults_pricing.splice(index, 1)
                },
                addAdultPricing() {
                    this.adults_pricing.push({
                        age_range: '',
                        max_count: 1,
                        price: 0,
                        description: '',
                        enabled: true,
                    })
                },
            }
        })
    </script>
@endpush



