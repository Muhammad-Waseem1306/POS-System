@props(['activeTab' => 'website-info'])

<div class="col-12 col-lg-3 mb-3 mb-lg-0">
    <div class="content-card settings-tabs">
        <nav class="settings-tabs__nav nav flex-column" id="settings-nav" role="tablist" aria-orientation="vertical">
            @can('website_settings')
            <a class="nav-link {{ $activeTab === 'website-info' ? 'active' : '' }}"
                id="settings-tab-website-info"
                data-toggle="pill"
                href="#settings-pane-website-info"
                role="tab"
                aria-controls="settings-pane-website-info"
                aria-selected="{{ $activeTab === 'website-info' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-desktop"></i></span>
                <span>Website Info</span>
            </a>
            @endcan
            @can('contact_settings')
            <a class="nav-link {{ $activeTab === 'contacts' ? 'active' : '' }}"
                id="settings-tab-contacts"
                data-toggle="pill"
                href="#settings-pane-contacts"
                role="tab"
                aria-controls="settings-pane-contacts"
                aria-selected="{{ $activeTab === 'contacts' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-address-book"></i></span>
                <span>Contacts</span>
            </a>
            @endcan
            @can('socials_settings')
            <a class="nav-link {{ $activeTab === 'social-links' ? 'active' : '' }}"
                id="settings-tab-social-links"
                data-toggle="pill"
                href="#settings-pane-social-links"
                role="tab"
                aria-controls="settings-pane-social-links"
                aria-selected="{{ $activeTab === 'social-links' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-share-alt"></i></span>
                <span>Social Links</span>
            </a>
            @endcan
            @can('style_settings')
            <a class="nav-link {{ $activeTab === 'style-settings' ? 'active' : '' }}"
                id="settings-tab-style-settings"
                data-toggle="pill"
                href="#settings-pane-style-settings"
                role="tab"
                aria-controls="settings-pane-style-settings"
                aria-selected="{{ $activeTab === 'style-settings' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-swatchbook"></i></span>
                <span>Style Settings</span>
            </a>
            @endcan
            @can('custom_settings')
            <a class="nav-link {{ $activeTab === 'custom-css' ? 'active' : '' }}"
                id="settings-tab-custom-css"
                data-toggle="pill"
                href="#settings-pane-custom-css"
                role="tab"
                aria-controls="settings-pane-custom-css"
                aria-selected="{{ $activeTab === 'custom-css' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-code"></i></span>
                <span>Custom CSS</span>
            </a>
            @endcan
            @can('notification_settings')
            <a class="nav-link {{ $activeTab === 'notification-settings' ? 'active' : '' }}"
                id="settings-tab-notification-settings"
                data-toggle="pill"
                href="#settings-pane-notification-settings"
                role="tab"
                aria-controls="settings-pane-notification-settings"
                aria-selected="{{ $activeTab === 'notification-settings' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                <span>Notification Settings</span>
            </a>
            @endcan
            @can('website_status_settings')
            <a class="nav-link {{ $activeTab === 'website-status' ? 'active' : '' }}"
                id="settings-tab-website-status"
                data-toggle="pill"
                href="#settings-pane-website-status"
                role="tab"
                aria-controls="settings-pane-website-status"
                aria-selected="{{ $activeTab === 'website-status' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-power-off"></i></span>
                <span>Website Status</span>
            </a>
            @endcan
            @can('invoice_settings')
            <a class="nav-link {{ $activeTab === 'invoice-settings' ? 'active' : '' }}"
                id="settings-tab-invoice-settings"
                data-toggle="pill"
                href="#settings-pane-invoice-settings"
                role="tab"
                aria-controls="settings-pane-invoice-settings"
                aria-selected="{{ $activeTab === 'invoice-settings' ? 'true' : 'false' }}">
                <span class="settings-tabs__icon" aria-hidden="true"><i class="fas fa-file-invoice"></i></span>
                <span>Invoice Settings</span>
            </a>
            @endcan
        </nav>
    </div>
</div>
