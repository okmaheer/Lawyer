@php
    $general_settings = !empty(App\SiteManagement::getMetaValue('general_settings')) ? App\SiteManagement::getMetaValue('general_settings') : array();
    $enable_loader = !empty($general_settings) && !empty($general_settings['enable_loader']) ? $general_settings['enable_loader'] : true;
@endphp
@if (!empty($enable_loader) && ($enable_loader === true || $enable_loader === 'true'))
    <div class="preloader-outer">
        <div class="dc-preloader-holder">
            <div class="dc-loader"></div>
        </div>
    </div>
@endif