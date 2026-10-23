<div class="card tab2-card">
    <div class="card-body needs-validation">
        <x-dashboard.form.language-multi-tab-card tab-id="seo" title="SEO">
            @foreach(config('translatable.supported_locales') as $localKey => $local)
                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                     id="{{ 'seo-'.$localKey }}" role="tabpanel"
                     aria-labelledby="{{  'seo-'.$localKey }}-tab">
                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->meta_title"
                                                 error-key="seo.{{$localKey}}.meta_title"
                                                 name="seo[{{$localKey}}][meta_title]" id="seo.{{$localKey}}-meta-title"
                                                 label-title="Meta Title"/>
                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->meta_description"
                                                 error-key="seo.{{$localKey}}.meta_description"
                                                 name="seo[{{$localKey}}][meta_description]"
                                                 id="seo.{{$localKey}}-meta-description"
                                                 label-title="Meta Description"/>
                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->meta_keywords"
                                                 error-key="seo.{{$localKey}}.meta_keywords"
                                                 name="seo[{{$localKey}}][meta_keywords]"
                                                 id="seo.{{$localKey}}-meta-keywords" label-title="Meta Keywords"
                                                 class="tags-input"/>
                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->og_title"
                                                 error-key="seo.{{$localKey}}.og_title"
                                                 name="seo[{{$localKey}}][og_title]"
                                                 id="seo.{{$localKey}}-og-title"
                                                 label-title="OpenGraph Title"/>
                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->og_description"
                                                 error-key="seo.{{$localKey}}.og_description"
                                                 name="seo[{{$localKey}}][og_description]"
                                                 id="seo.{{$localKey}}-og-description"
                                                 label-title="OpenGraph Description"/>


                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->twitter_title"
                                                 error-key="seo.{{$localKey}}.twitter_title"
                                                 name="seo[{{$localKey}}][twitter_title]"
                                                 id="seo.{{$localKey}}-twitter-title"
                                                 label-title="Twitter Title"/>

                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->twitter_description"
                                                 error-key="seo.{{$localKey}}.twitter_description"
                                                 name="seo[{{$localKey}}][twitter_description]"
                                                 id="seo.{{$localKey}}-twitter-description"
                                                 label-title="Twitter Description"/>

                    <x-dashboard.form.input-text :value="$seo->translateOrNew($localKey)?->canonical"
                                                 error-key="seo.{{$localKey}}.canonical"
                                                 name="seo[{{$localKey}}][canonical]"
                                                 id="seo.{{$localKey}}-canonical" label-title="Canonical" />


                    <div class="form-group row">
                        <label for="structure-schema" class="col-xl-3 col-md-4">
                            Structure Schema
                        </label>
                        <div class="col-xl-8 col-md-7">
                            <textarea id="structure-schema-{{$localKey}}-field"  style="display: none" aria-label="S" name="seo[{{$localKey}}][structure_schema]">{{$seo->translateOrNew($localKey)?->structure_schema}}</textarea>
                            <div id="structure-schema-{{$localKey}}" style="width: 100%;height: 250px">{{$seo->translateOrNew($localKey)?->structure_schema}}</div>
                        </div>
                    </div>

                </div>
            @endforeach


        </x-dashboard.form.language-multi-tab-card>


        <x-dashboard.form.input-text :value="$seo->viewport"
                                     error-key="seo.viewport"
                                     name="seo[viewport]" id="seo-viewport"
                                     label-title="View Port"/>

        <x-dashboard.form.input-text :value="$seo->robots"
                                     error-key="seo.robots"
                                     name="seo[robots]" id="seo-robots"
                                     label-title="Robots"/>

        <x-dashboard.form.input-text :value="$seo->og_type"
                                     error-key="seo.og_type"
                                     name="seo[og_type]" id="seo-og-type"
                                     label-title="Open Graph Type"/>

        <x-dashboard.form.input-text :value="$seo->twitter_card"
                                     error-key="seo.twitter_card"
                                     name="seo[twitter_card]" id="seo-twitter-card"
                                     label-title="Twitter Card"/>

        <x-dashboard.form.input-text :value="$seo->twitter_creator"
                                     error-key="seo.twitter_creator"
                                     name="seo[twitter_creator]" id="seo-twitter-creator"
                                     label-title="Twitter Creator"/>

        <x-dashboard.form.media
            name="seo[og_image]"
            title="Add Open Graph Image"
            :images="$seo->og_image"
        ></x-dashboard.form.media>

        <x-dashboard.form.media
            name="seo[twitter_image]"
            title="Add Twitter Image"
            :images="$seo->twitter_image"
        ></x-dashboard.form.media>

        <x-dashboard.form.submit-button/>
    </div>
</div>

@push('js')
    <script>
        const init_json_editor = (selector) => {
            console.log(selector)
            let editor = ace.edit(selector, {
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true
            });
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/json");
            try {
                let content = JSON.parse(editor.getValue())
                editor.getSession().setValue(JSON.stringify(content, null, 2));
            } catch (e) {
                editor.getSession().setValue(editor.getValue());
            }
            editor.getSession().on('change', function () {
                $('#'+selector+'-field').val(editor.getValue())
            });
        }
        @foreach(config('translatable.locales') as $loc)
                    init_json_editor("structure-schema-{{$loc}}")
        @endforeach
    </script>
@endpush

