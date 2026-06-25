@extends('backend.master')

@section('title', 'General Settings')
@section('page-class', 'page-modern--no-page-title')

@section('content')
@php
    $activeTab = request('active-tab', 'website-info');
@endphp

<div class="row settings-layout">
    <x-settings-nav :active-tab="$activeTab" />

    <div class="col-12 col-lg-9">
        <div class="content-card settings-panel">
            <div class="tab-content settings-panel__content" id="settings-tab-content">
                @can('website_settings')
                <div class="tab-pane fade {{ $activeTab === 'website-info' ? 'active show' : '' }}"
                    id="settings-pane-website-info"
                    role="tabpanel"
                    aria-labelledby="settings-tab-website-info">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.info.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Website Info" icon="fas fa-desktop" />
                        <x-form-section>
                            <x-form-field label="Website Title" name="site_name" required col="12">
                                <input class="form-control" id="site_name" name="site_name" type="text"
                                    value="{{ readConfig('site_name') }}" placeholder="Enter site title" required>
                            </x-form-field>
                            <x-form-field label="Meta Description" name="meta_description" col="12">
                                <textarea class="form-control" id="meta_description" rows="3" name="meta_description"
                                    placeholder="Enter meta description">{{ readConfig('meta_description') }}</textarea>
                            </x-form-field>
                            <x-form-field label="Meta Keywords" name="meta_keywords" col="12">
                                <textarea class="form-control" id="meta_keywords" rows="3" name="meta_keywords"
                                    placeholder="Enter keywords">{{ readConfig('meta_keywords') }}</textarea>
                            </x-form-field>
                            <x-form-field label="Website URL" name="site_url" col="12">
                                <input class="form-control" id="site_url" name="site_url" type="url"
                                    value="{{ readConfig('site_url') }}" placeholder="https://example.com">
                            </x-form-field>
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('contact_settings')
                <div class="tab-pane fade {{ $activeTab === 'contacts' ? 'active show' : '' }}"
                    id="settings-pane-contacts"
                    role="tabpanel"
                    aria-labelledby="settings-tab-contacts">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.contacts.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Contacts" icon="fas fa-address-book" />
                        <x-form-section>
                            <x-form-field label="Address" name="contact_address" col="12">
                                <input class="form-control" id="contact_address" name="contact_address" type="text"
                                    value="{{ readConfig('contact_address') }}" placeholder="Enter business address">
                            </x-form-field>
                            <x-form-field label="Phone" name="contact_phone" col="md-6">
                                <input class="form-control" id="contact_phone" name="contact_phone" type="tel"
                                    value="{{ readConfig('contact_phone') }}" placeholder="Phone number">
                            </x-form-field>
                            <x-form-field label="Fax" name="contact_fax" col="md-6">
                                <input class="form-control" id="contact_fax" name="contact_fax" type="tel"
                                    value="{{ readConfig('contact_fax') }}" placeholder="Fax number">
                            </x-form-field>
                            <x-form-field label="Mobile" name="contact_mobile" col="md-6">
                                <input class="form-control" id="contact_mobile" name="contact_mobile" type="tel"
                                    value="{{ readConfig('contact_mobile') }}" placeholder="Mobile number">
                            </x-form-field>
                            <x-form-field label="Email" name="contact_email" col="md-6">
                                <input class="form-control" id="contact_email" name="contact_email" type="email"
                                    value="{{ readConfig('contact_email') }}" placeholder="contact@example.com">
                            </x-form-field>
                            <x-form-field label="Working Time" name="working_hour" col="12"
                                hint="Example: Sunday to Thursday 08:00 AM to 05:00 PM">
                                <input class="form-control" id="working_hour" name="working_hour" type="text"
                                    value="{{ readConfig('working_hour') }}" placeholder="Enter working hours">
                            </x-form-field>
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('socials_settings')
                <div class="tab-pane fade {{ $activeTab === 'social-links' ? 'active show' : '' }}"
                    id="settings-pane-social-links"
                    role="tabpanel"
                    aria-labelledby="settings-tab-social-links">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.social.link.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Social Links" icon="fas fa-share-alt" />
                        <div class="settings-social-grid">
                            @foreach ([
                                ['name' => 'facebook_link', 'label' => 'Facebook', 'icon' => 'fab fa-facebook', 'brand' => 'facebook'],
                                ['name' => 'twitter_link', 'label' => 'Twitter', 'icon' => 'fab fa-twitter', 'brand' => 'twitter'],
                                ['name' => 'linkedin_link', 'label' => 'LinkedIn', 'icon' => 'fab fa-linkedin', 'brand' => 'linkedin'],
                                ['name' => 'youtube_link', 'label' => 'YouTube', 'icon' => 'fab fa-youtube', 'brand' => 'youtube'],
                                ['name' => 'instagram_link', 'label' => 'Instagram', 'icon' => 'fab fa-instagram', 'brand' => 'instagram'],
                                ['name' => 'pinterest_link', 'label' => 'Pinterest', 'icon' => 'fab fa-pinterest', 'brand' => 'pinterest'],
                                ['name' => 'tumblr_link', 'label' => 'Tumblr', 'icon' => 'fab fa-tumblr', 'brand' => 'tumblr'],
                                ['name' => 'snapchat_link', 'label' => 'Snapchat', 'icon' => 'fab fa-snapchat', 'brand' => 'snapchat'],
                                ['name' => 'whatsapp_link', 'label' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'brand' => 'whatsapp'],
                            ] as $social)
                            <x-settings-social-field
                                :name="$social['name']"
                                :label="$social['label']"
                                :icon="$social['icon']"
                                :brand="$social['brand']"
                                :value="readConfig($social['name'])"
                            />
                            @endforeach
                        </div>
                    </form>
                </div>
                @endcan

                @can('style_settings')
                <div class="tab-pane fade {{ $activeTab === 'style-settings' ? 'active show' : '' }}"
                    id="settings-pane-style-settings"
                    role="tabpanel"
                    aria-labelledby="settings-tab-style-settings">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.style.settings.update') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <x-settings-panel-header title="Style Settings" icon="fas fa-swatchbook" />
                        <x-form-section title="Brand Assets">
                            <x-form-field label="Site Logo" col="12"
                                hint="Recommended 260×60 px — PNG, JPG, JPEG, GIF, or SVG">
                                <x-form-image-upload
                                    name="site_logo"
                                    input-id="siteLogoInput"
                                    preview-id="siteLogoPreview"
                                    container-id="siteLogoContainer"
                                    preview-container-id="siteLogoPreviewContainer"
                                    placeholder="Upload site logo"
                                    :preview-src="assetImage(readConfig('site_logo'))"
                                />
                            </x-form-field>
                            <x-form-field label="Favicon" col="md-6"
                                hint="Recommended 32×32 px — PNG, JPG, JPEG, GIF, or SVG">
                                <x-form-image-upload
                                    name="favicon_icon"
                                    input-id="faviconInput"
                                    preview-id="faviconPreview"
                                    container-id="faviconContainer"
                                    preview-container-id="faviconPreviewContainer"
                                    placeholder="Upload favicon"
                                    :preview-src="assetImage(readConfig('favicon_icon'))"
                                />
                            </x-form-field>
                            <x-form-field label="Apple Touch Icon" col="md-6"
                                hint="Recommended 180×180 px — PNG, JPG, JPEG, GIF, or SVG">
                                <x-form-image-upload
                                    name="favicon_icon_apple"
                                    input-id="appleIconInput"
                                    preview-id="appleIconPreview"
                                    container-id="appleIconContainer"
                                    preview-container-id="appleIconPreviewContainer"
                                    placeholder="Upload Apple icon"
                                    :preview-src="assetImage(readConfig('favicon_icon_apple'))"
                                />
                            </x-form-field>
                        </x-form-section>
                        <x-form-section title="Newsletter">
                            <x-settings-radio-group
                                name="newsletter_subscribe"
                                label="Newsletter Subscribe"
                                :options="[
                                    ['value' => '1', 'label' => 'Active', 'checked' => readConfig('newsletter_subscribe') == 1],
                                    ['value' => '0', 'label' => 'Not Active', 'checked' => readConfig('newsletter_subscribe') == 0],
                                ]"
                            />
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('custom_settings')
                <div class="tab-pane fade {{ $activeTab === 'custom-css' ? 'active show' : '' }}"
                    id="settings-pane-custom-css"
                    role="tabpanel"
                    aria-labelledby="settings-tab-custom-css">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.custom.css.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Custom CSS" icon="fas fa-code" />
                        <x-form-section>
                            <x-form-field label="Custom Stylesheet" name="custom_css" col="12"
                                hint="Add custom CSS rules. Changes apply site-wide.">
                                <textarea class="form-control settings-code-editor" id="custom_css" rows="18"
                                    name="custom_css" placeholder="/* Custom CSS */">{{ readConfig('custom_css') }}</textarea>
                            </x-form-field>
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('notification_settings')
                <div class="tab-pane fade {{ $activeTab === 'notification-settings' ? 'active show' : '' }}"
                    id="settings-pane-notification-settings"
                    role="tabpanel"
                    aria-labelledby="settings-tab-notification-settings">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.notification.settings.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Notification Settings" icon="fas fa-envelope" />
                        <x-form-section>
                            <x-form-field label="Website Notification Email" name="notify_email_address" col="12">
                                <input class="form-control" id="notify_email_address" name="notify_email_address" type="email"
                                    value="{{ readConfig('notify_email_address') }}" placeholder="notifications@example.com">
                            </x-form-field>
                            <x-settings-radio-group
                                name="notify_messages_status"
                                label="Send email on new contact messages"
                                :options="[
                                    ['value' => '1', 'label' => 'Yes', 'checked' => readConfig('notify_messages_status') == 1],
                                    ['value' => '0', 'label' => 'No', 'checked' => readConfig('notify_messages_status') == 0],
                                ]"
                            />
                            <x-settings-radio-group
                                name="notify_comments_status"
                                label="Send email on new comments"
                                :options="[
                                    ['value' => '1', 'label' => 'Yes', 'checked' => readConfig('notify_comments_status') == 1],
                                    ['value' => '0', 'label' => 'No', 'checked' => readConfig('notify_comments_status') == 0],
                                ]"
                            />
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('website_status_settings')
                <div class="tab-pane fade {{ $activeTab === 'website-status' ? 'active show' : '' }}"
                    id="settings-pane-website-status"
                    role="tabpanel"
                    aria-labelledby="settings-tab-website-status">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.status.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Website Status" icon="fas fa-power-off" />
                        <x-form-section>
                            <x-settings-radio-group
                                name="is_live"
                                label="Website Status"
                                :options="[
                                    ['value' => '1', 'label' => 'Active', 'checked' => readConfig('is_live') == 1],
                                    ['value' => '0', 'label' => 'Maintenance', 'checked' => readConfig('is_live') == 0],
                                ]"
                            />
                            <div id="close_msg_div" class="{{ readConfig('is_live') == 1 ? 'd-none' : '' }}">
                                <x-form-field label="Close Message" name="close_msg" col="12">
                                    <textarea class="form-control" id="close_msg" rows="4" name="close_msg"
                                        placeholder="Message shown when the site is in maintenance mode">{{ readConfig('close_msg') }}</textarea>
                                </x-form-field>
                            </div>
                        </x-form-section>
                    </form>
                </div>
                @endcan

                @can('invoice_settings')
                <div class="tab-pane fade {{ $activeTab === 'invoice-settings' ? 'active show' : '' }}"
                    id="settings-pane-invoice-settings"
                    role="tabpanel"
                    aria-labelledby="settings-tab-invoice-settings">

                    <form class="form-modern settings-form" action="{{ route('backend.admin.settings.website.invoice.update') }}" method="post">
                        @csrf
                        <x-settings-panel-header title="Invoice Settings" icon="fas fa-file-invoice" />
                        <x-form-section>
                            <x-form-field label="Note to Customer" name="note_to_customer_invoice" col="12">
                                <input type="text" class="form-control" id="note_to_customer_invoice"
                                    name="note_to_customer_invoice" placeholder="Enter message for invoice"
                                    value="{{ readConfig('note_to_customer_invoice') }}">
                            </x-form-field>
                            <x-form-field label="POS Invoice Width" name="receiptMaxwidth" col="md-6">
                                <select name="receiptMaxwidth" id="receiptMaxwidth" class="form-control">
                                    <option value="300px" {{ readConfig('receiptMaxwidth') == '300px' ? 'selected' : '' }}>Small</option>
                                    <option value="400px" {{ readConfig('receiptMaxwidth') == '400px' ? 'selected' : '' }}>Medium</option>
                                    <option value="500px" {{ readConfig('receiptMaxwidth') == '500px' ? 'selected' : '' }}>Large</option>
                                </select>
                            </x-form-field>
                        </x-form-section>
                        <x-form-section title="Invoice Fields">
                            <x-form-switch name="is_show_logo_invoice" id="is_show_logo_invoice" label="Show Logo"
                                :checked="readConfig('is_show_logo_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_site_invoice" id="is_show_site_invoice" label="Show Site Name"
                                :checked="readConfig('is_show_site_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_phone_invoice" id="is_show_phone_invoice" label="Show Phone"
                                :checked="readConfig('is_show_phone_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_email_invoice" id="is_show_email_invoice" label="Show Email"
                                :checked="readConfig('is_show_email_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_address_invoice" id="is_show_address_invoice" label="Show Address"
                                :checked="readConfig('is_show_address_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_customer_invoice" id="is_show_customer_invoice" label="Show Customer"
                                :checked="readConfig('is_show_customer_invoice') == 1" col="md-6" />
                            <x-form-switch name="is_show_note_invoice" id="is_show_note_invoice" label="Show Note to Customer"
                                :checked="readConfig('is_show_note_invoice') == 1" col="md-6" />
                        </x-form-section>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('input[type=radio][name=is_live]').on('change', function() {
        if (this.value === '0') {
            $('#close_msg_div').removeClass('d-none');
        } else {
            $('#close_msg_div').addClass('d-none');
        }
    });

    document.querySelectorAll('.form-image-upload').forEach(function(container) {
        var input = container.querySelector('.form-image-upload__input');
        var preview = container.querySelector('.form-image-upload__img');
        var placeholder = container.querySelector('.form-image-upload__placeholder');

        if (!input) {
            return;
        }

        container.addEventListener('click', function(event) {
            if (event.target === input) {
                return;
            }
            input.click();
        });

        input.addEventListener('change', function() {
            if (!this.files || !this.files[0]) {
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                if (preview) {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endpush
