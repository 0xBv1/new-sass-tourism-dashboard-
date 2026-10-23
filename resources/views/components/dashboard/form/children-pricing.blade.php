@props([
    'children' => []
])
<div id="children_pricing">
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center" colspan="6">Children Pricing</th>
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
                    <button @click.prevent="addChildPricing()" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>

            <tr v-for="(child, index) in children_pricing">
                <td>
                    <input required class="form-control" type="text" v-model="child.age_range"
                           :name="`children[${index}][age_range]`" placeholder="e.g., 0-2" aria-label="Age Range">
                </td>
                <td>
                    <input required class="form-control" type="number" v-model="child.max_count"
                           :name="`children[${index}][max_count]`" placeholder="Max Count" aria-label="Max Count">
                </td>
                <td>
                    <input required class="form-control" type="number" step="0.01" v-model="child.price"
                           :name="`children[${index}][price]`" placeholder="Price" aria-label="Price">
                </td>
                <td>
                    <input class="form-control" type="text" v-model="child.description"
                           :name="`children[${index}][description]`" placeholder="Description" aria-label="Description">
                </td>
                <td>
                    <input type="checkbox" v-model="child.enabled" :name="`children[${index}][enabled]`" value="1">
                    <input type="hidden" :name="`children[${index}][enabled]`" :value="child.enabled ? 1 : 0">
                </td>
                <td>
                    <button @click.prevent="removeChildPricing(index)"
                            class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

            <tr v-if="!children_pricing.length">
                <td colspan="6">No children pricing configured</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        const el = new Vue({
            el: '#children_pricing',
            data() {
                return {
                    children_pricing: {!! json_encode($children) !!}
                }
            },
            methods: {
                removeChildPricing(index) {
                    this.children_pricing.splice(index, 1)
                },
                addChildPricing() {
                    this.children_pricing.push({
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



