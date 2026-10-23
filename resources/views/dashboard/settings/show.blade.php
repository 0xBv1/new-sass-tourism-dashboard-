@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.settings.update' ) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Settings" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.settings.show') }}">Settings</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->


        <!-- Container-fluid starts-->
        <div class="container-fluid" id="settings-app">
            <div class="row">
                <x-dashboard.partials.message-alert/>
                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['basic','notifications', 'social-links', 'company-team', 'tiny-editor', 'debugging']"
                            tab-id="settings">

                            <div class="tab-pane fade active show"
                                 id="{{ 'settings-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-0' }}-tab">

                                <x-dashboard.form.input-text error-key="site_title"
                                                             required
                                                             :value="old('site_title.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::SITE_TITLE->value)?->option_value[0] ?? '')"
                                                             name="site_title[]" id="site_title"
                                                             label-title="Site Title"/>

                                <x-dashboard.form.input-text error-key="{{ \App\Enums\SettingKey::COMPANY_LOCATION_URL->value }}"
                                                             required
                                                             :value="old(\App\Enums\SettingKey::COMPANY_LOCATION_URL->value. '.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::COMPANY_LOCATION_URL->value)?->option_value[0] ?? '')"
                                                             name="{{\App\Enums\SettingKey::COMPANY_LOCATION_URL->value}}[]" id="{{\App\Enums\SettingKey::COMPANY_LOCATION_URL->value}}"
                                                             label-title="Company Location Url"/>



                                <x-dashboard.form.input-text error-key="{{ \App\Enums\SettingKey::CONTACT_PHONE_NUMBER->value }}"
                                                             required
                                                             :value="old(\App\Enums\SettingKey::CONTACT_PHONE_NUMBER->value .'.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::CONTACT_PHONE_NUMBER->value)?->option_value[0] ?? '')"
                                                             name="{{ \App\Enums\SettingKey::CONTACT_PHONE_NUMBER->value }}[]" id="{{ \App\Enums\SettingKey::CONTACT_PHONE_NUMBER->value }}"
                                                             label-title="Contact Phone Number"/>

                                <x-dashboard.form.input-text error-key="{{ \App\Enums\SettingKey::EMAIL_ADDRESS->value }}"
                                                             required
                                                             :value="old(\App\Enums\SettingKey::EMAIL_ADDRESS->value .'.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::EMAIL_ADDRESS->value)?->option_value[0] ?? '')"
                                                             name="{{ \App\Enums\SettingKey::EMAIL_ADDRESS->value }}[]" id="{{ \App\Enums\SettingKey::EMAIL_ADDRESS->value }}"
                                                             label-title="Email Address"/>

                                <x-dashboard.form.input-text error-key="{{ \App\Enums\SettingKey::ADDRESS->value }}"
                                                             required
                                                             :value="old(\App\Enums\SettingKey::ADDRESS->value .'.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::ADDRESS->value)?->option_value[0] ?? '')"
                                                             name="{{ \App\Enums\SettingKey::ADDRESS->value }}[]" id="{{ \App\Enums\SettingKey::ADDRESS->value }}"
                                                             label-title="Address"/>

                                <x-dashboard.form.media title="Choose Logo"
                                                        :images="old('logo.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::LOGO->value)?->option_value[0] ?? '')"
                                                        name="logo[]"/>
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'settings-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-1' }}-tab">
                                <a href="javascript:;" @click="addNotificationEmail()"
                                   class="text-center mb-4 btn btn-outline-primary w-100">
                                    <i class="fa fa-plus"></i> Add Email
                                </a>

                                <div v-for="(email,index) in notification_emails" :key="'email-' + index" class="row">
                                    <div class="form-group row">
                                        <label :for="'price-group-car-type-'+index" class="col-xl-3 col-md-4">Email
                                            <i class="fa fa-trash text-danger"
                                               @click="removeEmail(index)" style="cursor: pointer"></i>
                                        </label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'notification-email-'+index"
                                                   required
                                                   type="email" name="notification_emails[]"
                                                   :value="email"
                                                   placeholder="example@gmail.com">
                                        </div>
                                    </div>
                                </div> {{-- End Vue loop --}}
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'settings-2' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-2' }}-tab">
                                <div class="permission-block">
                                    <a href="javascript:;" @click="addSocialLink()"
                                       class="text-center mb-4 btn btn-outline-primary w-100">
                                        <i class="fa fa-plus"></i> Add Link
                                    </a>

                                    <div v-for="(link,idx) in social_media_links" :key="'link-' + idx" class="row">
                                        <div class="form-group row">
                                            <div class="col-xl-12 col-xl-12">
                                                <div class="input-group mb-3">
                                                    <select aria-label="Type" class="dropdown-toggle"
                                                            :name="'social_links['+idx+'][type]'" v-model="link.type"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <option v-for="social_media_type in social_media_types"
                                                                :value="social_media_type.value">@{{
                                                            social_media_type.name }}
                                                        </option>
                                                    </select>
                                                    <input type="text" class="form-control"
                                                           :name="'social_links['+idx+'][url]'" v-model="link.url"
                                                           aria-label="Text input with dropdown button">
                                                    <button class="btn btn-outline-primary" @click.prevent="removeSocialLink(idx)" type="button" id="button-addon2"><i class="fa fa-trash"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                    </div> {{-- End Vue loop --}}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'settings-3' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-3' }}-tab">
                                <div class="permission-block">
                                    <a href="javascript:;" @click="addTeamMember()"
                                       class="text-center mb-4 btn btn-outline-primary w-100">
                                        <i class="fa fa-plus"></i> Add Team Member
                                    </a>

                                    <div v-for="(member, idx) in company_team" :key="'company_team-' + idx" class="row">

                                        <div class="form-group row">
                                            <label :for="'team-member-name-'+idx" class="col-xl-3 col-md-4">Member Name
                                                <i class="fa fa-trash text-danger"
                                                   @click="removeTeamMember(idx)" style="cursor: pointer"></i>
                                            </label>
                                            <div class="col-xl-8 col-xl-9">
                                                <input class="form-control" :id="'team-member-'+idx"
                                                       required
                                                       type="text"
                                                       :name="'company_team['+idx+'][name]'"
                                                       :value="member.name"
                                                       placeholder="Amr Badawy">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label :for="'team-member-position-'+idx" class="col-xl-3 col-md-4">Member Position</label>
                                            <div class="col-xl-8 col-xl-9">
                                                <input class="form-control" :id="'team-member-position-'+idx"
                                                       required
                                                       type="text"
                                                       :value="member.position"
                                                       :name="'company_team['+idx+'][position]'"
                                                       placeholder="Owner">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label :for="'team-member-position-'+idx" class="col-xl-3 col-md-4">Member Photo Url</label>
                                            <div class="col-xl-8 col-xl-9">
                                                <input class="form-control" :id="'team-member-image-'+idx"
                                                       required
                                                       type="url"
                                                       :value="member.image"
                                                       :name="'company_team['+idx+'][image]'"
                                                       placeholder="{{ asset('/storage/media/images/team/owner.webp') }}">
                                            </div>
                                        </div>


                                        <hr v-if="idx < company_team.length-1">

                                    </div> {{-- End Vue loop --}}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'settings-4' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-3' }}-tab">

                                <x-dashboard.form.input-text error-key="{{\App\Enums\SettingKey::TINY_EDITOR->value}}"
                                                             :value="old(\App\Enums\SettingKey::TINY_EDITOR->value. '.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::TINY_EDITOR->value)?->option_value[0] ?? '')"
                                                             name="{{\App\Enums\SettingKey::TINY_EDITOR->value}}[]" id="{{\App\Enums\SettingKey::TINY_EDITOR->value}}"
                                                             label-title="Tiny Editor Key"/>

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'settings-5' }}" role="tabpanel"
                                 aria-labelledby="{{ 'settings-5' }}-tab">

                                <x-dashboard.form.input-checkbox error-key="{{\App\Enums\SettingKey::QUEUE_MONITOR_UI->value}}"
                                                             :value="old(\App\Enums\SettingKey::QUEUE_MONITOR_UI->value. '.0',
                                                              $settings->firstWhere('option_key', \App\Enums\SettingKey::QUEUE_MONITOR_UI->value)?->option_value[0] ?? '')"
                                                             name="{{\App\Enums\SettingKey::QUEUE_MONITOR_UI->value}}[]" id="{{\App\Enums\SettingKey::QUEUE_MONITOR_UI->value}}"
                                                             label-title="Queue Monitor UI" />

                            </div>

                        </x-dashboard.form.multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <!--Start SEO-->
                <x-dashboard.form.seo-form :seo="$seo"/>
                <!--End SEO-->

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection

