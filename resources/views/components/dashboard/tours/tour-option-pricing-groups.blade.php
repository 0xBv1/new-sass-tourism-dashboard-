@props([
    'tourOption' => new \App\Models\TourOption()
])
<div id="pricing_groups">
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center" colspan="5">Pricing Groups</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 20%">From</td>
                <td style="width: 20%">To</td>
                <td style="width: 25%">Adult Price</td>
                <td style="width: 25%">Child Price</td>
                <td style="width: 10%">
                    <button @click.prevent="addPriceGroup()" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>

            <tr v-for="(group, index) in pricing_groups">
                <td>
                    <input required class="form-control" type="number" v-model="group.from"
                           :name="`pricing_groups[${index}][from]`" placeholder="From" aria-label="From">
                </td>
                <td>
                    <input required class="form-control" type="number" v-model="group.to"
                           :name="`pricing_groups[${index}][to]`" placeholder="To" aria-label="To">
                </td>

                <td>
                    <input required class="form-control" type="number" v-model="group.price"
                           :name="`pricing_groups[${index}][price]`" placeholder="Adult Price" aria-label="Adult Price">
                </td>
                <td>
                    <input required class="form-control" type="number" v-model="group.child_price"
                           :name="`pricing_groups[${index}][child_price]`" placeholder="Child Price"
                           aria-label="Child Price">
                </td>
                <td>
                    <button @click.prevent="removePriceGroup(index)"
                            class="btn btn-sm btn-outline-primary"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

            <tr v-if="!pricing_groups.length">
                <td colspan="5">Empty Pricing Groups</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>



@push('js')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        const el = new Vue({
            el: '#pricing_groups',
            data() {
                return {
                    pricing_groups: {!! $tourOption->pricing_groups->toJson() !!}
                }
            },
            methods: {
                removePriceGroup(index) {
                    this.pricing_groups.splice(index, 1)
                },
                addPriceGroup() {
                    this.pricing_groups.push({
                        from: null,
                        to: null,
                        adult_price: null,
                        child_price: null,
                    })
                },
            }
        })
    </script>
@endpush
