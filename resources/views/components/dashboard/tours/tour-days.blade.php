@props([
    'tour' => new \App\Models\Tour(),
    'locale' => config('app.locale')
])
@php
    // Get days for this locale
    $days = $tour->days ?? collect([]);
    $daysData = $days->map(function($day) use ($locale) {
        return [
            'title' => $day->translateOrNew($locale)->title ?? '',
            'description' => $day->translateOrNew($locale)->description ?? '',
        ];
    })->toArray();
    
    // Ensure at least one empty day for new tours
    if (empty($daysData)) {
        $daysData = [['title' => '', 'description' => '']];
    }
@endphp

<div id="tour-days-{{ $locale }}">
    <div class="row mb-4">
        <div class="col-12">
            <button @click.prevent="addDay()" class="btn btn-danger btn-lg w-100 text-white" style="font-weight: 600;">
                <i class="fa fa-plus"></i> ADD DAY
            </button>
        </div>
    </div>

    <template v-for="(day, index) in days" :key="index">
        <div class="row mb-4" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 60px;">
            <div class="col-12 mb-3">
                <label class="form-label" style="font-weight: 500; margin-bottom: 8px;">Title</label>
                <input 
                     
                    class="form-control" 
                    type="text" 
                    v-model="day.title"
                    :name="'days[' + index + '][' + locale + '][title]'" 
                    :id="'days-' + index + '-' + locale + '-title'"
                    placeholder="Day Title" 
                    aria-label="Title"
                    style="padding: 10px 15px; font-size: 14px;">
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" style="font-weight: 500; margin-bottom: 8px;">Description</label>
                <textarea 
                     
                    class="form-control code-editor-tiny" 
                    :name="'days[' + index + '][' + locale + '][description]'" 
                    :id="'days-' + index + '-' + locale + '-description'"
                    rows="5"
                    placeholder="Start typing..." 
                    aria-label="Description">@{{ day.description }}</textarea>
            </div>

            <div class="col-12" v-if="days.length > 1">
                <button @click.prevent="removeDay(index)" 
                        class="btn btn-secondary btn-lg w-100 text-white" 
                        style="font-weight: 500; background-color: #6c757d; border-color: #6c757d;">
                    <i class="fa fa-trash"></i> REMOVE DAY
                </button>
            </div>
        </div>
    </template>

    <div v-if="!days.length" class="alert alert-info text-center">
        No days added yet. Click "ADD DAY" to get started.
    </div>
</div>

@push('js')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        (function() {
            const locale = '{{ $locale }}';
            const daysData = {!! json_encode($daysData) !!};
            
            const vueInstance = new Vue({
                el: '#tour-days-' + locale,
                data() {
                    return {
                        days: daysData,
                        locale: locale
                    }
                },
                methods: {
                    removeDay(index) {
                        this.days.splice(index, 1);
                        // Reinitialize editors after removal and update indices
                        this.$nextTick(() => {
                            this.initializeEditors();
                            this.updateFieldIndices();
                        });
                    },
                    updateFieldIndices() {
                        // Update all field names and IDs to have sequential indices
                        const inputs = document.querySelectorAll(`#tour-days-${this.locale} input[name*="[title]"], #tour-days-${this.locale} textarea[name*="[description]"]`);
                        inputs.forEach((input, newIndex) => {
                            const name = input.getAttribute('name');
                            const id = input.getAttribute('id');
                            
                            // Extract locale from current name
                            const localeMatch = name.match(/\[(\w+)\]\[title\]|\[(\w+)\]\[description\]/);
                            const currentLocale = localeMatch ? (localeMatch[1] || localeMatch[2]) : this.locale;
                            
                            // Update name with new sequential index
                            if (name.includes('[title]')) {
                                input.setAttribute('name', `days[${newIndex}][${currentLocale}][title]`);
                                input.setAttribute('id', `days-${newIndex}-${currentLocale}-title`);
                            } else if (name.includes('[description]')) {
                                input.setAttribute('name', `days[${newIndex}][${currentLocale}][description]`);
                                input.setAttribute('id', `days-${newIndex}-${currentLocale}-description`);
                            }
                        });
                    },
                    addDay() {
                        this.days.push({
                            title: '',
                            description: '',
                        });
                        // Initialize editor for new day
                        this.$nextTick(() => {
                            this.initializeEditors();
                        });
                    },
                    initializeEditors() {
                        // Wait a bit for DOM to update
                        setTimeout(() => {
                            const self = this;
                            // Find all new textareas that need editor initialization
                            const textareas = document.querySelectorAll(`#tour-days-${locale} textarea.code-editor-tiny:not(.easy-editor-initialized)`);
                            textareas.forEach((textarea) => {
                                if (typeof EasyEditor !== 'undefined') {
                                    try {
                                        // Get day index from textarea id
                                        const match = textarea.id.match(/days-(\d+)-/);
                                        if (match) {
                                            const dayIndex = parseInt(match[1]);
                                            // Set initial value if exists
                                            if (self.days[dayIndex] && self.days[dayIndex].description) {
                                                textarea.value = self.days[dayIndex].description;
                                            }
                                        }
                                        
                                        // Remove any existing editor container
                                        let nextSibling = textarea.nextElementSibling;
                                        if (nextSibling && nextSibling.classList.contains('easy-editor-container')) {
                                            nextSibling.remove();
                                        }
                                        
                                        // Initialize new editor
                                        new EasyEditor('#' + textarea.id);
                                        textarea.classList.add('easy-editor-initialized');
                                    } catch (e) {
                                        console.warn('Failed to initialize editor:', e);
                                    }
                                }
                            });
                        }, 100);
                    }
                },
                mounted() {
                    // Initialize editors for existing days
                    this.initializeEditors();
                }
            });
        })();
    </script>
@endpush