@push('js-upper')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        new Vue({
            el: "#settings-app",
            data() {
                return {
                    social_media_types: [
                        {name: 'Facebook', value: 'facebook'},
                        {name: 'Twitter', value: 'twitter'},
                        {name: 'Google Plus', value: 'google-plus'},
                        {name: 'Instagram', value: 'instagram'},
                        {name: 'Pinterest', value: 'pinterest'},
                        {name: 'Youtube', value: 'youtube'},
                        {name: 'Tripadvisor', value: 'tripadvisor'},
                        {name: 'Linked In', value: 'linked-in'},
                    ],

                    company_team: @json(old(\App\Enums\SettingKey::COMPANY_TEAM->value,
                                           $settings->firstWhere('option_key', \App\Enums\SettingKey::COMPANY_TEAM->value)?->option_value ?? [])),

                    notification_emails: @json(old(\App\Enums\SettingKey::NOTIFICATION_EMAILS->value,
                                           $settings->firstWhere('option_key', \App\Enums\SettingKey::NOTIFICATION_EMAILS->value)?->option_value ?? [])),

                    social_media_links: @json(old(\App\Enums\SettingKey::SOCIAL_LINKS->value,
                                           $settings->firstWhere('option_key', \App\Enums\SettingKey::SOCIAL_LINKS->value)?->option_value ?? []))
                }
            },
            mounted() {
            },
            methods: {
                addNotificationEmail() {
                    this.notification_emails.push('')
                },
                removeEmail(index) {
                    this.notification_emails.splice(index, 1);
                },
                addSocialLink() {
                    this.social_media_links.push({
                        type: this.social_media_types[0].value,
                        url:''
                    })
                },
                removeSocialLink(index) {
                    this.social_media_links.splice(index, 1);
                },
                addTeamMember() {
                    this.company_team.push({
                        name: '',
                        image: '',
                        position:''
                    })
                },
                removeTeamMember(index) {
                    this.company_team.splice(index, 1);
                }
            }
        });
    </script>
@endpush
