@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.pages.update' , $page) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Page" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.pages.index') }}">Pages</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="tours">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'tours-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'tours-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.title"
                                                                 :value="$page->translateOrNew($localKey)->title"
                                                                 name="{{$localKey}}[title]" id="{{$localKey}}-title"
                                                                 label-title="Title"/>

                                    <x-dashboard.form.input-textarea error-key="{{$localKey}}.short_description"
                                                                     name="{{$localKey}}[short_description]"
                                                                     :value="$page->translateOrNew($localKey)->short_description"
                                                                     id="{{$localKey}}-short_description" label-title="Short Description"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.content"
                                                                   name="{{$localKey}}[content]"
                                                                   :value="$page->translateOrNew($localKey)->content"
                                                                   id="{{$localKey}}-content" label-title="Content"/>


                                    @forelse($page->metas as $meta)

                                        <x-dashboard.form.input-textarea error-key="meta.{{$localKey}}.{{$meta->meta_key}}"
                                                                       name="meta[{{$meta->meta_key}}][{{$localKey}}][meta_value]"
                                                                       :value="$meta->translateOrNew($localKey)->meta_value"
                                                                       id="meta-{{$localKey}}-{{$meta->meta_key}}"
                                                                        :label-title="str($meta->meta_key)->headline()"/>
                                    @empty
                                        <br>
                                    @endforelse
                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text
                            :readonly="in_array($page->key, \App\Models\Page::MAIN_PAGES)"
                            error-key="key" name="key" :value="$page->key" id="key"
                                                     label-title="Key"/>

                        <x-dashboard.form.media title="Add Banner Image" name="banner" :images="$page->banner"/>


                        <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                :images="$page->gallery"
                                                name="gallery[]"/>

                        <x-dashboard.form.media title="Add Mobile Gallery" :multiple="true"
                                                :images="$page->mobile_gallery"
                                                name="mobile_gallery[]"/>


                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <!--Start SEO-->
                <x-dashboard.form.seo-form :seo="$page->seo"/>
                <!--End SEO-->


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>

    <x-dashboard.partials.resource-translation model="Page" :id="$page->id"/>
@endsection
