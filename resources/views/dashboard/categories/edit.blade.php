@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.categories.update' , $category) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Category" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.categories.index') }}">Categories</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="categories">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'categories-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'categories-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.title"
                                                                 name="{{$localKey}}[title]"
                                                                 :value="$category->translateOrNew($localKey)->title"
                                                                 id="{{$localKey}}-title" label-title="Title"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.description"
                                                                   name="{{$localKey}}[description]"
                                                                   :value="$category->translateOrNew($localKey)->description"
                                                                   id="{{$localKey}}-description"
                                                                   label-title="Description"/>



                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card tab2-card">
                    <div class="card-body">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['featured', 'media']"
                            tab-id="featured-media">
                            <div class="tab-pane fade active show"
                                 id="{{ 'featured-media-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-0' }}-tab">

                                <x-dashboard.form.input-text error-key="slug"
                                                             name="slug" id="slug"
                                                             :value="$category->slug"
                                                             label-title="Slug"/>

                                <x-dashboard.form.input-text error-key="display_order"
                                                             name="display_order"
                                                             :value="$category->display_order"
                                                             id="display_order"
                                                               label-title="Display Order"/>

                                <x-dashboard.form.input-select
                                    name="parent_id"
                                    :options="$parent_categories"
                                    track-by="id"
                                    :value="$category->parent_id"
                                    option-lable="title"
                                    label-title="Parent Category"
                                    id="parent_id"
                                    error-key="parent_id"/>

                                <x-dashboard.form.input-checkbox resource-name="Category"
                                                                 error-key="enabled"
                                                                 :value="$category->enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>

                                <x-dashboard.form.input-checkbox resource-name="Category"
                                                                 resource-desc="Set Featured"
                                                                 :value="$category->featured"
                                                                 error-key="featured" name="featured" id="featured"
                                                                 label-title="Featured"/>

                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                <x-dashboard.form.media title="Add Banner Image"
                                                        :images="$category->banner"
                                                        name="banner"/>

                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="$category->featured_image"
                                                        name="featured_image"/>

                                <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                        :images="$category->gallery"
                                                        name="gallery[]"/>
                            </div>
                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <!--Start SEO-->
                <x-dashboard.form.seo-form :seo="$category->seo"/>
                <!--End SEO-->

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
    <x-dashboard.partials.resource-translation model="Category" :id="$category->id"/>
@endsection
